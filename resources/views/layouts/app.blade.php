<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} — {{ config('app.name', 'SAE PKL') }}</title>
    <style>
        :root {
            --primary: #1e40af;
            --primary-hover: #1d4ed8;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        body {
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
        }
        .navbar-logo {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
        }
        .navbar-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #0f172a;
        }
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .user-info {
            text-align: right;
            font-size: 0.875rem;
        }
        .user-name {
            font-weight: 600;
            color: #1e293b;
        }
        .badge {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: uppercase;
        }
        .badge-admin { background: #dbeafe; color: #1e40af; }
        .badge-kepsek { background: #ede9fe; color: #6d28d9; }
        .badge-waka_hubin { background: #fef3c7; color: #92400e; }
        .badge-kajur { background: #e0e7ff; color: #3730a3; }
        .badge-guru_pembimbing { background: #dcfce7; color: #166534; }
        .badge-pembimbing_industri { background: #ffedd5; color: #9a3412; }
        .badge-siswa { background: #f1f5f9; color: #475569; }

        .btn-logout {
            background: none;
            border: 1px solid var(--border);
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-logout:hover {
            background: #f1f5f9;
            color: var(--danger);
            border-color: #fca5a5;
        }
        .main-content {
            flex: 1;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }
        .alert {
            padding: 0.875rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .alert-warning {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.65rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-primary {
            background: var(--primary);
            color: #fff;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
        }
        .btn-secondary {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid var(--border);
        }
        .btn-secondary:hover {
            background: #e2e8f0;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #334155;
        }
        .form-control {
            width: 100%;
            padding: 0.65rem 0.875rem;
            font-size: 0.95rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            color: var(--text-main);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .footer {
            background: #ffffff;
            border-top: 1px solid var(--border);
            padding: 1.25rem 1.5rem;
            text-align: center;
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin-top: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
            <a href="{{ route('dashboard') }}" class="navbar-brand">
                <div class="navbar-logo">SAE</div>
                <div>
                    <div class="navbar-title">SAE PKL</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Sistem Aplikasi Edukasi</div>
                </div>
            </a>

            @auth
                <div style="display: flex; gap: 1rem; font-size: 0.875rem; font-weight: 600;">
                    <a href="{{ route('dashboard') }}" style="color: {{ request()->routeIs('dashboard') ? '#1e40af' : '#64748b' }}; text-decoration: none;">Dashboard</a>
                    @if (in_array(auth()->user()->role, ['admin', 'waka_hubin'], true))
                        <a href="{{ route('master.index') }}" style="color: {{ request()->routeIs('master.*') ? '#1e40af' : '#64748b' }}; text-decoration: none;">Data Master</a>
                    @endif
                    @if (in_array(auth()->user()->role, ['admin', 'waka_hubin', 'kepsek', 'kajur', 'guru_pembimbing'], true))
                        <a href="{{ route('students.index') }}" style="color: {{ request()->routeIs('students.*') ? '#1e40af' : '#64748b' }}; text-decoration: none;">Peserta Didik</a>
                    @endif
                </div>
            @endauth
        </div>

        @auth
            <div class="navbar-user">
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div style="display: flex; gap: 0.5rem; align-items: center; justify-content: flex-end; margin-top: 0.2rem;">
                        <span class="badge badge-{{ auth()->user()->role }}">
                            {{ str_replace('_', ' ', auth()->user()->role) }}
                        </span>
                        <a href="{{ route('profile.password') }}" style="font-size: 0.75rem; color: #64748b; text-decoration: underline;">Ubah Password</a>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">Keluar</button>
                </form>
            </div>
        @endauth
    </nav>

    <main class="main-content">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('warning_password'))
            <div class="alert alert-warning" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                <span>⚠️ {{ session('warning_password') }}</span>
                <a href="{{ route('profile.password') }}" style="color: #92400e; font-weight: 700; text-decoration: underline;">
                    Perbarui Kata Sandi Sekarang &rarr;
                </a>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer">
        &copy; {{ date('Y') }} SAE PKL &bull; Modul Praktik Kerja Lapangan SMK &bull; Versi 1.0.0 (Pondasi Selesai)
    </footer>
</body>
</html>
