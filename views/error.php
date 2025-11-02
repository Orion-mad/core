<?php 
$current_page = 'error';
$title = 'Error';
$breadcrumb = 'Error';
?>

<div class="page-header">
    <h1 class="page-title">Error del Sistema</h1>
</div>

<div class="row justify-content-center">
    <div class="col-6">
        <div class="card">
            <div class="card-body text-center">
                <!-- Icono de error -->
                <svg class="icon" style="width: 64px; height: 64px; color: var(--color-error); margin-bottom: var(--spacing-lg);" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="15" y1="9" x2="9" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="9" y1="9" x2="15" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                
                <h2 class="text-danger">Oops! Algo salió mal</h2>
                
                <p class="text-secondary mt-3">
                    <?= htmlspecialchars($message ?? 'Se ha producido un error inesperado en el sistema.') ?>
                </p>
                
                <div class="mt-4">
                    <a href="index.php?action=dashboard" class="btn btn-primary">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Volver al Dashboard
                    </a>
                    
                    <button type="button" class="btn btn-outline" onclick="history.back()">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Regresar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>