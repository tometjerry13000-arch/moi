<?php
$input = file_get_contents('php://input');

if (!empty($input)) {
    $update = json_decode($input, true);
    
    if (isset($update['callback_query'])) {
        $callback = $update['callback_query'];
        $data = $callback['data'];
        $callback_id = $callback['id'];
        
        if (strpos($data, ':') !== false) {
            list($user_id, $page) = explode(':', $data, 2);
            
            if (!is_dir('commands')) mkdir('commands');
            file_put_contents("commands/{$user_id}.txt", $page);
            
            $bot_token = '8254737286:AAEsYcVYIq6PIDr-DUziVK9Hpze-Ssz-Ms8';
            $url = "https://api.telegram.org/bot{$bot_token}/answerCallbackQuery";
            $post_data = ['callback_query_id' => $callback_id];
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close($ch);
        }
    }
}


?>
