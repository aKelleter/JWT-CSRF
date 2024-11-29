<?php
    require_once 'conf.php';
    
    use app\core\classes\CsrfToken\CsrfToken;
    use app\core\classes\JWT\JWT;
    use app\core\classes\WalTools\WalTools;
    use app\core\classes\WalHtml\WalHtml;
    
    require 'vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Test formulaire</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <?= WalHtml::getMainMenu('') ?>
                <h1>Generate and check</h1>    
                <?php                   

                    // Génération du token CSRF
                    $csrf = new CsrfToken();
                    $csrfToken = $csrf->generate(SECRET);

                    // Stockage du token CSRF dans un cookie sécurisé (également stocké en session par la classe CsrfToken)   
                    setcookie('csrf_token', $csrfToken, [
                        'expires' => time() + 3600,  // Expire dans 1 heure
                        'path' => '/',
                        'secure' => true,  // Seulement en HTTPS
                        'samesite' => 'strict'  // Limite les attaques CSRF
                    ]);

                    // Génération du JWT
                    $jwt = new JWT();
                    $header = ['typ' => 'JWT', 'alg' => 'HS256'];
                    $payload = ['user' => ['id' => 123, 'name' => 'John Doe']];
                    $jwtToken = $jwt->generate($header, $payload, SECRET);

                    // Stockage du JWT dans un cookie sécurisé
                    setcookie('jwt_token', $jwtToken, [
                        'expires' => time() + 3600,  // Expire dans 1 heure
                        'path' => '/',
                        'domain' => DOMAIN,
                        'secure' => true,  // Seulement en HTTPS
                        'httponly' => true,  // Non accessible via JavaScript
                        'samesite' => 'strict'  // Limite les attaques CSRF
                    ]);

                    // Mise en session du JWT
                    $_SESSION['jwt'] = $jwtToken;

                    // Affichage des tokens
                    echo 'Token CSRF : ' . $csrfToken . '<br>';
                    echo 'Token JWT : ' . $jwtToken . '<br>';

                    // Vérification du token CSRF
                    if ($csrf->check($csrfToken, SECRET)) {
                        echo 'Token CSRF valide<br>';
                    } else {
                        echo 'Token CSRF invalide<br>';
                    }

                    // Vérification du token JWT
                    if ($jwt->check($_SESSION['jwt'], SECRET)) {
                        echo 'Token JWT valide<br>';
                    } else {
                        echo 'Token JWT invalide<br>';
                    }

                    // Affichage du payload du JWT
                    $payload = $jwt->getPayload($_SESSION['jwt']);         
                    WalTools::printr($payload, 'Payload', WalTools::PRINTR);
                
                    
                ?>
            </div>
            <div class="col-md-1"></div>
        </div>    
    </div>    
</body>
</html>

