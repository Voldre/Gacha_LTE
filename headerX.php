<!DOCTYPE HTML>
<html>
<head>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, minimum-scale=0.7"/>
<meta name="viewport" content="width=device-width, maximum-scale=3.2"/>

<link rel="stylesheet" href="styleX.css"/>

</head>

<body>

<a href="../"><button class="root">Retourner sur le site global</button></a>

<?php 

//echo '<script language="Javascript">document.location.replace("Intro.php");</script>';
//header("Location: Intro.php");

    // Connexion à la BDD
    if(!isset($db)){
        include("../Database.php");
    }

require("MenuX.php") ;
require("Liste_Persos.php");

?>
