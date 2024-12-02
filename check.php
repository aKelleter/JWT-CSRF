<?php
require_once 'conf.php';
use app\core\classes\CsrfToken\CsrfToken;
use app\core\classes\JWT\JWT;
use app\core\classes\WalHtml\WalHtml;
use app\core\classes\WalTools\WalTools;

require 'vendor/autoload.php';

//DEBUG// WalTools::printr($_COOKIE,'COOKIE');

// On récupère le token JWT dans le cookie
$jwtToken = $_COOKIE[COOKIENAME_JWT_TOKEN] ?? '';
// On récupère le token CSRF dans le cookie
$csrfCookie = $_COOKIE[COOKIENAME_CSRF_TOKEN] ?? '';

// Si le header est présent, on le récupère, sinon on récupère le POST ou dans le cookie
$csrfHeader = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? $csrfCookie ?? '';

//DEBUG// WalTools::printr($jwtToken,'jwtToken');
//DEBUG// WalTools::printr($csrfCookie,'csrfCookie');

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
    $jwt = new JWT();
    if (!$jwt->check($jwtToken, SECRET)) {
        throw new \Exception('Token JWT invalide');
    }

    // On traite les données
    $jwtData = $jwt->getPayload($jwtToken) ?? '';    
       
    if(isset($_POST['type_form']) && $_POST['type_form'] == 'form_user')
    {
        WalTools::printr($_POST, '$_POST', WalTools::PRINTR);
    }else {
        WalTools::printr($jwtData, '$jwtData', WalTools::PRINTR);
        if (isset($jwtData['user']) && !empty($jwtData['user'])) {
            echo json_encode($jwtData);
        }else{
            echo json_encode(['message' => 'no data']); 
        }
    }
    
} catch (\Exception $e) {
    //Si le JWT ou le CSRF ne sont pas valide
    http_response_code(401);
    echo json_encode(['error' => 'Erreur d\'authentification : ' . $e->getMessage()]);
}
