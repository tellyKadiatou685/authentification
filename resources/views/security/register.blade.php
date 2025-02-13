<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            min-height: 100vh;
        }
        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #e0e0e0;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
            border-color: #007bff;
        }
        .btn {
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .nav-tabs {
            border: none;
            margin-bottom: 30px;
        }
        .nav-tabs .nav-link {
            border-radius: 12px;
            padding: 12px 24px;
            margin-right: 10px;
            font-weight: 500;
            color: #666;
            border: none;
            transition: all 0.3s ease;
        }
        .nav-tabs .nav-link.active {
            background-color: #007bff;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
        }
        .card-body {
            background-color: white;
            padding: 40px;
        }
        .form-label {
            font-weight: 500;
            color: #444;
            margin-bottom: 8px;
        }
        .input-group-text {
            background-color: transparent;
            border-right: none;
            border-radius: 12px 0 0 12px;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }
        .bi {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="authTabs">
                                <li class="nav-item">
                                    <a class="nav-link active" id="login-tab" href="#" onclick="showForm('login')">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Connexion
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="register-tab" href="#" onclick="showForm('register')">
                                        <i class="bi bi-person-plus me-2"></i>Inscription
                                    </a>
                                </li>
                            </ul>

                            <!-- Formulaire de connexion -->
                            <div id="login-form">
                                <h3 class="text-center mb-4">Connexion</h3>
                                @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                                @endif
                                <form action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" required>
                                        </div>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Mot de passe</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" required>
                                        </div>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                        @error('g-recaptcha-response')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                                    </button>
                                </form>
                            </div>

                            <!-- Formulaire d'Inscription -->
                            <div id="register-form" style="display: none;">
                                <h3 class="text-center mb-4">Inscription</h3>
                                <form action="{{ route('register') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="name" class="form-label">Nom</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                                            <input type="text" name="name" class="form-control" id="name" required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="phone" class="form-label">Numéro de téléphone</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                            <input type="text" name="phone" class="form-control" id="phone" required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="register-email" class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                            <input type="email" name="email" class="form-control" id="register-email" required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="register-password" class="form-label">Mot de passe</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                            <input type="password" name="password" class="form-control" id="register-password" required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-person-plus me-2"></i>S'inscrire
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showForm(form) {
            document.getElementById('login-form').style.display = form === 'login' ? 'block' : 'none';
            document.getElementById('register-form').style.display = form === 'register' ? 'block' : 'none';
            document.getElementById('login-tab').classList.toggle('active', form === 'login');
            document.getElementById('register-tab').classList.toggle('active', form === 'register');
        }
    </script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>