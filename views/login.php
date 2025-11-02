<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        .login-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            display: block;
            fill: var(--color-primary);
        }
    </style>
</head>
<body>
    <div class="login-layout">
        <div class="login-card fade-in">
            <div class="login-header">
                <!-- Icono del sistema -->
                <img alt="Orion CORE" src="../assets/images/orion.png">
                
                <h1 class="login-title"><?= APP_NAME ?></h1>
                <p class="login-subtitle">Ingrese sus credenciales para acceder al sistema</p>
            </div>

            <!-- Mensajes de error o timeout -->
            <?php if (!empty($timeout_message)): ?>
                <div class="alert alert-warning">
                    <strong>Sesión expirada:</strong> <?= htmlspecialchars($timeout_message) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-error">
                    <strong>Error:</strong> <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de login -->
            <form method="POST" action="index.php?action=login" id="loginForm">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <div class="form-group">
                    <label for="username" class="form-label">Usuario o Email</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="username" 
                        name="username" 
                        required 
                        autocomplete="username"
                        placeholder="Ingrese su usuario o email"
                        value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        placeholder="Ingrese su contraseña"
                    >
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block" id="loginBtn">
                        <span id="loginText">Iniciar Sesión</span>
                        <div class="loading" id="loginLoader" style="display: none;"></div>
                    </button>
                </div>
            </form>

            <div class="text-center mt-3">
                <small class="text-secondary">
                    <?= APP_NAME ?> v<?= APP_VERSION ?>
                </small>
            </div>
        </div>
    </div>

    <script>
        // JavaScript para mejorar la experiencia de usuario
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const loginText = document.getElementById('loginText');
            const loginLoader = document.getElementById('loginLoader');
            const usernameInput = document.getElementById('username');
            
            // Enfocar el campo de usuario al cargar la página
            usernameInput.focus();
            
            // Manejar envío del formulario
            loginForm.addEventListener('submit', function(e) {
                // Mostrar indicador de carga
                loginBtn.disabled = true;
                loginText.style.display = 'none';
                loginLoader.style.display = 'inline-block';
            });
            
            // Validación en tiempo real
            const inputs = loginForm.querySelectorAll('input[required]');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    validateForm();
                });
            });
            
            function validateForm() {
                let isValid = true;
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                    }
                });
                
                loginBtn.disabled = !isValid;
            }
            
            // Validación inicial
            validateForm();
            
            // Animación de entrada para mensajes de error
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.classList.add('fade-in');
            });
            
            // Auto-ocultar mensajes después de 5 segundos
            setTimeout(() => {
                alerts.forEach(alert => {
                    if (alert.classList.contains('alert-warning')) {
                        alert.style.opacity = '0';
                        alert.style.transition = 'opacity 0.5s ease';
                        setTimeout(() => {
                            alert.style.display = 'none';
                        }, 500);
                    }
                });
            }, 5000);
            
            // Manejar Enter en campos de formulario
            inputs.forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const nextInput = getNextInput(input);
                        if (nextInput) {
                            nextInput.focus();
                        } else {
                            loginForm.submit();
                        }
                    }
                });
            });
            
            function getNextInput(currentInput) {
                const inputArray = Array.from(inputs);
                const currentIndex = inputArray.indexOf(currentInput);
                return inputArray[currentIndex + 1] || null;
            }
        });
        
        // Prevenir envíos múltiples
        let formSubmitted = false;
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            if (formSubmitted) {
                e.preventDefault();
                return false;
            }
            formSubmitted = true;
        });
    </script>
</body>
</html>