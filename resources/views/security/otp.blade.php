<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="w-100 max-w-md px-4">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="card-title text-center mb-4">Vérification OTP</h3>
                    <form action="{{ route('verifyOtp') }}" method="POST">
                        @csrf
                        <!-- Champ pour entrer le code OTP -->
                        <div class="mb-3">
                            <label for="otp" class="form-label">Entrez le code OTP</label>
                            <input type="text" name="otp" class="form-control" id="otp" required>
                        </div>
                        <!-- Bouton de soumission -->
                        <button type="submit" class="btn btn-primary w-100">Vérifier le code</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
