<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?? 'Dashboard' ?> - <?= APP_NAME ?></title>
    <!-- Bootstrap 5.3 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/orion.css">
    <link rel="stylesheet" href="assets/css/main.css">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 70px;
            --orion-primary: #0d6efd;
            --orion-secondary: #6c757d;
            --orion-success: #198754;
            --orion-warning: #ffc107;
            --orion-danger: #dc3545;
            --orion-info: #0dcaf0;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            overflow-x: hidden;
        }
        
        /* Sidebar Styling */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            z-index: 1000;
            transition: transform 0.3s ease-in-out;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }
        
        .sidebar-brand:hover {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .sidebar-brand i {
            font-size: 2rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem;
            border-radius: 0.5rem;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
            height: calc(100vh - 120px);
            overflow-y: auto;
        }
        
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
        
        .nav-item {
            margin: 0.25rem 1rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }
        
        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        
        .nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        
        .nav-link.admin-link {
            background: linear-gradient(45deg, #ff6b6b, #ffa500);
            color: white;
            font-weight: 600;
            margin-top: 1rem;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }
        
        .nav-link.admin-link:hover {
            background: linear-gradient(45deg, #ff5252, #ff9500);
            transform: translateX(4px) translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        /* Header/Navbar */
        .main-header {
            background: white;
            height: var(--header-height);
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .navbar-brand-text {
            font-weight: 600;
            color: #495057;
        }
        
        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            font-size: 0.9rem;
        }
        
        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: #495057;
            font-weight: 500;
        }
        
        /* User Dropdown */
        .user-dropdown .dropdown-toggle {
            border: none;
            background: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #495057;
            font-weight: 500;
        }
        
        .user-dropdown .dropdown-toggle:after {
            margin-left: 0.5rem;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(45deg, var(--orion-primary), var(--orion-info));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        /* Content Area */
        .content-wrapper {
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
        }
        
        /* Page Header */
        .page-header {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .page-title {
            margin: 0;
            color: #212529;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .page-title i {
            color: var(--orion-primary);
            font-size: 1.75rem;
        }
        
        /* Cards Enhancement */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.2s ease;
        }
        
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        
        .card-header {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            border-bottom: 1px solid #dee2e6;
            border-radius: 1rem 1rem 0 0 !important;
            padding: 1.5rem;
        }
        
        .card-header h3 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057;
        }
        
        /* Buttons Enhancement */
        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .btn i {
            font-size: 0.9rem;
        }
        
        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(45deg, var(--orion-primary), var(--orion-info));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            color: #6c757d;
            font-weight: 500;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-wrapper {
                padding: 1rem;
            }
            
            .page-header {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }
        }
        
        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
        }
        
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
        }
        
        /* Overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        
        @media (max-width: 768px) {
            .sidebar-overlay.show {
                display: block;
            }
        }
        
        /* Table Enhancements */
        .table {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .table thead th {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            padding: 1rem;
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f8f9fa;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        /* Badge Enhancements */
        .badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
        }
        
        /* Form Enhancements */
        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--orion-primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        /* Animation Classes */
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
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
    <script src="assets/js/main.js"></script>
    
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