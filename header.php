<!DOCTYPE HTML>
<html>
<head>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, minimum-scale=0.7"/>
<meta name="viewport" content="width=device-width, maximum-scale=3.2"/>

<link rel="stylesheet" href="style.css"/>

<!-- Ajout de jQuery -->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script src="http://code.jquery.com/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.8.17/jquery-ui.min.js"></script>
<script src="jquery.ui.touch-punch.min.js"></script>

</head>

<body>

<a href="../"><button class="root">Retourner sur le site global</button></a>

<?php 
    /*
    function goToPage($page){
        echo "<script language=\"Javascript\">document.location.replace($page);</script>";
    }*/

    // Connexion à la BDD
    if(!isset($db)){
        include("../Database.php");
    }

    // Si la personne n'est pas connecté, redirection automatique sur
    // le formulaire de connexion
    if(!isset($_SESSION['id']) && strpos($_SERVER['REQUEST_URI'],"identification") <= -1){
        echo "<p>Connectez-vous pour pouvoir jouer, ou créer un compte!</p>";
        echo '<meta http-equiv="Refresh" content="1; url=identification.php?Ma_Connexion=Connexion" />';
    
    }

    if(isset($_GET['new_game']))
    {   
        unset($_SESSION['personnages']);
        $_SESSION['invocation'] = 0;
    
        echo '<script language="Javascript">document.location.replace("Intro.php");</script>';
    
    } 
    
    
require("Menu.php") ;
require("Liste_Persos.php");

?>
