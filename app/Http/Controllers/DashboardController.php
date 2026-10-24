<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $stats = [
            'total_users' => User::count(),
            'total_audit_logs' => AuditLog::count(),
            'app_name' => Setting::get('app_name', 'SAE PKL'),
            'institution_type' => Setting::get('institution_type', 'SMK'),
        ];

        $recentAudits = AuditLog::with('user')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('user', 'stats', 'recentAudits'));
    }
}
