@extends('layouts.app', ['title' => 'Dashboard Utama'])

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">
                Selamat Datang, {{ $user->name }}
            </h1>
            <p class="page-subtitle">
                Masuk sebagai <strong style="color: var(--text-body); text-transform: uppercase;">{{ str_replace('_', ' ', $user->role) }}</strong> &bull; Sistem Fondasi Fase 1 Aktif.
            </p>
        </div>
    </div>

    <!-- Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <div class="stat-card">
            <div class="stat-card-title">Pengguna Sistem</div>
            <div class="stat-card-value">{{ $stats['total_users'] }}</div>
            <div class="stat-card-desc">Akun terdaftar di database</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-title">Audit Trail</div>
            <div class="stat-card-value" style="color: #059669;">{{ $stats['total_audit_logs'] }}</div>
            <div class="stat-card-desc">Aktivitas tercatat</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-title">Tipe Institusi</div>
            <div class="stat-card-value" style="color: #d97706; font-size: 1.5rem;">{{ $stats['institution_type'] }}</div>
            <div class="stat-card-desc">Pendidikan Vokasi SMK</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-title">Status Fondasi</div>
            <div class="stat-card-value" style="color: #4338ca; font-size: 1.25rem;">FASE 1 SELESAI</div>
            <div class="stat-card-desc">Siap integrasi data master</div>
        </div>
    </div>

    <!-- Audit Logs Section -->
    <x-ui.card title="Riwayat Aktivitas Terakhir (Audit Log)">
        <x-ui.table :headers="['Waktu', 'Pengguna', 'Peran', 'Aksi', 'IP Address']">
            @forelse ($recentAudits as $audit)
                <tr>
                    <td style="color: var(--text-muted);">{{ $audit->created_at->format('d/m/Y H:i:s') }}</td>
                    <td style="font-weight: 600; color: #1e293b;">{{ $audit->user?->name ?? 'System' }}</td>
                    <td>
                        <x-ui.badge :role="$audit->role ?? 'siswa'">
                            {{ $audit->role ?? '-' }}
                        </x-ui.badge>
                    </td>
                    <td style="font-family: monospace; color: var(--accent);">{{ $audit->action }}</td>
                    <td style="color: #94a3b8;">{{ $audit->ip_address ?? '-' }}</td>
                </tr>
            @empty
                <tr class="empty-state-row">
                    <td colspan="5" class="empty-state">Belum ada catatan aktivitas.</td>
                </tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>
@endsection
