<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $subject ?? 'Notificación' ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .header img {
            max-width: 200px;
        }
        .content {
            padding: 20px 0;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #17a2b8;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="<?= base_url('assets/img/noPhoto.png') ?>" alt="MentalHealth">
            <h2><?= $subject ?? 'Notificación' ?></h2>
        </div>
        
        <div class="content">
            <?= $content ?? '' ?>
        </div>
        
        <div class="footer">
            <p>© <?= date('Y') ?> MentalHealth. Todos los derechos reservados.</p>
            <p>Este es un correo automático, por favor no responda a este mensaje.</p>
        </div>
    </div>
</body>
</html>