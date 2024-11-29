<?php
declare(strict_types=1);
namespace app\core\classes\WalTools;

class WalTools {

    const VARDUMP = 'var_dump';
    const PRINTR = 'print_r';

    /**
     * Affiche un print_r ou un var_dump dans une balise <pre>
     * 
     * @param mixed $data 
     * @param string $title 
     * @param mixed $type 
     * @param bool $exit 
     * @return void 
     */
    static function printr($data, $title = '', $type = self::PRINTR, $exit = false) {

        echo '<div style="border: 1px solid #000; padding: 1em;">';
        if ($type == self::VARDUMP) {
            echo '<pre>';
            echo '<h3>'.$title.'</h3>';
            echo '<hr>';
            var_dump($data);
            echo '</pre>';
        } else {
            echo '<pre>';
            echo '<h3>'.$title.'</h3>';
            echo '<hr>';
            print_r($data);
            echo '</pre>';
        }
        echo '</div>';


        ($exit)? exit : '';
        
    }
}


