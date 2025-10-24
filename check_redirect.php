<?php
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $command_file = "commands/{$user_id}.txt";
    
    if (file_exists($command_file)) {
        $page = trim(file_get_contents($command_file));
        unlink($command_file);
        echo $page;
        exit;
    }
}
echo '';
?>
