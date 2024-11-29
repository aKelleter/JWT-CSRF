<?php
declare(strict_types=1);
namespace app\core\classes\WalHtml;

class WalHtml {

    
    /**
     * Génère et retourne le menu principal
     * 
     * @param mixed $path 
     * @return string 
     */
    static function getMainMenu($path) {

        $html = '
            <p>
                <a href="'.$path.'index.php">Index</a> |
                <a href="'.$path.'check.php">Vérifier les tokens</a> |
                <a href="'.$path.'ajax.php">Appel AJAX</a> | 
                <a href="'.$path.'form.php">Formulaire</a>
            </p>
            ';

        return $html;        
    }
}


