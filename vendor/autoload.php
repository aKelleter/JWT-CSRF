<?php
spl_autoload_register(function ($class) {
    // On découpe le nom de la classe
    $parts = explode('\\', $class);
    // On récupère le dernier élément
    $className = array_pop($parts);
    // On crée le chemin vers la classe
    $path = implode(DIRECTORY_SEPARATOR, $parts) . DIRECTORY_SEPARATOR . $className . '.php';
    // On importe la classe
    require $path;
});    
?>
