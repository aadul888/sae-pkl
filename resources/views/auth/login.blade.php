@extends('layouts.guest', ['title' => 'Masuk ke Sistem'])

@section('content')
    <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-main);">Masuk</h2>
    <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1.5rem;">Gunakan username dan kata sandi Anda.</p>

    @if ($errors->any())
        <x-ui.alert type="danger">
            <ul style="list-style-position: inside;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-ui.alert>
    @endif

    @if (session('info'))
        <x-ui.alert type="info">
            {{ session('info') }}
        </x-ui.alert>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control" value="{{ old('username') }}" required autofocus autocomplete="username" placeholder="Masukkan username">
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password" placeholder="Masukkan password">
        </div>

        <div class="form-group form-check" style="justify-content: space-between;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                <span>Ingat saya</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Masuk ke Sistem</button>
    </form>
@endsection
