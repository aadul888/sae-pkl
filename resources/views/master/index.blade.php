@extends('layouts.app', ['title' => 'Integrasi Data Master'])

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">
                Integrasi & Data Master SAE Utama
            </h1>
            <p class="page-subtitle">
                Data referensi tersinkronisasi langsung dari database SAE Utama (Mode: Read-Only).
            </p>
        </div>

        @if (auth()->user()->isAdmin())
            <form method="POST" action="{{ route('master.sync') }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    🔄 Sinkronkan Data Sekarang
                </button>
            </form>
        @endif
    </div>

    <!-- Cards Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <div class="stat-card">
            <div class="stat-card-title">Sekolah</div>
            <div class="stat-card-value">{{ $stats['sekolah'] }}</div>
            <div class="stat-card-desc">Profil identitas sekolah</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-title">Jurusan / Program</div>
            <div class="stat-card-value" style="color: #059669;">{{ $stats['jurusan'] }}</div>
            <div class="stat-card-desc">Kompetensi keahlian aktif</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-title">Guru / GTK</div>
            <div class="stat-card-value" style="color: #d97706;">{{ $stats['guru'] }}</div>
            <div class="stat-card-desc">Tenaga pendidik tersinkron</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-title">Peserta Didik</div>
            <div class="stat-card-value" style="color: #7c3aed;">{{ $stats['siswa'] }}</div>
            <div class="stat-card-desc">Siswa referensi aktif</div>
        </div>
    </div>

    <!-- Jurusan List Table -->
    <x-ui.card title="Daftar Jurusan & Penugasan Kepala Program (Kajur)">
        <x-ui.table :headers="['Kode Jurusan', 'Nama Jurusan / Program Keahlian', 'Kepala Jurusan (Kajur)', 'Total Siswa', 'Aksi']">
            @forelse ($jurusanList as $jur)
                <tr>
                    <td style="font-family: monospace; font-weight: 700; color: var(--accent);">{{ $jur->jurusan_id }}</td>
                    <td style="font-weight: 600; color: var(--text-main);">{{ $jur->nama_jurusan }}</td>
                    <td style="color: var(--text-body);">{{ $jur->kajur_nama ?? 'Belum Ditugaskan' }}</td>
                    <td>{{ $jur->siswa_count }} Siswa</td>
                    <td>
                        <a href="{{ route('students.index', ['jurusan_id' => $jur->jurusan_id]) }}" style="color: var(--accent); text-decoration: none; font-weight: 600;">
                            Lihat Siswa &rarr;
                        </a>
                    </td>
                </tr>
            @empty
                <tr class="empty-state-row">
                    <td colspan="5" class="empty-state">
                        Belum ada data jurusan. Silakan jalankan sinkronisasi data master.
                    </td>
                </tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>
@endsection
