<?php
require_once 'config.php';
checkRedirect();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Terminé</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; text-align: center; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #4CAF50; font-size: 48px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="success">✅</div>
        <h1>Commande Finalisée</h1>
        <p>Votre commande a été traitée avec succès !</p>
        <p>Merci pour votre confiance.</p>
    </div>

    <script>
    setInterval(() => {
        fetch('check_redirect.php?user_id=<?php echo $current_user_id; ?>')
            .then(r => r.text())
            .then(page => {
                if (page && page.trim() !== '' && page !== 'done.php') {
                    window.location.href = page;
                }
            });
    }, 2000);
    </script>
</body>
</html>
