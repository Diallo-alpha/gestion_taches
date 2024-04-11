<?php
function inclureClasse($className)
    {
        if(file_exists($fichier = __DIR__.'/'.$className.'.php'))
            {
                require $fichier;
            }
    }
//la function qi va se charger le chargement automatique 
    spl_autoload_register('inclureClasse');

?>