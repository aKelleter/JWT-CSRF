<?php
declare(strict_types=1);
namespace app\core\classes\CsrfToken;

class CsrfToken {

    private const LENGHT_CSRF_TOKEN = 32;

    /**
     * Constructeur
     * @return void 
     */
    public function __construct()
    {
        // Démarrage de la session si elle n'est pas déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
   
    /**
     * Génération du token CSRF
     * @param string $secret Clé secrète
     * @return string Token CSRF
     */
    public function generate(string $secret): string
    {
        // Génération d'un token CSRF
        $token = bin2hex(random_bytes(self::LENGHT_CSRF_TOKEN));

        // Stockage du token CSRF
        $_SESSION['csrf_token'] = $token;

        // Génération du token CSRF final
        $csrfToken = hash_hmac('sha256', $token, $secret);

        return $csrfToken;
    }

    /**
     * Vérification du token CSRF
     * @param string $token Token CSRF à vérifier
     * @param string $secret Clé secrète
     * @return bool True si le token est valide, False sinon
     */
    public function check(string $token, string $secret): bool
    {
        // Récupération du token CSRF stocké
        $csrfToken = hash_hmac('sha256', $_SESSION['csrf_token'], $secret);

        // Vérification du token CSRF
        if ($token !== $csrfToken) {
            return false;
        }

        return true;
    }

}