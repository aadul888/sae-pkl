<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1e40af">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SAE PKL">
    <title>{{ $title ?? 'Dashboard' }} — {{ config('app.name', 'SAE PKL') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar">
        <div style="display: flex; align-items: center; gap: 2rem;">
            <a href="{{ route('dashboard') }}" class="navbar-brand">
                <div class="navbar-logo">SAE</div>
                <div>
                    <div class="navbar-title">SAE PKL</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Sistem Aplikasi Edukasi</div>
                </div>
            </a>

            @auth
                <div class="desktop-nav">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                    @if (in_array(auth()->user()->role, ['admin', 'waka_hubin'], true))
                        <a href="{{ route('master.index') }}" class="{{ request()->routeIs('master.*') ? 'active' : '' }}">Data Master</a>
                    @endif
                    @if (in_array(auth()->user()->role, ['admin', 'waka_hubin', 'kepsek', 'kajur', 'guru_pembimbing'], true))
                        <a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.*') ? 'active' : '' }}">Peserta Didik</a>
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
                        <a href="{{ route('profile.password') }}" style="font-size: 0.75rem; color: var(--text-muted); text-decoration: underline;">Ubah Password</a>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout" title="Keluar dari sesi">Keluar</button>
                </form>
            </div>
        @endauth
    </nav>

    <main class="main-content">
        @if (session('success'))
            <x-ui.alert type="success" :dismissible="true">
                {{ session('success') }}
            </x-ui.alert>
        @endif

        @if (session('warning_password'))
            <div class="alert alert-warning" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                <span>⚠️ {{ session('warning_password') }}</span>
                <a href="{{ route('profile.password') }}" style="color: var(--warning-text); font-weight: 700; text-decoration: underline;">
                    Perbarui Kata Sandi Sekarang &rarr;
                </a>
            </div>
        @endif

        @yield('content')
    </main>

    @auth
        <nav class="mobile-bottom-nav">
            <a href="{{ route('dashboard') }}" class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="mobile-nav-icon">📊</span>
                <span>Dashboard</span>
            </a>
            @if (in_array(auth()->user()->role, ['admin', 'waka_hubin'], true))
                <a href="{{ route('master.index') }}" class="mobile-nav-item {{ request()->routeIs('master.*') ? 'active' : '' }}">
                    <span class="mobile-nav-icon">🗄️</span>
                    <span>Master</span>
                </a>
            @endif
            @if (in_array(auth()->user()->role, ['admin', 'waka_hubin', 'kepsek', 'kajur', 'guru_pembimbing'], true))
                <a href="{{ route('students.index') }}" class="mobile-nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <span class="mobile-nav-icon">👨‍🎓</span>
                    <span>Siswa</span>
                </a>
            @endif
            <a href="{{ route('profile.password') }}" class="mobile-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <span class="mobile-nav-icon">🔒</span>
                <span>Sandi</span>
            </a>
        </nav>
    @endauth

    <footer class="footer">
        &copy; {{ date('Y') }} SAE PKL &bull; Modul Praktik Kerja Lapangan SMK &bull; Versi 1.0.0 (Pondasi Selesai)
    </footer>
</body>
</html>
