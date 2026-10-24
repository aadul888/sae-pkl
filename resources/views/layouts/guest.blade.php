<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1e40af">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SAE PKL">
    <title>{{ $title ?? 'Masuk' }} — {{ config('app.name', 'SAE PKL') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-wrapper">
    <div class="auth-container">
        <div class="brand-header">
            <div class="brand-logo">SAE</div>
            <h1 class="brand-title">SAE PKL</h1>
            <p class="brand-subtitle">Sistem Informasi Praktik Kerja Lapangan</p>
        </div>

        <div class="auth-card">
            @yield('content')
        </div>

        <div class="auth-footer">
            &copy; {{ date('Y') }} SAE PKL &bull; Bagian dari Ekosistem SAE
        </div>
    </div>
</body>
</html>
