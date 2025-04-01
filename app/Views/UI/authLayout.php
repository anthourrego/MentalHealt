<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'MentalHealth' ?></title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('Library/bootstrap/bootstrap.min.css') ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('Library/fontawesome/all.min.css') ?>">
    <!-- Custom styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>">
    
    <!-- CSRF -->
    <?= csrf_meta() ?>
    
    <?= $this->renderSection('styles') ?>
</head>
<body class="auth-body">
    
    <div class="auth-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <a href="<?= site_url() ?>" class="navbar-brand">
                        <img src="<?= base_url('assets/img/noPhoto.png') ?>" alt="MentalHealth" height="60">
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="auth-content">
        <?= $this->renderSection('content') ?>
    </div>
    
    <footer class="auth-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>&copy; <?= date('Y') ?> MentalHealth. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="<?= base_url('Library/bootstrap/bootstrap.bundle.min.js') ?>"></script>
    <!-- jQuery -->
    <script src="<?= base_url('Library/jquery/jquery.min.js') ?>"></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>