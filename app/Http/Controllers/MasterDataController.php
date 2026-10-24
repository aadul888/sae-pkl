<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\RefGuru;
use App\Models\RefJurusan;
use App\Models\RefSekolah;
use App\Models\RefSiswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class MasterDataController extends Controller
{
    public function index(): View
    {
        $stats = [
            'sekolah' => RefSekolah::count(),
            'jurusan' => RefJurusan::count(),
            'guru' => RefGuru::count(),
            'siswa' => RefSiswa::count(),
        ];

        $jurusanList = RefJurusan::withCount('siswa')->get();
        $recentSync = AuditLog::where('action', 'master.sync')->latest('id')->first();

        return view('master.index', compact('stats', 'jurusanList', 'recentSync'));
    }

    public function students(Request $request): View
    {
        $user = Auth::user();

        $query = RefSiswa::with('jurusan');

        // Penegakan Hak Akses di Sisi Server (Server-Side Query Scoping)
        if ($user->isKajur()) {
            // Kajur MUTLAK hanya dapat melihat jurusannya sendiri
            $query->forDepartment($user->jurusan_id);
        } elseif ($request->filled('jurusan_id')) {
            // Admin, Kepsek, Hubin dapat menyaring jurusan tertentu
            $query->forDepartment($request->input('jurusan_id'));
        }

        if ($request->filled('status')) {
            $query->where('status_kesiapan', $request->input('status'));
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('nisn', 'like', "%{$q}%")
                    ->orWhere('nama_rombel', 'like', "%{$q}%");
            });
        }

        $students = $query->paginate(20)->withQueryString();
        $jurusanList = RefJurusan::all();

        return view('master.students', compact('students', 'jurusanList', 'user'));
    }

    public function studentDetail(string $id): View
    {
        $siswa = RefSiswa::findOrFail($id);

        // Otorisasi Policy Server-Side
        Gate::authorize('view', $siswa);

        return view('master.student_detail', compact('siswa'));
    }

    public function verifyReadiness(Request $request, string $id): RedirectResponse
    {
        $siswa = RefSiswa::findOrFail($id);

        // Otorisasi Policy Server-Side
        Gate::authorize('verifyKesiapan', $siswa);

        $validated = $request->validate([
            'status_kesiapan' => ['required', 'in:siap,tidak_siap,belum_verifikasi'],
            'catatan_kesiapan' => ['nullable', 'string', 'max:500'],
        ]);

        $oldStatus = $siswa->status_kesiapan;
        $siswa->update($validated);

        AuditLog::record('student.readiness_verified', $siswa, [
            'status' => $oldStatus,
        ], [
            'status' => $siswa->status_kesiapan,
            'catatan' => $siswa->catatan_kesiapan,
        ]);

        return back()->with('success', "Status kesiapan siswa {$siswa->nama} berhasil diperbarui.");
    }

    public function syncNow(Request $request): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Hanya Administrator yang berwenang memicu sinkronisasi manual.');
        }

        try {
            Artisan::call('sae:sync-master');
            return redirect()->route('master.index')
                ->with('success', 'Sinkronisasi data master dari SAE Utama berhasil dieksekusi.');
        } catch (\Throwable $e) {
            return redirect()->route('master.index')
                ->with('error', 'Sinkronisasi gagal: ' . $e->getMessage());
        }
    }
}
