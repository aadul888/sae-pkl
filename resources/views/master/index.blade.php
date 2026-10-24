@extends('layouts.app', ['title' => 'Integrasi Data Master'])

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">
                Integrasi & Data Master SAE Utama
            </h1>
            <p style="color: #64748b; font-size: 0.95rem;">
                Data referensi tersinkronisasi langsung dari database SAE Utama (Mode: Read-Only).
            </p>
        </div>

        @if (auth()->user()->isAdmin())
            <form method="POST" action="{{ route('master.sync') }}">
                @csrf
                <button type="submit" style="background: #2563eb; color: #fff; border: none; padding: 0.65rem 1.25rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    🔄 Sinkronkan Data Sekarang
                </button>
            </form>
        @endif
    </div>

    <!-- Cards Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem;">
            <div style="font-size: 0.8125rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Sekolah</div>
            <div style="font-size: 2rem; font-weight: 800; color: #1e40af; margin-top: 0.5rem;">{{ $stats['sekolah'] }}</div>
            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">Profil identitas sekolah</div>
        </div>

        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem;">
            <div style="font-size: 0.8125rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Jurusan / Program</div>
            <div style="font-size: 2rem; font-weight: 800; color: #059669; margin-top: 0.5rem;">{{ $stats['jurusan'] }}</div>
            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">Kompetensi keahlian aktif</div>
        </div>

        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem;">
            <div style="font-size: 0.8125rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Guru / GTK</div>
            <div style="font-size: 2rem; font-weight: 800; color: #d97706; margin-top: 0.5rem;">{{ $stats['guru'] }}</div>
            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">Tenaga pendidik tersinkron</div>
        </div>

        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem;">
            <div style="font-size: 0.8125rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Peserta Didik</div>
            <div style="font-size: 2rem; font-weight: 800; color: #7c3aed; margin-top: 0.5rem;">{{ $stats['siswa'] }}</div>
            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">Siswa referensi aktif</div>
        </div>
    </div>

    <!-- Jurusan List Table -->
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem;">
        <h3 style="font-size: 1.125rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem;">
            Daftar Jurusan & Penugasan Kepala Program (Kajur)
        </h3>

        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f5f9; color: #64748b;">
                    <th style="padding: 0.75rem 0.5rem;">Kode Jurusan</th>
                    <th style="padding: 0.75rem 0.5rem;">Nama Jurusan / Program Keahlian</th>
                    <th style="padding: 0.75rem 0.5rem;">Kepala Jurusan (Kajur)</th>
                    <th style="padding: 0.75rem 0.5rem;">Total Siswa</th>
                    <th style="padding: 0.75rem 0.5rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jurusanList as $jur)
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td style="padding: 0.75rem 0.5rem; font-family: monospace; font-weight: 700; color: #2563eb;">{{ $jur->jurusan_id }}</td>
                        <td style="padding: 0.75rem 0.5rem; font-weight: 600;">{{ $jur->nama_jurusan }}</td>
                        <td style="padding: 0.75rem 0.5rem; color: #334155;">{{ $jur->kajur_nama ?? 'Belum Ditugaskan' }}</td>
                        <td style="padding: 0.75rem 0.5rem;">{{ $jur->siswa_count }} Siswa</td>
                        <td style="padding: 0.75rem 0.5rem;">
                            <a href="{{ route('students.index', ['jurusan_id' => $jur->jurusan_id]) }}" style="color: #2563eb; text-decoration: none; font-weight: 600;">Lihat Siswa &rarr;</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 1.5rem; text-align: center; color: #94a3b8;">
                            Belum ada data jurusan. Silakan jalankan sinkronisasi data master.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
