@extends('layouts.app', ['title' => 'Detail Siswa — ' . $siswa->nama])

@section('content')
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('students.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem; font-weight: 600;">
            &larr; Kembali ke Daftar Siswa
        </a>
        <h1 class="page-title" style="margin-top: 0.5rem;">
            {{ $siswa->nama }}
        </h1>
        <p class="page-subtitle">
            NISN: {{ $siswa->nisn }} &bull; Rombel: {{ $siswa->nama_rombel ?? '-' }} &bull; Jurusan: {{ $siswa->jurusan_id }}
        </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
        <!-- Student Info Card -->
        <x-ui.card title="Informasi Identitas Siswa">
            <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.875rem;">
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.5rem;">
                    <span style="color: var(--text-muted);">ID Siswa (Dapodik):</span>
                    <span style="font-family: monospace; font-weight: 600;">{{ $siswa->peserta_didik_id }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.5rem;">
                    <span style="color: var(--text-muted);">NISN:</span>
                    <span style="font-weight: 600;">{{ $siswa->nisn }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.5rem;">
                    <span style="color: var(--text-muted);">NIPD / NIS:</span>
                    <span>{{ $siswa->nipd ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.5rem;">
                    <span style="color: var(--text-muted);">Jenis Kelamin:</span>
                    <span>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.5rem;">
                    <span style="color: var(--text-muted);">No. HP / WhatsApp:</span>
                    <span>{{ $siswa->no_hp ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.5rem;">
                    <span style="color: var(--text-muted);">Status Sinkronisasi:</span>
                    <span style="color: var(--success); font-weight: 600;">Tersinkron ({{ $siswa->synced_at?->format('d/m/Y H:i') }})</span>
                </div>
            </div>
        </x-ui.card>

        <!-- Verification Form Card -->
        <x-ui.card title="Verifikasi Kesiapan PKL (Wewenang Kajur)">
            @can('verifyKesiapan', $siswa)
                <form method="POST" action="{{ route('students.verify', $siswa->peserta_didik_id) }}">
                    @csrf

                    <x-ui.form-field label="Keputusan Kesiapan Siswa:" name="status_kesiapan">
                        <select name="status_kesiapan" id="status_kesiapan" class="form-select">
                            <option value="belum_verifikasi" {{ $siswa->status_kesiapan === 'belum_verifikasi' ? 'selected' : '' }}>⏳ Belum Diverifikasi</option>
                            <option value="siap" {{ $siswa->status_kesiapan === 'siap' ? 'selected' : '' }}>✅ Siap Mengikuti PKL</option>
                            <option value="tidak_siap" {{ $siswa->status_kesiapan === 'tidak_siap' ? 'selected' : '' }}>❌ Tidak Siap / Ditunda</option>
                        </select>
                    </x-ui.form-field>

                    <x-ui.form-field label="Catatan Evaluasi / Syarat:" name="catatan_kesiapan">
                        <textarea name="catatan_kesiapan" id="catatan_kesiapan" rows="4" class="form-textarea" placeholder="Contoh: Seluruh nilai semester genap tuntas, berkas izin orang tua lengkap.">{{ old('catatan_kesiapan', $siswa->catatan_kesiapan) }}</textarea>
                    </x-ui.form-field>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Simpan Keputusan Kesiapan
                    </button>
                </form>
            @else
                <div style="padding: 1rem; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-md);">
                    <div style="font-size: 0.875rem; color: var(--text-muted);">Status Kesiapan Saat Ini:</div>
                    <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-main); margin: 0.5rem 0;">
                        {{ strtoupper(str_replace('_', ' ', $siswa->status_kesiapan)) }}
                    </div>
                    <p style="font-size: 0.875rem; color: var(--text-body);">
                        {{ $siswa->catatan_kesiapan ?: 'Belum ada catatan evaluasi dari Kepala Jurusan.' }}
                    </p>
                    <div style="margin-top: 1rem; font-size: 0.75rem; color: var(--text-muted);">
                        * Hak verifikasi kesiapan berada di bawah wewenang Kepala Jurusan {{ $siswa->jurusan_id }}.
                    </div>
                </div>
            @endcan
        </x-ui.card>
    </div>
@endsection
