<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Kredensial bawaan hanya diperbolehkan pada lingkungan local / testing
        $initialPassword = env('INITIAL_ADMIN_PASSWORD');
        if (empty($initialPassword)) {
            $initialPassword = app()->environment('production') 
                ? \Illuminate\Support\Str::random(16) 
                : 'admin123';
        }

        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Administrator PKL',
                'email' => 'admin@sekolah.id',
                'password' => Hash::make($initialPassword),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        Setting::set('app_name', 'SAE PKL (Sistem Aplikasi Edukasi — Praktik Kerja Lapangan)', 'string', 'general');
        Setting::set('institution_type', 'SMK', 'string', 'general');
        Setting::set('integration_mode', 'database', 'string', 'integration');

        AuditLog::create([
            'user_id' => $admin->id,
            'role' => 'admin',
            'action' => 'system.initialized',
            'auditable_type' => User::class,
            'auditable_id' => (string) $admin->id,
            'new_values' => ['message' => 'Sistem dasar SAE PKL Fase 1 berhasil diinisialisasi'],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Seeder/CLI',
            'created_at' => now(),
        ]);
    }
}
