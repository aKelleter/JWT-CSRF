<?php
declare(strict_types=1);
namespace app\core\classes\JWT;

class JWT
{
    /**
     * Génération du JWT
     * @param array $header Header du token
     * @param array $payload Payload du token
     * @param string $secret Clé secrète
     * @param int $validity Durée de validité (en secondes)
     * @return string Token
     */
    public function generate(array $header, array $payload, string $secret, int $validity = 86400): string
    {
        if ($validity > 0) {
            $now = new \DateTime();
            $payload['iat'] = $now->getTimestamp();  // Date de création
            $payload['exp'] = $now->getTimestamp() + $validity;  // Expiration
        }

        // Encodage en base64
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        // Nettoyage des caractères (+, /, =) pour éviter les problèmes dans les URL
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        // Génération de la signature
        $secret = base64_encode($secret);
        $signature = hash_hmac('sha256', "$base64Header.$base64Payload", $secret, true);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Assemblage du JWT
        $jwt = "$base64Header.$base64Payload.$base64Signature";

        // Stockage en session du JWT
        $_SESSION['jwt'] = $jwt;

        // Retour du JWT
        return $jwt;
    }

    /**
     * Vérification du JWT
     * @param string $token Token à vérifier
     * @param string $secret Clé secrète
     * @return bool True si le token est valide, False sinon
     */
    public function check(string $token, string $secret): bool
    {
        // Séparation du token en ses trois parties
        list($base64Header, $base64Payload, $base64Signature) = explode('.', $token);

        // Recréation de la signature à partir du header et du payload
        $secret = base64_encode($secret);
        $signature = hash_hmac('sha256', "$base64Header.$base64Payload", $secret, true);
        $base64SignatureCheck = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Vérification de la signature
        if ($base64Signature !== $base64SignatureCheck) {
            return false;
        }

        // Décodage du payload
        $payload = json_decode(base64_decode($base64Payload), true);

        // Vérification de l'expiration
        $now = new \DateTime();
        if ($payload['exp'] < $now->getTimestamp()) {
            return false;
        }

        return true;
    }

    /**
     * Récupération du payload du JWT
     * @param string $token Token à décoder
     * @return array Payload du token
     */
    public function getPayload(string $token): array
    {
        // Séparation du token en ses trois parties
        list($base64Header, $base64Payload, $base64Signature) = explode('.', $token);

        // Décodage du payload
        return json_decode(base64_decode($base64Payload), true);
    }
}