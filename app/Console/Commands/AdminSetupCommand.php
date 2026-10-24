<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminSetupCommand extends Command
{
    protected $signature = 'sae:admin-setup 
                            {--username= : Username administrator} 
                            {--nama= : Nama lengkap administrator} 
                            {--email= : Alamat email administrator} 
                            {--password= : Kata sandi administrator}';

    protected $description = 'Menyiapkan atau memperbarui akun Administrator SAE PKL secara aman';

    public function handle(): int
    {
        $this->info('=== Setup Akun Administrator SAE PKL ===');

        $username = $this->option('username') ?: $this->ask('Masukkan username admin', 'admin');
        $nama = $this->option('nama') ?: $this->ask('Masukkan nama lengkap admin', 'Administrator PKL');
        $email = $this->option('email') ?: $this->ask('Masukkan email admin (opsional)', 'admin@sekolah.id');

        $password = $this->option('password');
        if (empty($password)) {
            $password = $this->secret('Masukkan kata sandi admin (minimal 8 karakter)');
        }

        $validator = Validator::make([
            'username' => $username,
            'nama' => $nama,
            'email' => $email,
            'password' => $password,
        ], [
            'username' => ['required', 'string', 'max:100'],
            'nama' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'password.min' => 'Kata sandi minimal harus 8 karakter demi keamanan.',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $err) {
                $this->error($err);
            }
            return Command::FAILURE;
        }

        $user = User::firstOrNew(['username' => $username]);
        $isNew = !$user->exists;

        $user->nama = $nama;
        $user->email = $email ?: null;
        $user->password = Hash::make($password);
        $user->role = 'admin';
        $user->is_active = true;
        $user->save();

        AuditLog::record(
            $isNew ? 'admin.created' : 'admin.updated',
            $user,
            null,
            ['username' => $username, 'nama' => $nama, 'email' => $email]
        );

        $actionText = $isNew ? 'berhasil dibuat' : 'berhasil diperbarui';
        $this->info("Akun administrator [{$username}] {$actionText}!");

        return Command::SUCCESS;
    }
}
