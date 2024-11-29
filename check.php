<?php
require_once 'conf.php';
use app\core\classes\CsrfToken\CsrfToken;
use app\core\classes\JWT\JWT;
use app\core\classes\WalHtml\WalHtml;
use app\core\classes\WalTools\WalTools;

require 'vendor/autoload.php';

$jwt = new JWT();

// On récupère le token JWT dans le cookie
$jwtToken = $_COOKIE['jwt_token'] ?? '';
// On récupère le token CSRF dans le cookie
$csrfCookie = $_COOKIE['csrf_token'] ?? '';
// Si le header est présent, on le récupère, sinon on récupère le POST
$csrfHeader = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? '';

// Vérifications
try {

    // On vérifie la correspondance entre le token CSRF du cookie et celui du header
    if ($csrfCookie !== $csrfHeader) {
        throw new \Exception('Token CSRF non correspondant');
    }

    // On vérifie le token CSRF
    $csrf = new CsrfToken();
    if (!$csrf->check($csrfCookie, SECRET)) {
        throw new \Exception('Token CSRF invalide');
    }

    // On vérifie le token JWT
    if (!$jwt->check($jwtToken, SECRET)) {
        throw new \Exception('Token JWT invalide');
    }

    // On traite les données
    $jwtData = $jwt->getPayload($jwtToken) ?? '';
    WalTools::printr($_POST, '$_POST', WalTools::PRINTR);

    if (!empty($jwtData)) {
       echo json_encode($jwtData);
    }else{
       echo json_encode(['message' => 'Pas de données']); 
    }


} catch (\Exception $e) {
    //Si le JWT ou le CSRF ne sont pas valide
    http_response_code(401);
    echo json_encode(['error' => 'Erreur d\'authentification : ' . $e->getMessage()]);
}
