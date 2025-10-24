<?php
define('BOT_TOKEN', '8254737286:AAEsYcVYIq6PIDr-DUziVK9Hpze-Ssz-Ms8');
define('CHAT_ID', '7747778364');

// Test simple
function testBot() {
    $data = [
        'chat_id' => CHAT_ID,
        'text' => '🧪 TEST DIRECT - Si vous voyez ce message, le bot fonctionne',
        'parse_mode' => 'HTML'
    ];
    
    $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return ['http_code' => $http_code, 'result' => $result];
}

$test = testBot();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Bot</title>
</head>
<body>
    <h1>🧪 Test du Bot Telegram</h1>
    
    <h2>Résultat:</h2>
    <pre>HTTP Code: <?php echo $test['http_code']; ?>

Response: <?php echo $test['result']; ?></pre>

    <?php if ($test['http_code'] == 200): ?>
        <p style="color: green; font-size: 20px;">✅ BOT FONCTIONNEL - Message envoyé</p>
    <?php else: ?>
        <p style="color: red; font-size: 20px;">❌ ERREUR BOT - Vérifiez token/chat_id</p>
    <?php endif; ?>
    
    <p><a href="index.php">← Retour à l'accueil</a></p>
</body>
</html>
