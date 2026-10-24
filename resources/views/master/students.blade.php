@extends('layouts.app', ['title' => 'Daftar Peserta Didik'])

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">
                Daftar Peserta Didik PKL
            </h1>
            <p class="page-subtitle">
                @if ($user->isKajur())
                    <span class="badge badge-kajur">
                        🔒 Terkunci Pada Jurusan Anda: {{ $user->jurusan_id }}
                    </span>
                @else
                    <span>Cakupan: Seluruh Jurusan (Tingkat Sekolah)</span>
                @endif
            </p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <form method="GET" action="{{ route('students.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
                <div style="flex: 1; min-width: 220px;">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NISN, atau kelas..." class="form-control" style="min-height: 40px; padding: 0.5rem 0.75rem;">
                </div>

                @if (!$user->isKajur())
                    <div style="min-width: 180px;">
                        <select name="jurusan_id" class="form-select" style="min-height: 40px; padding: 0.5rem 0.75rem;">
                            <option value="">-- Semua Jurusan --</option>
                            @foreach ($jurusanList as $jur)
                                <option value="{{ $jur->jurusan_id }}" {{ request('jurusan_id') == $jur->jurusan_id ? 'selected' : '' }}>
                                    {{ $jur->jurusan_id }} - {{ $jur->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div style="min-width: 160px;">
                    <select name="status" class="form-select" style="min-height: 40px; padding: 0.5rem 0.75rem;">
                        <option value="">-- Status Kesiapan --</option>
                        <option value="belum_verifikasi" {{ request('status') == 'belum_verifikasi' ? 'selected' : '' }}>Belum Diverifikasi</option>
                        <option value="siap" {{ request('status') == 'siap' ? 'selected' : '' }}>Siap PKL</option>
                        <option value="tidak_siap" {{ request('status') == 'tidak_siap' ? 'selected' : '' }}>Tidak Siap</option>
                    </select>
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary btn-sm" style="min-height: 40px;">
                        Filter
                    </button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm" style="min-height: 40px;">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Student Table -->
    <x-ui.card>
        <x-ui.table :headers="['NISN', 'Nama Siswa', 'Kelas / Rombel', 'Jurusan', 'Status Kesiapan', 'Aksi']">
            @forelse ($students as $s)
                <tr>
                    <td style="font-family: monospace; color: var(--text-muted);">{{ $s->nisn }}</td>
                    <td style="font-weight: 600; color: var(--text-main);">{{ $s->nama }}</td>
                    <td style="color: var(--text-body);">{{ $s->nama_rombel ?? '-' }}</td>
                    <td>
                        <span style="background: var(--bg-alt); color: var(--text-body); padding: 0.2rem 0.5rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.8125rem;">
                            {{ $s->jurusan_id }}
                        </span>
                    </td>
                    <td>
                        @if ($s->status_kesiapan === 'siap')
                            <x-ui.badge type="siap">SIAP</x-ui.badge>
                        @elseif ($s->status_kesiapan === 'tidak_siap')
                            <x-ui.badge type="tidak_siap">TIDAK SIAP</x-ui.badge>
                        @else
                            <x-ui.badge type="belum_verifikasi">BELUM VERIFIKASI</x-ui.badge>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('students.show', $s->peserta_didik_id) }}" style="color: var(--accent); text-decoration: none; font-weight: 600; font-size: 0.875rem;">
                            Detail & Verifikasi &rarr;
                        </a>
                    </td>
                </tr>
            @empty
                <tr class="empty-state-row">
                    <td colspan="6" class="empty-state">
                        Tidak ada data siswa yang cocok dengan kriteria filter.
                    </td>
                </tr>
            @endforelse
        </x-ui.table>

        @if ($students->hasPages())
            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-subtle);">
                {{ $students->links() }}
            </div>
        @endif
    </x-ui.card>
@endsection
