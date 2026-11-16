<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Portal - Sistem Pembayaran Pensiunan PT Jasa Marga</title>

    {{-- Typography --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5.3 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --jm-navy:        #0B192C;
            --jm-navy-dark:   #060F1A;
            --jm-slate:       #1E293B;
            --jm-blue:        #0284C7;
            --jm-blue-dark:   #0369A1;
            --jm-gold:        #D97706;
            --jm-bg:          #F8FAFC;
            --jm-text-main:   #0F172A;
            --jm-text-muted:  #64748B;
            --jm-border:      #E2E8F0;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            min-height: 100vh;
            background-color: var(--jm-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .auth-container {
            width: 100%;
            max-width: 980px;
            background: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            border: 1px solid var(--jm-border);
            overflow: hidden;
            display: flex;
            min-height: 560px;
        }

        /* ── Left Corporate Panel ── */
        .auth-panel-brand {
            flex: 1.1;
            background: linear-gradient(145deg, var(--jm-navy) 0%, #152A45 100%);
            color: #FFFFFF;
            padding: 3.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .brand-emblem-large {
            width: 52px;
            height: 52px;
            background: #1E3E62;
            border: 2px solid var(--jm-gold);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.5px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            margin-bottom: 1.5rem;
        }

        .brand-headline {
            font-size: 1.65rem;
            font-weight: 800;
            line-height: 1.25;
            color: #FFFFFF;
            letter-spacing: -0.02em;
            margin-bottom: 0.75rem;
        }

        .brand-tagline {
            font-size: 0.9rem;
            color: #94A3B8;
            line-height: 1.5;
            margin-bottom: 2rem;
        }

        .pillar-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .pillar-item {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
        }

        .pillar-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(2, 132, 199, 0.15);
            border: 1px solid rgba(2, 132, 199, 0.3);
            color: #38BDF8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .pillar-text-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #FFFFFF;
        }

        .pillar-text-desc {
            font-size: 0.75rem;
            color: #94A3B8;
            line-height: 1.35;
        }

        .auth-panel-footer {
            margin-top: 2rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.72rem;
            color: #64748B;
        }

        /* ── Right Form Panel ── */
        .auth-panel-form {
            flex: 1;
            padding: 3.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #FFFFFF;
        }

        .form-header-title {
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--jm-text-main);
            letter-spacing: -0.02em;
            margin-bottom: 0.25rem;
        }

        .form-header-sub {
            font-size: 0.85rem;
            color: var(--jm-text-muted);
            margin-bottom: 2rem;
        }

        .form-label-custom {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--jm-text-main);
            margin-bottom: 0.35rem;
        }

        .form-control-custom {
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            padding: 0.65rem 0.9rem;
            font-size: 0.9rem;
            color: var(--jm-text-main);
            background-color: #FFFFFF;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            border-color: var(--jm-blue);
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.12);
            outline: none;
        }

        .input-group-custom .input-group-text {
            background-color: #F8FAFC;
            border: 1px solid #CBD5E1;
            border-right: none;
            color: #64748B;
            border-radius: 8px 0 0 8px;
            padding-left: 0.85rem;
            padding-right: 0.85rem;
        }

        .input-group-custom .form-control-custom {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }

        .input-group-custom:focus-within .input-group-text {
            border-color: var(--jm-blue);
            color: var(--jm-blue);
        }

        .btn-toggle-pwd {
            border: 1px solid #CBD5E1;
            border-left: none;
            background-color: #FFFFFF;
            color: #64748B;
            border-radius: 0 8px 8px 0;
            padding: 0 0.85rem;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-toggle-pwd:hover {
            color: var(--jm-text-main);
            background-color: #F8FAFC;
        }

        .btn-submit-corporate {
            background-color: var(--jm-navy);
            border: 1px solid var(--jm-navy);
            color: #FFFFFF;
            font-weight: 600;
            font-size: 0.925rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            width: 100%;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .btn-submit-corporate:hover {
            background-color: #152A45;
            border-color: #152A45;
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(11, 25, 44, 0.2);
        }

        .alert-corporate-danger {
            background-color: #FEF2F2;
            border: 1px solid #FECACA;
            color: #991B1B;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
        }

        @media (max-width: 860px) {
            .auth-container {
                flex-direction: column;
                max-width: 480px;
            }
            .auth-panel-brand {
                padding: 2.5rem 2rem;
            }
            .auth-panel-form {
                padding: 2.5rem 2rem;
            }
        }
    </style>
</head>
<body>

<div class="auth-container">
    {{-- Left Brand Panel --}}
    <div class="auth-panel-brand">
        <div>
            <div class="brand-emblem-large" aria-hidden="true">JM</div>
            <h1 class="brand-headline">Sistem Pembayaran Pensiunan</h1>
            <p class="brand-tagline">
                Portal terpadu pengelolaan dan penyaluran dana manfaat pensiunan PT Jasa Marga (Persero) Tbk.
            </p>

            <div class="pillar-list">
                <div class="pillar-item">
                    <div class="pillar-icon" aria-hidden="true"><i class="bi bi-shield-check"></i></div>
                    <div>
                        <div class="pillar-text-title">Keamanan Transaksi Perbankan</div>
                        <div class="pillar-text-desc">Validasi berlapis untuk akurasi penyaluran dana.</div>
                    </div>
                </div>

                <div class="pillar-item">
                    <div class="pillar-icon" aria-hidden="true"><i class="bi bi-diagram-3"></i></div>
                    <div>
                        <div class="pillar-text-title">Integrasi Multi-Peran</div>
                        <div class="pillar-text-desc">Otorisasi terstruktur untuk HRD, Keuangan, dan Pensiunan.</div>
                    </div>
                </div>

                <div class="pillar-item">
                    <div class="pillar-icon" aria-hidden="true"><i class="bi bi-receipt-cutoff"></i></div>
                    <div>
                        <div class="pillar-text-title">Transparansi Penyaluran</div>
                        <div class="pillar-text-desc">Monitoring real-time status verifikasi dan pembayaran.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-panel-footer">
            &copy; {{ date('Y') }} PT Jasa Marga (Persero) Tbk. Hak cipta dilindungi undang-undang.
        </div>
    </div>

    {{-- Right Login Form Panel --}}
    <div class="auth-panel-form">
        <h2 class="form-header-title">Masuk ke Akun</h2>
        <p class="form-header-sub">Silakan gunakan kredensial resmi perusahaan Anda</p>

        @if(session('error'))
            <div class="alert-corporate-danger d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0" aria-hidden="true"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any() && !$errors->has('email') && !$errors->has('password'))
            <div class="alert-corporate-danger d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0" aria-hidden="true"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" novalidate>
            @csrf

            {{-- Email Input --}}
            <div class="mb-3">
                <label for="email" class="form-label-custom">Alamat Email Terdaftar</label>
                <div class="input-group input-group-custom">
                    <span class="input-group-text" id="email-addon" aria-hidden="true">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control form-control-custom @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="nama@jasamarga.co.id"
                           aria-describedby="email-addon"
                           autocomplete="email"
                           required
                           autofocus>
                </div>
                @error('email')
                    <div class="text-danger small mt-1" role="alert">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password Input --}}
            <div class="mb-3">
                <label for="password" class="form-label-custom">Kata Sandi</label>
                <div class="input-group input-group-custom">
                    <span class="input-group-text" id="pwd-addon" aria-hidden="true">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control form-control-custom @error('password') is-invalid @enderror"
                           placeholder="Masukkan kata sandi"
                           aria-describedby="pwd-addon"
                           autocomplete="current-password"
                           required>
                    <button type="button"
                            class="btn-toggle-pwd"
                            id="togglePassword"
                            aria-label="Tampilkan atau sembunyikan kata sandi">
                        <i class="bi bi-eye" id="toggleIcon" aria-hidden="true"></i>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger small mt-1" role="alert">{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label small text-muted" for="remember">
                        Ingat sesi saya
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-submit-corporate">
                <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
                <span>Masuk ke Dashboard</span>
            </button>
        </form>

        <div class="mt-4 pt-3 border-top text-center">
            <p class="small text-muted mb-0" style="font-size: 0.78rem;">
                Bantuan teknis dan kendala akun dapat dilaporkan ke Tim TI &amp; SDM PT Jasa Marga.
            </p>
        </div>
    </div>
</div>

<script>
    // Password visibility toggle (Accessible R-39)
    const toggleBtn = document.getElementById('togglePassword');
    const pwdInput  = document.getElementById('password');
    const pwdIcon   = document.getElementById('toggleIcon');

    if (toggleBtn && pwdInput) {
        toggleBtn.addEventListener('click', function() {
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                pwdIcon.className = 'bi bi-eye-slash';
                toggleBtn.setAttribute('aria-label', 'Sembunyikan kata sandi');
            } else {
                pwdInput.type = 'password';
                pwdIcon.className = 'bi bi-eye';
                toggleBtn.setAttribute('aria-label', 'Tampilkan kata sandi');
            }
        });
    }
</script>
</body>
</html>
