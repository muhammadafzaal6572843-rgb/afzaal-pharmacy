<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->can('view dashboard')) {
                return redirect()->route('dashboard');
            }
            if (Auth::user()->can('access pos')) {
                return redirect()->route('pos.index');
            }
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            if (Auth::user()->status !== 'active') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is pending admin approval or has been deactivated.']);
            }
            $request->session()->regenerate();

            if (!Auth::user()->can('view dashboard') && Auth::user()->can('access pos')) {
                return redirect()->route('pos.index');
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        $roles = Role::whereIn('name', ['Pharmacist', 'Cashier', 'Store Manager', 'Admin'])->get();
        return view('auth.register', compact('roles'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|exists:roles,name',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Generate 6-digit OTP verification code
        $otpCode = str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);

        // 1. Create account as INACTIVE (Pending Admin Approval / OTP verification)
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'status'   => 'inactive',
            'otp_code' => $otpCode,
        ]);

        $user->assignRole($data['role']);

        // 2. Dispatch email notification to Admin Gmail with OTP code & details
        try {
            $setting    = Setting::get();
            $adminEmail = $setting->email ?: config('mail.from.address', 'admin@pharmacy.com');
            $roleName   = $data['role'];

            $mailBody = "⚠️ NEW ACCOUNT REGISTRATION REQUEST - AFZAAL PHARMACY SYSTEM\n\n"
                      . "A new staff account registration has been submitted:\n"
                      . "---------------------------------------------------------\n"
                      . "Full Name:       {$user->name}\n"
                      . "Email Address:   {$user->email}\n"
                      . "Phone Number:    " . ($user->phone ?? 'N/A') . "\n"
                      . "Requested Role:  {$roleName}\n"
                      . "Verification OTP: {$otpCode}\n"
                      . "Account Status:  INACTIVE (Pending OTP / Approval)\n"
                      . "Submitted At:    " . now()->format('Y-m-d H:i:s') . "\n\n"
                      . "Provide the OTP ({$otpCode}) to the user so they can activate their account at:\n"
                      . route('verify-otp') . "\n\n"
                      . "Or review and activate directly via Admin Dashboard:\n"
                      . route('users.index') . "\n";

            Mail::raw($mailBody, function ($message) use ($adminEmail, $user) {
                $message->to($adminEmail)
                        ->subject("🔔 New User OTP & Registration Notice: {$user->name} ({$user->email})");
            });
        } catch (\Throwable $e) {
            Log::warning("Registration Email Notice Failed: " . $e->getMessage());
        }

        return redirect()->route('login')->with('success', "Registration request submitted! Your account OTP is [{$otpCode}]. Give this OTP to the Admin or enter it on the OTP Verification page to activate your account.");
    }

    public function showVerifyOtp(Request $request)
    {
        $email = $request->get('email', '');
        return view('auth.verify_otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'otp_code' => 'required|string|size:6',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->otp_code !== $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Invalid OTP Verification Code. Please check with your Super Admin.'])->withInput();
        }

        // Update password if provided, clear OTP code, set status to active
        $updateData = [
            'status'   => 'active',
            'otp_code' => null,
        ];
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        Auth::login($user);
        $request->session()->regenerate();

        if (!$user->can('view dashboard') && $user->can('access pos')) {
            return redirect()->route('pos.index')->with('success', 'Account verified and activated successfully!');
        }

        return redirect()->route('dashboard')->with('success', 'Account verified and activated successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
