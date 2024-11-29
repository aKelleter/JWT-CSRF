<?php
    require_once 'conf.php';
    
    use app\core\classes\CsrfToken\CsrfToken;
    use app\core\classes\JWT\JWT;
    use app\core\classes\WalTools\WalTools;
    use app\core\classes\WalHtml\WalHtml;
    
    require 'vendor/autoload.php';

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta name="X-CSRF-Token" content="<?= $csrfToken ?>">
    <title>Test formulaire</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <?= WalHtml::getMainMenu('') ?>
                <h1>Formulaire (protect with csrf)</h1>
                <form action="check.php" method="post">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom">
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </div>
            <div class="col-md-1"></div>
        </div>    
    </div>    
</body>
</html>