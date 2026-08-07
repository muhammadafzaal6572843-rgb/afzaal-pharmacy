<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Expense;
use App\Models\Customer;
use App\Models\Supplier;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view dashboard')) {
            if (auth()->user()->can('access pos')) {
                return redirect()->route('pos.index');
            }
            abort(403, 'Unauthorized access.');
        }

        $today      = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $yearStart  = Carbon::now()->startOfYear();

        // 1. TODAY (Daily)
        $totalSalesToday    = Sale::whereDate('created_at', $today)->sum('grand_total');
        $cogsToday          = \App\Models\SaleItem::whereHas('sale', fn($q) => $q->whereDate('created_at', $today))
            ->join('medicines', 'sale_items.medicine_id', '=', 'medicines.id')
            ->sum(\DB::raw('sale_items.quantity * medicines.purchase_price'));
        $expensesToday      = Expense::whereDate('expense_date', $today->toDateString())->sum('amount');
        $grossProfitToday   = max(0, $totalSalesToday - $cogsToday);
        $netProfitToday     = $grossProfitToday - $expensesToday;

        // 2. MONTHLY
        $totalSalesMonth    = Sale::where('created_at', '>=', $monthStart)->sum('grand_total');
        $cogsMonth          = \App\Models\SaleItem::whereHas('sale', fn($q) => $q->where('created_at', '>=', $monthStart))
            ->join('medicines', 'sale_items.medicine_id', '=', 'medicines.id')
            ->sum(\DB::raw('sale_items.quantity * medicines.purchase_price'));
        $expensesMonth      = Expense::where('expense_date', '>=', $monthStart->toDateString())->sum('amount');
        $grossProfitMonth   = max(0, $totalSalesMonth - $cogsMonth);
        $netProfitMonth     = $grossProfitMonth - $expensesMonth;

        // 3. YEARLY
        $totalSalesYear     = Sale::where('created_at', '>=', $yearStart)->sum('grand_total');
        $cogsYear           = \App\Models\SaleItem::whereHas('sale', fn($q) => $q->where('created_at', '>=', $yearStart))
            ->join('medicines', 'sale_items.medicine_id', '=', 'medicines.id')
            ->sum(\DB::raw('sale_items.quantity * medicines.purchase_price'));
        $expensesYear       = Expense::where('expense_date', '>=', $yearStart->toDateString())->sum('amount');
        $grossProfitYear    = max(0, $totalSalesYear - $cogsYear);
        $netProfitYear      = $grossProfitYear - $expensesYear;

        // KPI metrics
        $totalMedicines      = Medicine::active()->count();
        $totalPurchasesMonth = Purchase::where('created_at', '>=', $monthStart)->sum('grand_total');
        $totalExpensesMonth  = $expensesMonth;

        $lowStockCount    = Medicine::active()->lowStock()->count();
        $outOfStockCount  = Medicine::active()->outOfStock()->count();
        $expiredCount     = Medicine::active()->expired()->count();
        $expiringSoon     = Medicine::active()->expiringSoon(30)->count();
        $totalSuppliers   = Supplier::where('status', 'active')->count();
        $totalCustomers   = Customer::count();
        $todaySalesCount  = Sale::whereDate('created_at', $today)->count();

        // Recent sales
        $recentSales = Sale::with(['customer', 'user'])
            ->latest()
            ->take(8)
            ->get();

        // Low stock alerts
        $lowStockAlerts = Medicine::active()
            ->with('category')
            ->where('quantity', '<=', \DB::raw('reorder_level'))
            ->orderBy('quantity')
            ->take(8)
            ->get();

        // Expired medicines
        $expiredMedicines = Medicine::active()->expired()->with('category')->take(5)->get();

        // Expiring soon
        $expiringSoonMedicines = Medicine::active()->expiringSoon(30)->with('category')->take(5)->get();

        // Monthly sales chart data (last 7 days)
        $salesChart = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $daySales = Sale::whereDate('created_at', $date)->sum('grand_total');
            $dayCogs  = \App\Models\SaleItem::whereHas('sale', fn($q) => $q->whereDate('created_at', $date))
                ->join('medicines', 'sale_items.medicine_id', '=', 'medicines.id')
                ->sum(\DB::raw('sale_items.quantity * medicines.purchase_price'));
            $dayExp   = Expense::whereDate('expense_date', $date->toDateString())->sum('amount');
            $dayProfit= max(0, $daySales - $dayCogs) - $dayExp;

            $salesChart->push([
                'date'   => $date->format('M d'),
                'sales'  => $daySales,
                'profit' => $dayProfit,
                'count'  => Sale::whereDate('created_at', $date)->count(),
            ]);
        }

        return view('dashboard', compact(
            'totalMedicines', 'totalSalesToday', 'netProfitToday',
            'totalSalesMonth', 'netProfitMonth', 'totalSalesYear', 'netProfitYear',
            'totalPurchasesMonth', 'totalExpensesMonth',
            'lowStockCount', 'outOfStockCount', 'expiredCount', 'expiringSoon',
            'totalSuppliers', 'totalCustomers', 'todaySalesCount',
            'recentSales', 'lowStockAlerts', 'expiredMedicines',
            'expiringSoonMedicines', 'salesChart'
        ));
    }
}
