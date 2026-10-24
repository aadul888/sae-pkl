@extends('layouts.app', ['title' => 'Dashboard Utama'])

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">
            Selamat Datang, {{ $user->name }}
        </h1>
        <p style="color: #64748b; font-size: 0.95rem;">
            Masuk sebagai <strong style="color: #334155; text-transform: uppercase;">{{ str_replace('_', ' ', $user->role) }}</strong> &bull; Sistem Fondasi Fase 1 Aktif.
        </p>
    </div>

    <!-- Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="font-size: 0.8125rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Pengguna Sistem</div>
            <div style="font-size: 2rem; font-weight: 800; color: #1e40af; margin-top: 0.5rem;">{{ $stats['total_users'] }}</div>
            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">Akun terdaftar di database</div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="font-size: 0.8125rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Audit Trail</div>
            <div style="font-size: 2rem; font-weight: 800; color: #059669; margin-top: 0.5rem;">{{ $stats['total_audit_logs'] }}</div>
            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">Aktivitas tercatat</div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="font-size: 0.8125rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Tipe Institusi</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #d97706; margin-top: 0.75rem;">{{ $stats['institution_type'] }}</div>
            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">Pendidikan Vokasi SMK</div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="font-size: 0.8125rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Status Fondasi</div>
            <div style="font-size: 1.25rem; font-weight: 800; color: #4338ca; margin-top: 0.85rem;">FASE 1 SELESAI</div>
            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">Siap integrasi data master</div>
        </div>
    </div>

    <!-- Audit Logs Section -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <h3 style="font-size: 1.125rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem;">
            Riwayat Aktivitas Terakhir (Audit Log)
        </h3>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                    <tr style="border-bottom: 2px solid #f1f5f9; color: #64748b;">
                        <th style="padding: 0.75rem 0.5rem;">Waktu</th>
                        <th style="padding: 0.75rem 0.5rem;">Pengguna</th>
                        <th style="padding: 0.75rem 0.5rem;">Peran</th>
                        <th style="padding: 0.75rem 0.5rem;">Aksi</th>
                        <th style="padding: 0.75rem 0.5rem;">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentAudits as $audit)
                        <tr style="border-bottom: 1px solid #f8fafc;">
                            <td style="padding: 0.75rem 0.5rem; color: #64748b;">{{ $audit->created_at->format('d/m/Y H:i:s') }}</td>
                            <td style="padding: 0.75rem 0.5rem; font-weight: 600; color: #1e293b;">{{ $audit->user?->name ?? 'System' }}</td>
                            <td style="padding: 0.75rem 0.5rem;"><span class="badge badge-{{ $audit->role }}">{{ $audit->role ?? '-' }}</span></td>
                            <td style="padding: 0.75rem 0.5rem; font-family: monospace; color: #2563eb;">{{ $audit->action }}</td>
                            <td style="padding: 0.75rem 0.5rem; color: #94a3b8;">{{ $audit->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 1.5rem; text-align: center; color: #94a3b8;">Belum ada catatan aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
