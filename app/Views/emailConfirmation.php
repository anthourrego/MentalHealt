<?= $this->extend('UI/authLayout') ?>

<?= $this->section('content') ?>

<div class="container confirmation-container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card confirmation-card">
                <div class="card-body text-center">
                    
                    <?php if ($success): ?>
                        <!-- Caso de éxito -->
                        <div class="confirmation-icon success mb-4">
                            <i class="fas fa-check-circle fa-5x text-success"></i>
                        </div>
                        <h2 class="confirmation-title text-success mb-3">¡Email Verificado!</h2>
                        <p class="confirmation-message mb-4">
                            Tu dirección de email ha sido verificada correctamente. Ahora puedes acceder a todas las funcionalidades de MentalHealth.
                        </p>
                        
                        <div class="user-info-container mb-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="user-info-card">
                                        <div class="user-details p-3">
                                            <h5><?= esc($user->first_name . ' ' . $user->last_name) ?></h5>
                                            <p class="text-muted mb-0">
                                                <i class="fas fa-envelope me-2"></i><?= esc($user->email) ?>
                                            </p>
                                            <p class="mb-0 mt-2">
                                                <span class="badge bg-success text-white">
                                                    <i class="fas fa-user-check me-1"></i>
                                                    Cuenta Verificada
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="confirmation-buttons">
                            <a href="<?= site_url() ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Caso de error general -->
                        <div class="confirmation-icon error mb-4">
                            <i class="fas fa-times-circle fa-5x text-danger"></i>
                        </div>
                        <h2 class="confirmation-title text-danger mb-3">Verificación Fallida</h2>
                        <p class="confirmation-message mb-4">
                            <?= $error_message ?? 'No hemos podido verificar tu dirección de email. El enlace puede ser inválido o ya ha sido utilizado.' ?>
                        </p>
                        
                        <div class="confirmation-buttons">
                            <a href="<?= site_url('') ?>" class="btn btn-primary mb-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                            </a>
                            <a href="<?= site_url('register') ?>" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-user-plus me-2"></i>Registrarse
                            </a>
                        </div>
                        
                    <?php endif; ?>
                </div>
                
                <div class="card-footer text-center py-3">
                    <p class="mb-0">MentalHealth - Tu bienestar es nuestra prioridad</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .confirmation-container {
        padding-top: 50px;
        padding-bottom: 50px;
    }
    
    .confirmation-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .confirmation-icon {
        display: inline-block;
        padding: 20px;
        border-radius: 50%;
        margin-bottom: 20px;
    }
    
    .confirmation-icon.success {
        background-color: rgba(40, 167, 69, 0.1);
    }
    
    .confirmation-icon.expired {
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    .confirmation-icon.error {
        background-color: rgba(220, 53, 69, 0.1);
    }
    
    .confirmation-title {
        font-weight: 700;
        margin-bottom: 15px;
    }
    
    .confirmation-message {
        color: #6c757d;
        font-size: 1.1rem;
        max-width: 80%;
        margin: 0 auto 25px;
    }
    
    .user-info-container {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
    }
    
    .user-info-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    
    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
    }
    
    .confirmation-buttons {
        margin: 30px 0;
    }
    
    .confirmation-buttons .btn {
        padding: 10px 20px;
        font-weight: 600;
    }
    
    .additional-info {
        border-top: 1px solid #eee;
        padding-top: 20px;
        max-width: 80%;
        margin: 0 auto;
    }
    
    .card-footer {
        background-color: rgba(0, 123, 255, 0.05);
        color: #6c757d;
    }
</style>
<?= $this->endSection() ?>