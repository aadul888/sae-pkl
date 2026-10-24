@extends('layouts.app', ['title' => 'Perbarui Kata Sandi'])

@section('content')
    <div style="max-width: 500px; margin: 0 auto;">
        <div style="margin-bottom: 1.5rem;">
            <a href="{{ route('dashboard') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem; font-weight: 600;">
                &larr; Kembali ke Dashboard
            </a>
            <h1 class="page-title" style="margin-top: 0.5rem; font-size: 1.5rem;">
                Perbarui Kata Sandi
            </h1>
            <p class="page-subtitle">
                Ganti kata sandi bawaan dengan kata sandi yang kuat dan aman.
            </p>
        </div>

        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <x-ui.alert type="danger">
                        <ul style="list-style-position: inside; font-size: 0.875rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-ui.alert>
                @endif

                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf

                    <div class="form-group">
                        <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required autocomplete="current-password" placeholder="Masukkan kata sandi saat ini">
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi Baru (Min. 8 Karakter)</label>
                        <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password" placeholder="Masukkan kata sandi baru">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password" placeholder="Ketik ulang kata sandi baru">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Simpan Kata Sandi Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
