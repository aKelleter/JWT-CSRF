<?php
    require_once 'conf.php';  
    use app\core\classes\WalHtml\WalHtml;
    require 'vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="assets/js/script.js" defer></script>
    <title>Test Ajax</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <?= WalHtml::getMainMenu('') ?>
                <h1>Ajax</h1>                
                <button class="btn btn-secondary" id="request_token">Request</button>
            </div>
            <div class="col-md-1"></div>
        </div>    
    </div>    
</body>
</html>