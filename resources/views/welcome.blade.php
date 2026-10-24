<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1e40af">
    <meta name="description" content="Platform digital untuk membantu sekolah, siswa, guru pembimbing, dan mitra industri mengelola kegiatan Praktik Kerja Lapangan secara terstruktur.">
    <title>{{ config('app.name', 'SAE PKL') }} — Kelola Praktik Kerja Lapangan Lebih Mudah dan Terintegrasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <a href="#main" class="skip-link">Lewati ke konten utama</a>

    <!-- Navbar -->
    <header class="site-header">
        <div class="container">
            <nav class="navbar" aria-label="Navigasi Utama">
                <a href="{{ route('home') }}" class="navbar-brand">
                    <div class="brand-logo" aria-hidden="true">SAE</div>
                    <div class="brand-text">
                        <span class="brand-name">SAE PKL</span>
                        <span class="brand-tag">Praktik Kerja Lapangan</span>
                    </div>
                </a>

                <button type="button" class="navbar-toggle" id="navbarToggle" aria-expanded="false" aria-controls="navbarMenu" aria-label="Buka menu navigasi">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <ul class="nav-menu" id="navbarMenu">
                    <li><a href="#beranda" class="nav-link">Beranda</a></li>
                    <li><a href="#tentang" class="nav-link">Tentang SAE PKL</a></li>
                    <li><a href="#alur" class="nav-link">Alur PKL</a></li>
                    <li><a href="#fitur" class="nav-link">Fitur</a></li>
                    <li class="nav-cta">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary nav-btn">Buka Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary nav-btn">Login</a>
                        @endauth
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main id="main">
        <!-- Hero Section -->
        <section class="hero" id="beranda">
            <div class="container">
                <div class="hero-grid">
                    <div class="hero-content">
                        <div class="hero-pill">
                            <span class="hero-pill-dot" aria-hidden="true"></span>
                            <span>Ekosistem Sistem Aplikasi Edukasi (SAE)</span>
                        </div>
                        <h1 class="hero-title">Kelola Praktik Kerja Lapangan Lebih Mudah dan Terintegrasi</h1>
                        <p class="hero-desc">Platform digital untuk membantu sekolah, siswa, guru pembimbing, dan mitra industri mengelola kegiatan Praktik Kerja Lapangan secara terstruktur.</p>
                        
                        <div class="hero-actions">
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Buka Dashboard Sistem</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Login ke Sistem</a>
                            @endauth
                            <a href="#alur" class="btn btn-secondary btn-lg">Pelajari Alur PKL</a>
                        </div>

                        <div class="hero-notice">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                            <span>Dukungan 7 peran: Admin, Kepsek, Hubin, Kajur, Guru, Industri &amp; Siswa</span>
                        </div>
                    </div>

                    <div class="hero-visual" aria-hidden="true">
                        <div class="hero-svg-card">
                            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.75rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 10px; height: 10px; border-radius: 50%; background: #ef4444;"></div>
                                    <div style="width: 10px; height: 10px; border-radius: 50%; background: #f59e0b;"></div>
                                    <div style="width: 10px; height: 10px; border-radius: 50%; background: #10b981;"></div>
                                </div>
                                <span style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted); background: var(--bg-alt); padding: 0.2rem 0.5rem; border-radius: 4px;">Sistem SAE PKL</span>
                            </div>

                            <svg viewBox="0 0 400 240" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: auto;">
                                <!-- Background grid lines -->
                                <rect width="400" height="240" rx="8" fill="#F8FAFC"/>
                                <line x1="20" y1="60" x2="380" y2="60" stroke="#E2E8F0" stroke-dasharray="4 4"/>
                                <line x1="20" y1="120" x2="380" y2="120" stroke="#E2E8F0" stroke-dasharray="4 4"/>
                                <line x1="20" y1="180" x2="380" y2="180" stroke="#E2E8F0" stroke-dasharray="4 4"/>
                                
                                <!-- Diagram Elements: Sekolah -> SAE PKL Hub -> DU/DI -->
                                <!-- Node Sekolah -->
                                <rect x="25" y="85" width="90" height="70" rx="8" fill="#EFF6FF" stroke="#3B82F6" stroke-width="2"/>
                                <circle cx="70" cy="110" r="14" fill="#3B82F6"/>
                                <path d="M70 102 L78 108 L62 108 Z" fill="#FFFFFF"/>
                                <rect x="66" y="108" width="8" height="8" fill="#FFFFFF"/>
                                <text x="70" y="142" font-size="10" font-weight="700" fill="#1E40AF" text-anchor="middle">SEKOLAH</text>

                                <!-- Central Hub -->
                                <rect x="145" y="65" width="110" height="110" rx="12" fill="#1E40AF" stroke="#1D4ED8" stroke-width="2"/>
                                <rect x="160" y="80" width="80" height="42" rx="4" fill="#2563EB"/>
                                <path d="M168 102 L178 92 L190 105 L204 90 L220 108" stroke="#60A5FA" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="204" cy="90" r="3" fill="#93C5FD"/>
                                <text x="200" y="142" font-size="11" font-weight="800" fill="#FFFFFF" text-anchor="middle">SAE PKL</text>
                                <text x="200" y="158" font-size="8" font-weight="500" fill="#93C5FD" text-anchor="middle">Pusat Integrasi</text>

                                <!-- Node Industri -->
                                <rect x="285" y="85" width="90" height="70" rx="8" fill="#F0FDF4" stroke="#10B981" stroke-width="2"/>
                                <circle cx="330" cy="110" r="14" fill="#10B981"/>
                                <path d="M324 116 L324 105 L330 109 L330 105 L336 109 L336 116 Z" fill="#FFFFFF"/>
                                <text x="330" y="142" font-size="10" font-weight="700" fill="#166534" text-anchor="middle">DUNIA USAHA</text>

                                <!-- Connecting Arrows -->
                                <path d="M115 120 L145 120" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-dasharray="3 3"/>
                                <path d="M255 120 L285 120" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-dasharray="3 3"/>
                                
                                <!-- Top status badge -->
                                <rect x="135" y="15" width="130" height="24" rx="12" fill="#DBEAFE"/>
                                <text x="200" y="31" font-size="9" font-weight="600" fill="#1E40AF" text-anchor="middle">Sinkronisasi Data Otomatis</text>
                                
                                <!-- Bottom status badge -->
                                <rect x="120" y="200" width="160" height="24" rx="12" fill="#DCFCE7"/>
                                <text x="200" y="216" font-size="9" font-weight="600" fill="#166534" text-anchor="middle">Monitoring Terstruktur</text>
                            </svg>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; font-size: 0.75rem;">
                                <div style="background: var(--bg-alt); padding: 0.5rem 0.75rem; border-radius: 6px; border: 1px solid var(--border);">
                                    <span style="color: var(--text-muted); display: block;">Peran Pengguna</span>
                                    <strong style="color: var(--text-dark);">7 Hak Akses</strong>
                                </div>
                                <div style="background: var(--bg-alt); padding: 0.5rem 0.75rem; border-radius: 6px; border: 1px solid var(--border);">
                                    <span style="color: var(--text-muted); display: block;">Arsitektur</span>
                                    <strong style="color: var(--text-dark);">Keamanan RBAC</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Tentang Aplikasi -->
        <section class="section section-alt" id="tentang">
            <div class="container">
                <div class="section-header">
                    <span class="section-eyebrow">Pondasi Sistem</span>
                    <h2 class="section-title">Tentang SAE PKL</h2>
                    <p class="section-subtitle">Sistem dirancang untuk membantu sekolah, siswa, guru pembimbing, dan pembimbing industri mengelola administrasi Praktik Kerja Lapangan secara terpadu, transparan, dan efisien.</p>
                </div>

                <div class="value-grid">
                    <div class="value-card">
                        <div class="value-icon-box">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 class="value-title">Koordinasi Multiperan</h3>
                        <p class="value-desc">Menghubungkan 7 pemangku kepentingan (Kepala Sekolah, Waka Hubin, Kajur, Guru Pembimbing, Pembimbing Industri, Siswa, dan Admin) dalam satu sistem koordinasi dengan batasan akses sesuai tanggung jawab.</p>
                    </div>

                    <div class="value-card">
                        <div class="value-icon-box">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <h3 class="value-title">Administrasi Terstruktur</h3>
                        <p class="value-desc">Mendukung pengelolaan administrasi PKL secara rapi mulai dari persiapan periode, verifikasi kesiapan, pemetaan kuota industri, hingga dokumentasi penilaian tanpa formulir fisik yang tercecer.</p>
                    </div>

                    <div class="value-card">
                        <div class="value-icon-box">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <h3 class="value-title">Integrasi Data Pokok SAE</h3>
                        <p class="value-desc">Terhubung langsung dengan ekosistem data pokok sekolah pada Sistem Aplikasi Edukasi (SAE), memastikan data siswa, guru, dan kompetensi keahlian selalu valid tanpa pekerjaan ganda.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Alur PKL -->
        <section class="section" id="alur">
            <div class="container">
                <div class="section-header">
                    <span class="section-eyebrow">Alur Kerja</span>
                    <h2 class="section-title">Alur Pelaksanaan PKL</h2>
                    <p class="section-subtitle">Gambaran tahapan umum kegiatan Praktik Kerja Lapangan dari persiapan awal hingga evaluasi dan penuntasan.</p>
                </div>

                <div class="steps-container">
                    <div class="step-card">
                        <div class="step-badge" aria-hidden="true">1</div>
                        <div class="step-content">
                            <h3 class="step-title">Persiapan dan Pendataan</h3>
                            <p class="step-desc">Sinkronisasi data master sekolah dari SAE, pembaharuan referensi siswa, penugasan guru pembimbing, dan penetapan gelombang periode PKL oleh pihak sekolah.</p>
                        </div>
                    </div>

                    <div class="step-card">
                        <div class="step-badge" aria-hidden="true">2</div>
                        <div class="step-content">
                            <h3 class="step-title">Pengajuan atau Penempatan Siswa</h3>
                            <p class="step-desc">Verifikasi kesiapan siswa oleh Kepala Jurusan (Kajur), koordinasi kuota mitra industri oleh Waka Hubin, serta pemetaan siswa ke lokasi Praktik Kerja Lapangan.</p>
                        </div>
                    </div>

                    <div class="step-card">
                        <div class="step-badge" aria-hidden="true">3</div>
                        <div class="step-content">
                            <h3 class="step-title">Pelaksanaan PKL</h3>
                            <p class="step-desc">Siswa menjalani kegiatan praktik kerja lapangan di lingkungan kerja mitra industri sesuai jadwal yang telah ditentukan dengan bimbingan praktisi DU/DI.</p>
                        </div>
                    </div>

                    <div class="step-card">
                        <div class="step-badge" aria-hidden="true">4</div>
                        <div class="step-content">
                            <h3 class="step-title">Monitoring dan Pencatatan Kegiatan</h3>
                            <p class="step-desc">Pencatatan aktivitas berkala dan presensi oleh siswa, didampingi pemantauan dan bimbingan aktif oleh guru pembimbing sekolah dan mentor industri.</p>
                        </div>
                    </div>

                    <div class="step-card">
                        <div class="step-badge" aria-hidden="true">5</div>
                        <div class="step-content">
                            <h3 class="step-title">Penilaian dan Penyelesaian</h3>
                            <p class="step-desc">Evaluasi komprehensif capaian kompetensi oleh pihak industri dan guru pembimbing untuk penyusunan dokumentasi hasil dan penyelesaian administrasi PKL.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Fitur -->
        <section class="section section-alt" id="fitur">
            <div class="container">
                <div class="section-header">
                    <span class="section-eyebrow">Kapabilitas Platform</span>
                    <h2 class="section-title">Fitur SAE PKL</h2>
                    <p class="section-subtitle">Gambaran modul dan kapabilitas sistem yang disiapkan untuk mendukung efisiensi tata kelola kegiatan PKL.</p>
                </div>

                <div class="features-grid">
                    <!-- Fitur 1: Manajemen PKL -->
                    <div class="feature-card">
                        <div>
                            <div class="feature-top">
                                <div class="feature-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </div>
                                <span class="feature-badge active">Modul Inti</span>
                            </div>
                            <h3 class="feature-title">Manajemen PKL</h3>
                            <p class="feature-desc">Pengelolaan kegiatan PKL secara terstruktur, pengelompokan gelombang pelaksanaan, dan pemantauan status administratif secara terpusat.</p>
                        </div>
                    </div>

                    <!-- Fitur 2: Data dan Penempatan -->
                    <div class="feature-card">
                        <div>
                            <div class="feature-top">
                                <div class="feature-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <polyline points="16 11 18 13 22 9"></polyline>
                                    </svg>
                                </div>
                                <span class="feature-badge planned">Direncanakan</span>
                            </div>
                            <h3 class="feature-title">Data dan Penempatan</h3>
                            <p class="feature-desc">Mendukung proses pengelolaan data dan penempatan siswa sesuai modul yang direncanakan pada tahap pengembangan berikutnya.</p>
                        </div>
                    </div>

                    <!-- Fitur 3: Jurnal Kegiatan -->
                    <div class="feature-card">
                        <div>
                            <div class="feature-top">
                                <div class="feature-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                </div>
                                <span class="feature-badge planned">Direncanakan</span>
                            </div>
                            <h3 class="feature-title">Jurnal Kegiatan</h3>
                            <p class="feature-desc">Mendukung pencatatan aktivitas PKL ketika modul tersedia untuk mendokumentasikan pekerjaan harian dan perkembangan keahlian siswa.</p>
                        </div>
                    </div>

                    <!-- Fitur 4: Monitoring Pembimbing -->
                    <div class="feature-card">
                        <div>
                            <div class="feature-top">
                                <div class="feature-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                </div>
                                <span class="feature-badge planned">Direncanakan</span>
                            </div>
                            <h3 class="feature-title">Monitoring Pembimbing</h3>
                            <p class="feature-desc">Mendukung pemantauan kegiatan oleh pembimbing untuk memastikan proses bimbingan, kendala lapangan, dan kunjungan tercatat jelas.</p>
                        </div>
                    </div>

                    <!-- Fitur 5: Absensi PKL -->
                    <div class="feature-card">
                        <div>
                            <div class="feature-top">
                                <div class="feature-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                </div>
                                <span class="feature-badge planned">Direncanakan</span>
                            </div>
                            <h3 class="feature-title">Absensi PKL</h3>
                            <p class="feature-desc">Direncanakan untuk mendukung pencatatan kehadiran siswa di lokasi praktik kerja secara akurat serta rekapitulasi kehadiran.</p>
                        </div>
                    </div>

                    <!-- Fitur 6: Laporan dan Penilaian -->
                    <div class="feature-card">
                        <div>
                            <div class="feature-top">
                                <div class="feature-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                </div>
                                <span class="feature-badge planned">Direncanakan</span>
                            </div>
                            <h3 class="feature-title">Laporan dan Penilaian</h3>
                            <p class="feature-desc">Mendukung dokumentasi hasil PKL, kompilasi nilai pembimbing industri dan guru, serta penerbitan sertifikat digital.</p>
                        </div>
                    </div>
                </div>

                <div class="features-notice">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span><strong>Informasi Pengembangan:</strong> Modul bertanda "Direncanakan" merupakan bagian dari rencana pengembangan bertahap sistem SAE PKL dan disajikan sebagai gambaran fungsi aplikasi mendatang.</span>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2 class="cta-title">Siap Mengelola Praktik Kerja Lapangan?</h2>
                    <p class="cta-desc">Pengguna terdaftar dapat langsung masuk ke sistem menggunakan akun masing-masing untuk mengakses layanan administrasi SAE PKL.</p>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-cta">Buka Dashboard Sistem</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-cta">Login ke Sistem</a>
                    @endauth
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a href="{{ route('home') }}" class="footer-brand">
                        <div class="footer-logo" aria-hidden="true">SAE</div>
                        <span class="footer-brand-title">SAE PKL</span>
                    </a>
                    <p class="footer-desc">Sistem Aplikasi Edukasi — Praktik Kerja Lapangan. Platform terintegrasi untuk pengelolaan administrasi PKL SMK.</p>
                </div>

                <div>
                    <h4 class="footer-col-title">Navigasi</h4>
                    <ul class="footer-links">
                        <li><a href="#beranda" class="footer-link">Beranda</a></li>
                        <li><a href="#tentang" class="footer-link">Tentang SAE PKL</a></li>
                        <li><a href="#alur" class="footer-link">Alur PKL</a></li>
                        <li><a href="#fitur" class="footer-link">Fitur</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-col-title">Hak Akses Peran</h4>
                    <ul class="footer-links" style="font-size: 0.8125rem;">
                        <li>Administrator</li>
                        <li>Kepala Sekolah &amp; Waka Hubin</li>
                        <li>Kepala Jurusan (Kajur)</li>
                        <li>Guru Pembimbing</li>
                        <li>Pembimbing Industri &amp; Siswa</li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-col-title">Akses Sistem</h4>
                    <ul class="footer-links">
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="footer-link">Buka Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="footer-link">Masuk ke Sistem</a></li>
                        @endauth
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                &copy; {{ date('Y') }} SAE PKL &bull; Bagian dari Ekosistem Sistem Aplikasi Edukasi (SAE). Seluruh hak cipta dilindungi.
            </div>
        </div>
    </footer>
</body>
</html>
