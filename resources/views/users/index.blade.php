@extends('layouts.app')
@section('title', 'Users')

@section('content')
<div class="page-header">
    <div>
        <h2>User Management</h2>
        <p>Manage system users and their roles</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Add User
    </a>
</div>

<div class="card" style="margin-bottom:20px">
    <form method="GET" style="display:flex;gap:12px">
        <div class="search-bar" style="flex:1">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="form-control" placeholder="Search name or email..." value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="{{ route('users.index') }}" class="btn btn-outline">Reset</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>User</th><th>Email</th><th>Phone</th><th>Role</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td style="color:var(--text-muted)">{{ $loop->iteration }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#10b981);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;color:#fff">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600">{{ $user->name }}</div>
                                <div style="font-size:11px;color:var(--text-muted)">ID: {{ $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td>
                        @foreach($user->roles as $role)
                        <span class="badge badge-info">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                        @if($user->otp_code && $user->status !== 'active')
                        <div style="margin-top:4px">
                            <span class="badge badge-warning" style="font-family:monospace;font-size:11px" title="Share this 6-digit OTP code with the cashier/user to activate their account">
                                🔑 OTP: {{ $user->otp_code }}
                            </span>
                        </div>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-outline btn-sm btn-icon" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('users.toggle-status', $user) }}">
                                @csrf
                                <button type="submit" class="btn {{ $user->status === 'active' ? 'btn-warning' : 'btn-success' }} btn-sm btn-icon"
                                        title="{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state"><i class="fas fa-users"></i><h3>No Users</h3></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px">{{ $users->links('vendor.pagination.custom') }}</div>
</div>
@endsection
