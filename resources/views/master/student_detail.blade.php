@extends('layouts.app', ['title' => 'Detail Siswa — ' . $siswa->nama])

@section('content')
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('students.index') }}" style="color: #64748b; text-decoration: none; font-size: 0.875rem; font-weight: 600;">
            &larr; Kembali ke Daftar Siswa
        </a>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-top: 0.5rem;">
            {{ $siswa->nama }}
        </h1>
        <p style="color: #64748b; font-size: 0.95rem;">
            NISN: {{ $siswa->nisn }} &bull; Rombel: {{ $siswa->nama_rombel ?? '-' }} &bull; Jurusan: {{ $siswa->jurusan_id }}
        </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
        <!-- Student Info Card -->
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem;">
            <h3 style="font-size: 1.125rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem;">
                Informasi Identitas Siswa
            </h3>

            <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.875rem;">
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">
                    <span style="color: #64748b;">ID Siswa (Dapodik):</span>
                    <span style="font-family: monospace; font-weight: 600;">{{ $siswa->peserta_didik_id }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">
                    <span style="color: #64748b;">NISN:</span>
                    <span style="font-weight: 600;">{{ $siswa->nisn }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">
                    <span style="color: #64748b;">NIPD / NIS:</span>
                    <span>{{ $siswa->nipd ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">
                    <span style="color: #64748b;">Jenis Kelamin:</span>
                    <span>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">
                    <span style="color: #64748b;">No. HP / WhatsApp:</span>
                    <span>{{ $siswa->no_hp ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">
                    <span style="color: #64748b;">Status Sinkronisasi:</span>
                    <span style="color: #059669; font-weight: 600;">Tersinkron ({{ $siswa->synced_at?->format('d/m/Y H:i') }})</span>
                </div>
            </div>
        </div>

        <!-- Verification Form Card -->
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem;">
            <h3 style="font-size: 1.125rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem;">
                Verifikasi Kesiapan PKL (Wewenang Kajur)
            </h3>

            @can('verifyKesiapan', $siswa)
                <form method="POST" action="{{ route('students.verify', $siswa->peserta_didik_id) }}">
                    @csrf

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem;">
                            Keputusan Kesiapan Siswa:
                        </label>
                        <select name="status_kesiapan" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.95rem;">
                            <option value="belum_verifikasi" {{ $siswa->status_kesiapan === 'belum_verifikasi' ? 'selected' : '' }}>⏳ Belum Diverifikasi</option>
                            <option value="siap" {{ $siswa->status_kesiapan === 'siap' ? 'selected' : '' }}>✅ Siap Mengikuti PKL</option>
                            <option value="tidak_siap" {{ $siswa->status_kesiapan === 'tidak_siap' ? 'selected' : '' }}>❌ Tidak Siap / Ditunda</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem;">
                            Catatan Evaluasi / Syarat:
                        </label>
                        <textarea name="catatan_kesiapan" rows="4" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.95rem;" placeholder="Contoh: Seluruh nilai semester genap tuntas, berkas izin orang tua lengkap.">{{ old('catatan_kesiapan', $siswa->catatan_kesiapan) }}</textarea>
                    </div>

                    <button type="submit" style="background: #1e40af; color: #fff; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%;">
                        Simpan Keputusan Kesiapan
                    </button>
                </form>
            @else
                <div style="padding: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div style="font-size: 0.875rem; color: #64748b;">Status Kesiapan Saat Ini:</div>
                    <div style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0.5rem 0;">
                        {{ strtoupper(str_replace('_', ' ', $siswa->status_kesiapan)) }}
                    </div>
                    <p style="font-size: 0.875rem; color: #64748b;">
                        {{ $siswa->catatan_kesiapan ?: 'Belum ada catatan evaluasi dari Kepala Jurusan.' }}
                    </p>
                    <div style="margin-top: 1rem; font-size: 0.75rem; color: #94a3b8;">
                        * Hak verifikasi kesiapan berada di bawah wewenang Kepala Jurusan {{ $siswa->jurusan_id }}.
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection
