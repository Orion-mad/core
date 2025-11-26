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
    <title><?= $title ?? 'Dashboard' ?> - <?= APP_NAME ?></title>
    <!-- Bootstrap 5.3 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/orion.css?v=<?= time() ?>">
    <link rel="stylesheet" href="assets/css/main.css?v=<?= time() ?>">
    <link rel="stylesheet" href="assets/css/sliders.css?v=<?= time() ?>">
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="index.php?action=dashboard" class="sidebar-brand">
                <img alt="Orion Suite" src="assets/images/orion.png" class="img-fluid" width="25%">
                <span><?= APP_NAME ?></span>
            </a>
        </div>
        
        <div class="sidebar-nav">
            <?php 
            $auth = Auth::getInstance();
            $current_page = $current_page ?? '';
            ?>
            
            <!-- Dashboard -->
            <div class="nav-item">
                <a href="index.php?action=dashboard" class="nav-link <?= $current_page === 'dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <!-- SliderPAge -->
            <div class="nav-item">
                <a href="index.php?action=sliders" class="nav-link <?= $current_page === 'sliders' ? 'active' : '' ?>">
                    <i class="bi bi-sliders"></i>
                    <span>Ej. Slider</span>
                </a>
            </div>
            
            <!-- Usuarios -->
            <?php if ($auth->hasPermission('usuarios.leer')): ?>
            <div class="nav-item">
                <a href="index.php?action=usuarios" class="nav-link <?= $current_page === 'usuarios' ? 'active' : '' ?>">
                    <i class="bi bi-people"></i>
                    <span>Usuarios</span>
                </a>
            </div>
            <?php endif; ?>
            
            <!-- Roles -->
            <?php if ($auth->hasPermission('roles.leer')): ?>
            <div class="nav-item">
                <a href="index.php?action=roles" class="nav-link <?= $current_page === 'roles' ? 'active' : '' ?>">
                    <i class="bi bi-person-badge"></i>
                    <span>Roles</span>
                </a>
            </div>
            <?php endif; ?>
            
            <!-- Auditoría -->
            <?php if ($auth->hasPermission('auditoria.leer')): ?>
            <div class="nav-item">
                <a href="index.php?action=auditoria" class="nav-link <?= $current_page === 'auditoria' ? 'active' : '' ?>">
                    <i class="bi bi-file-text"></i>
                    <span>Auditoría</span>
                </a>
            </div>
            <?php endif; ?>
            
            <!-- Mi Perfil -->
            <div class="nav-item">
                <a href="index.php?action=perfil" class="nav-link <?= $current_page === 'perfil' ? 'active' : '' ?>">
                    <i class="bi bi-person-circle"></i>
                    <span>Mi Perfil</span>
                </a>
            </div>
            
            <!-- Panel de Administración (Solo Admin) -->
            <?php if ($auth->isAdmin()): ?>
            <div class="nav-item">
                <a href="index.php?action=admin" class="nav-link admin-link <?= $current_page === 'admin' ? 'active' : '' ?>">
                    <i class="bi bi-gear-fill"></i>
                    <span>Panel de Administración</span>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header/Navbar -->
        <nav class="navbar navbar-expand-lg main-header">
            <div class="container-fluid">
                <!-- Mobile Menu Button -->
                <button class="btn mobile-menu-btn me-3" type="button" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="me-auto">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?action=dashboard">
                                <i class="bi bi-house"></i> Sistema
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= $breadcrumb ?? 'Dashboard' ?>
                        </li>
                    </ol>
                </nav>
                
                <!-- User Menu -->
                <div class="dropdown user-dropdown">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            <?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)) ?>
                        </div>
                        <span class="d-none d-sm-inline">
                            <?= $_SESSION['nombre_completo'] ?? $_SESSION['username'] ?? 'Usuario' ?>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <h6 class="dropdown-header">
                                <i class="bi bi-person-circle me-2"></i>
                                <?= $_SESSION['username'] ?? 'Usuario' ?>
                            </h6>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="index.php?action=perfil">
                                <i class="bi bi-person me-2"></i>
                                Mi Perfil
                            </a>
                        </li>
                        <?php if ($auth->isAdmin()): ?>
                        <li>
                            <a class="dropdown-item" href="index.php?action=admin">
                                <i class="bi bi-gear me-2"></i>
                                Administración
                            </a>
                        </li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <!-- LOGOUT SIN data-action para evitar duplicados -->
                            <a class="dropdown-item text-danger" href="index.php?action=logout">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Cerrar Sesión
                            </a>                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <?php if (isset($content)): ?>
                <?= $content ?>
            <?php else: ?>
                <!-- Default Content -->
                <div class="page-header fade-in">
                    <h1 class="page-title">
                        <i class="bi bi-speedometer2"></i>
                        <?= $title ?? 'Dashboard' ?>
                    </h1>
                    <?php if (isset($message)): ?>
                        <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <?= htmlspecialchars($message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <!-- Custom JavaScript -->
    <script src="assets/js/main.js?v=<?= time() ?>"></script>
    
    <script>
        // Enhanced Bootstrap Integration
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Sidebar Toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                });
            }
            
            // Close sidebar when clicking overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }
            
            // Close sidebar on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
            
            // Enhanced table interactions
            const tables = document.querySelectorAll('.table tbody tr');
            tables.forEach(function(row) {
                row.addEventListener('click', function() {
                    // Remove active class from all rows
                    tables.forEach(r => r.classList.remove('table-active'));
                    // Add active class to clicked row
                    this.classList.add('table-active');
                });
            });
            
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
        
        // Theme Toggle (optional)
        function toggleTheme() {
            const htmlElement = document.documentElement;
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            htmlElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }
        
        // Load saved theme

        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        }
    </script>
    
    <?php if (isset($additional_scripts)): ?>
        <?= $additional_scripts ?>
    <?php endif; ?>
</body>
</html>