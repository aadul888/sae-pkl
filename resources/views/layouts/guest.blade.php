<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Masuk' }} — {{ config('app.name', 'SAE PKL') }}</title>
    <style>
        :root {
            --primary: #1e40af;
            --primary-hover: #1d4ed8;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --danger: #ef4444;
            --success: #10b981;
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
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .auth-container {
            width: 100%;
            max-width: 420px;
        }
        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            border-radius: 12px;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .brand-title {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: #0f172a;
        }
        .brand-subtitle {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }
        .auth-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
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
        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-muted);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #fff;
            background: var(--primary);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }
        .btn:hover {
            background: var(--primary-hover);
        }
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }
        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .alert-info {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.8125rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
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
