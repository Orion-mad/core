<? 
//include ('config.php');
//unset($csrf_token);
//session_start();unset($_SESSION);session_destroy();exit;
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if (APP_ENV === 'development'): ?>
    <!-- Meta tags para evitar caché en desarrollo -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <?php endif; ?>
    <title>Iniciar Sesión - <?= APP_NAME ?></title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 2rem 2rem;
            text-align: center;
            position: relative;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 20px;
            background: white;
            border-radius: 20px 20px 0 0;
        }
        
        .system-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.5rem;
            backdrop-filter: blur(10px);
        }
        
        .login-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            opacity: 0.9;
            font-size: 0.95rem;
            margin: 0;
        }
        
        .login-body {
            padding: 2rem;
        }
        
        .form-floating {
            margin-bottom: 1.5rem;
        }
        
        .form-floating .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            height: 58px;
            padding: 1rem 0.75rem 0.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-floating .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .form-floating label {
            padding: 1rem 0.75rem;
            color: #6c757d;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            color: white;
        }
        
        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 6px 15px rgba(102, 126, 234, 0.3);
        }
        
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ffa500 100%);
            color: white;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #ffa500 0%, #ffcd02 100%);
            color: white;
        }
        
        .system-info {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 0.875rem;
        }
        
        .version-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
        }
        
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            z-index: 1000;
        }
        
        .spinner-border {
            color: #667eea;
        }
        
        /* Responsive adjustments */
        @media (max-width: 576px) {
            .login-card {
                margin: 10px;
                border-radius: 15px;
            }
            
            .login-header {
                padding: 2rem 1.5rem 1.5rem;
            }
            
            .login-body {
                padding: 1.5rem;
            }
        }
        
        /* Animation for form elements */
        .form-floating {
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }
        
        .form-floating:nth-child(1) { animation-delay: 0.1s; }
        .form-floating:nth-child(2) { animation-delay: 0.2s; }
        .btn-login { animation: fadeInUp 0.6s ease-out 0.3s both; }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="loading-overlay" id="loadingOverlay">
                <div class="d-flex flex-column align-items-center">
                    <div class="spinner-border" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-3 mb-0 text-muted">Iniciando sesión...</p>
                </div>
            </div>
            
            <div class="login-header">
                <div class="system-logo p-2">
                    <img class="img-fluid" src="../assets/images/logo.ico" alt="Orion suite">
                </div>
                <h1 class="login-title"><?= APP_NAME ?></h1>
                <p class="login-subtitle">Ingrese sus credenciales para acceder al sistema</p>
            </div>
            
            <div class="login-body">
                <!-- Messages -->
                <?php if (!empty($timeout_message)): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-clock-history me-2"></i>
                        <strong>Sesión expirada:</strong> <?= htmlspecialchars($timeout_message) ?>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Error:</strong> <?= htmlspecialchars($error_message) ?>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" action="index.php?action=login" id="loginForm">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="form-floating">
                        <input type="text" class="form-control" id="username" name="username" 
                               placeholder="Usuario o Email" required autocomplete="username"
                               value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                        <label for="username">
                            <i class="bi bi-person me-2"></i>Usuario o Email
                        </label>
                    </div>
                    
                    <div class="form-floating position-relative">
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Contraseña" required autocomplete="current-password" style="padding-right: 3rem;">
                        <label for="password">
                            <i class="bi bi-lock me-2"></i>Contraseña
                        </label>
                        <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y pe-3"
                                id="togglePassword" style="z-index: 10; text-decoration: none; color: #6c757d;">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-login">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Iniciar Sesión
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="index.php?action=forgot_password" class="text-decoration-none" style="color: #667eea; font-weight: 500;">
                        <i class="bi bi-question-circle me-1"></i>
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
                
                <div class="system-info">
                    <div class="version-info">
                        <span>
                            <i class="bi bi-shield-check me-1"></i>
                            Seguro
                        </span>
                        <span>
                            <i class="bi bi-code-square me-1"></i>
                            v<?= APP_VERSION ?>
                        </span>
                        <span>
                            <i class="bi bi-globe me-1"></i>
                            <?= date('Y') ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
       <script>
        // JavaScript simplificado y corregido para el login
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const loginText = document.getElementById('loginText');
            const loginLoader = document.getElementById('loginLoader');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const togglePasswordIcon = document.getElementById('togglePasswordIcon');

            // Variables de control
            let isSubmitting = false;

            // Toggle mostrar/ocultar contraseña
            if (togglePassword && passwordInput && togglePasswordIcon) {
                togglePassword.addEventListener('click', function(e) {
                    e.preventDefault();
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    // Cambiar icono
                    if (type === 'password') {
                        togglePasswordIcon.classList.remove('bi-eye-slash');
                        togglePasswordIcon.classList.add('bi-eye');
                    } else {
                        togglePasswordIcon.classList.remove('bi-eye');
                        togglePasswordIcon.classList.add('bi-eye-slash');
                    }
                });
            }
            
            // Enfocar automáticamente el campo de usuario
            if (usernameInput && !usernameInput.value) {
                usernameInput.focus();
            }
            
            // Manejar envío del formulario
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // Prevenir múltiples envíos
                    if (isSubmitting) {
                        e.preventDefault();
                        return false;
                    }
                    
                    // Validar campos básicos
                    const username = usernameInput.value.trim();
                    const password = passwordInput.value;
                    
                    if (!username || !password) {
                        e.preventDefault();
                        alert('Por favor complete todos los campos');
                        return false;
                    }
                    
                    // Marcar como enviándose
                    isSubmitting = true;
                    
                    // Mostrar estado de carga
                    if (loginText && loginLoader && loginBtn) {
                        loginText.style.display = 'none';
                        loginLoader.style.display = 'inline-block';
                        loginBtn.disabled = true;
                    }
                    
                    // Debug: Verificar que los datos se envían
                    console.log('Enviando formulario de login...');
                    console.log('Username:', username);
                    console.log('Password length:', password.length);
                    console.log('CSRF Token:', document.querySelector('input[name="csrf_token"]').value);
                    
                    // Permitir el envío del formulario
                    return true;
                });
            }
            
            // Manejar tecla Enter en los campos
            [usernameInput, passwordInput].forEach(input => {
                if (input) {
                    input.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            if (input === usernameInput && passwordInput) {
                                passwordInput.focus();
                            } else {
                                loginForm.submit();
                            }
                        }
                    });
                }
            });
            
            // Auto-ocultar mensajes de alerta después de 5 segundos
            const alerts = document.querySelectorAll('.alert-warning, .alert-error');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });
            
            // Reset del formulario si hay error (para permitir reintento)
            if (document.querySelector('.alert-error')) {
                isSubmitting = false;
                if (loginText && loginLoader && loginBtn) {
                    loginText.style.display = 'inline';
                    loginLoader.style.display = 'none';
                    loginBtn.disabled = false;
                }
            }
        });
        
        // Función para debug manual
        function debugLogin() {
            const form = document.getElementById('loginForm');
            const formData = new FormData(form);
            
            console.log('=== DEBUG LOGIN FORM ===');
            console.log('Form method:', form.method);
            console.log('Form action:', form.action);
            console.log('Form data:');
            for (let [key, value] of formData.entries()) {
                if (key === 'password') {
                    console.log(key + ':', '[HIDDEN - Length: ' + value.length + ']');
                } else {
                    console.log(key + ':', value);
                }
            }
            console.log('========================');
        }
        
        // Hacer disponible la función de debug
        window.debugLogin = debugLogin;
           
           
            // Toast notification function
            function showToast(message, type = 'info') {
                const toastContainer = getOrCreateToastContainer();
                const toastId = 'toast-' + Date.now();
                
                const toastHtml = `
                    <div class="toast align-items-center text-bg-${type === 'error' ? 'danger' : type} border-0" role="alert" id="${toastId}">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="bi bi-${type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                                ${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `;
                
                toastContainer.insertAdjacentHTML('beforeend', toastHtml);
                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement);
                toast.show();
                
                // Remove toast element after it's hidden
                toastElement.addEventListener('hidden.bs.toast', function() {
                    toastElement.remove();
                });
            }
            
            function getOrCreateToastContainer() {
                let container = document.querySelector('.toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.className = 'toast-container position-fixed top-0 end-0 p-3';
                    container.style.zIndex = '1060';
                    document.body.appendChild(container);
                }
                return container;
            }
            
            // Particle animation (optional eye candy)
            createParticles();
            
            function createParticles() {
                const particleCount = 20;
                const particles = [];
                
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.style.cssText = `
                        position: fixed;
                        width: 4px;
                        height: 4px;
                        background: rgba(255, 255, 255, 0.6);
                        border-radius: 50%;
                        pointer-events: none;
                        z-index: -1;
                    `;
                    
                    document.body.appendChild(particle);
                    particles.push({
                        element: particle,
                        x: Math.random() * window.innerWidth,
                        y: Math.random() * window.innerHeight,
                        speedX: (Math.random() - 0.5) * 2,
                        speedY: (Math.random() - 0.5) * 2
                    });
                }
                
                function animateParticles() {
                    particles.forEach(particle => {
                        particle.x += particle.speedX;
                        particle.y += particle.speedY;
                        
                        if (particle.x > window.innerWidth) particle.x = 0;
                        if (particle.x < 0) particle.x = window.innerWidth;
                        if (particle.y > window.innerHeight) particle.y = 0;
                        if (particle.y < 0) particle.y = window.innerHeight;
                        
                        particle.element.style.left = particle.x + 'px';
                        particle.element.style.top = particle.y + 'px';
                    });
                    
                    requestAnimationFrame(animateParticles);
                }
                
                animateParticles();
            }
        });
           
           
           

    </script>
</body>
</html>