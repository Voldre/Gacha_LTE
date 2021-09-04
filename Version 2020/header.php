<!DOCTYPE HTML>
<html>
<head>

<meta charset="utf-8" />

<link rel="stylesheet" href="style.css"/>

</head>

<body>

<?php 

    // Connexion à la BDD
    
function isLocalhost($whitelist = ['127.0.0.1', '::1']) {
    return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
}

try {
    if(isLocalhost()){ // Si on est sur le localhost
        echo "<p>Vous êtes en local !</p>";
        $db = new PDO('mysql:host=localhost;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)); 
        // Vérification de la présence de la BDD, création de le BDD si elle n'existe pas.
        $sql = file_get_contents('gacha_bdd_vierge.sql');
        $qr = $db->exec($sql); // CREATE DB IF NOT EXIST    
    }
    else{ // Sinon, on est sur free
        echo "<p>Bienvenue sur le site free.fr !</p>";
        
        //$db = new PDO('mysql:host=sql.free.fr; dbname=voldre');
        // ---------
        $hostname    = 'localhost';   // voir hébergeur ou "localhost" en local (et chez free.fr)
        $database    = 'voldre';     // nom de la BdD
        $username    = '';   // identifiant ("root" en local)
        $password    = '';                 // mot de passe (vide en local)
        $strConn     = 'mysql:host='.$hostname.';dbname='.$database.';charset=utf8';    // UTF-8
        $extraParam    = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        // rapport d'erreurs sous forme d'exceptions
                PDO::ATTR_PERSISTENT => true,                         // Connexions persistantes
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // fetch mode par defaut
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"    // encodage UTF-8
                );
        // Instancie la connexion
        $db = new PDO($strConn, $username, $password, $extraParam);        

        // ---------
    }

}catch (Exception $e)
{
    die('Erreur : ' . $e->getMessage());
    echo $e->getMessage();
    echo "<p>Nous allons importer la Base de Données existante...</p>";
}    

require("Menu.php") ;
require("Liste_Persos.php");

?>
