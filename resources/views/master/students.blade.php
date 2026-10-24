@extends('layouts.app', ['title' => 'Daftar Peserta Didik'])

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">
                Daftar Peserta Didik PKL
            </h1>
            <p style="color: #64748b; font-size: 0.95rem;">
                @if ($user->isKajur())
                    <span style="background: #e0e7ff; color: #3730a3; padding: 0.2rem 0.6rem; border-radius: 9999px; font-weight: 700; font-size: 0.8125rem;">
                        🔒 Terkunci Pada Jurusan Anda: {{ $user->jurusan_id }}
                    </span>
                @else
                    <span>Cakupan: Seluruh Jurusan (Tingkat Sekolah)</span>
                @endif
            </p>
        </div>
    </div>

    <!-- Filter Form -->
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('students.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NISN, atau kelas..." style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px;">
            </div>

            @if (!$user->isKajur())
                <div style="min-width: 180px;">
                    <select name="jurusan_id" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px;">
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
                <select name="status" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px;">
                    <option value="">-- Status Kesiapan --</option>
                    <option value="belum_verifikasi" {{ request('status') == 'belum_verifikasi' ? 'selected' : '' }}>Belum Diverifikasi</option>
                    <option value="siap" {{ request('status') == 'siap' ? 'selected' : '' }}>Siap PKL</option>
                    <option value="tidak_siap" {{ request('status') == 'tidak_siap' ? 'selected' : '' }}>Tidak Siap</option>
                </select>
            </div>

            <button type="submit" style="background: #1e293b; color: #fff; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; cursor: pointer;">
                Filter
            </button>
            <a href="{{ route('students.index') }}" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; border: 1px solid #cbd5e1; border-radius: 6px; color: #64748b; text-decoration: none; font-size: 0.875rem;">
                Reset
            </a>
        </form>
    </div>

    <!-- Student Table -->
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f5f9; color: #64748b;">
                    <th style="padding: 0.75rem 0.5rem;">NISN</th>
                    <th style="padding: 0.75rem 0.5rem;">Nama Siswa</th>
                    <th style="padding: 0.75rem 0.5rem;">Kelas / Rombel</th>
                    <th style="padding: 0.75rem 0.5rem;">Jurusan</th>
                    <th style="padding: 0.75rem 0.5rem;">Status Kesiapan</th>
                    <th style="padding: 0.75rem 0.5rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $s)
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td style="padding: 0.75rem 0.5rem; font-family: monospace; color: #64748b;">{{ $s->nisn }}</td>
                        <td style="padding: 0.75rem 0.5rem; font-weight: 600; color: #0f172a;">{{ $s->nama }}</td>
                        <td style="padding: 0.75rem 0.5rem; color: #334155;">{{ $s->nama_rombel ?? '-' }}</td>
                        <td style="padding: 0.75rem 0.5rem;">
                            <span style="background: #f1f5f9; color: #334155; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 600;">
                                {{ $s->jurusan_id }}
                            </span>
                        </td>
                        <td style="padding: 0.75rem 0.5rem;">
                            @if ($s->status_kesiapan === 'siap')
                                <span style="background: #dcfce7; color: #166534; padding: 0.2rem 0.6rem; border-radius: 9999px; font-weight: 700; font-size: 0.75rem;">SIAP</span>
                            @elseif ($s->status_kesiapan === 'tidak_siap')
                                <span style="background: #fee2e2; color: #991b1b; padding: 0.2rem 0.6rem; border-radius: 9999px; font-weight: 700; font-size: 0.75rem;">TIDAK SIAP</span>
                            @else
                                <span style="background: #fef3c7; color: #92400e; padding: 0.2rem 0.6rem; border-radius: 9999px; font-weight: 700; font-size: 0.75rem;">BELUM VERIFIKASI</span>
                            @endif
                        </td>
                        <td style="padding: 0.75rem 0.5rem;">
                            <a href="{{ route('students.show', $s->peserta_didik_id) }}" style="color: #2563eb; text-decoration: none; font-weight: 600;">Detail & Verifikasi &rarr;</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 1.5rem; text-align: center; color: #94a3b8;">
                            Tidak ada data siswa yang cocok dengan kriteria filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 1.5rem;">
            {{ $students->links() }}
        </div>
    </div>
@endsection
