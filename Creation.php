<!DOCTYPE HTML>
<html>
<head>

<meta charset="utf-8" />

<link rel="stylesheet" href="style2.css"/>

</head>

<body>
<?php   
try {
    $db = new PDO('mysql:host=localhost;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));     

    $sql = file_get_contents('Gacha_BDD.sql');

    $qr = $db->exec($sql); // CREATE DB IF NOT EXIST
    }catch (Exception $e)
    {
    die('Erreur : ' . $e->getMessage());
    echo $e->getMessage();
    echo "<p>Nous allons importer la Base de Données existante...</p>";
    }  
         require("Liste_Persos.php");
?>

 <audio autoplay controls loop  style="display: none;">
  <source src="Valkyrie_Anatomia.mp3" type="audio/mpeg">
  </audio>

<h3>Bienvenue dans l'espace création du jeu Gacha LTE !
<br/>Ici vous pouvez créer vos propres personnages et personnaliser leurs caractéristiques.</h3>
<h4>&nbsp; Pour cela, rien de plus simple, remplissez le formulaire ci-dessous :</h4>

<div class="container_creation">

<form action="" method="post">
<h4>Saisissez le nom de votre personnage en veillant bien à ce que son nom soit identique à son nom de fichier.</a></h4>
<h5>&nbsp; Exemple : Mon personnage s'appelle Silarius, mon fichier s'appellera Silarius.png dans le dossier Gacha_LTE.</h5>
<p>Nom    : <input type="text" name="nom"/><p>
<p>Rareté : <select name="stars">
          <option value="3">3*</option>
          <option value="4">4*</option>
          <option value="5">5*</option>
        </select></p> 
<p>Element : <select name="elmt">
          <option value="feu">Feu</option>
          <option value="vent">Vent</option>
          <option value="glace">Glace</option>
          <option value="foudre">Foudre</option>
          <option value="terre">Terre</option>
          <option value="physique">Physique</option>
          <option value="lumiere">Lumière</option>
          <option value="tenebres">Ténèbres</option>
        </select></p> 
<h4> Par rapport aux autres personnages de même rareté, comment se situe-t-il niveau ...</h4>
<p>Attaque : <select name="atk">
          <option value="-2">Bien en dessous</option>
          <option value="-1">En dessous</option>
          <option value="0">Normal</option>
          <option value="1">Au dessus</option>
          <option value="2">Bien au dessus</option>
        </select></p> 
<p>Défense : <select name="def">
          <option value="-2">Bien en dessous</option>
          <option value="-1">En dessous</option>
          <option value="0">Normal</option>
          <option value="1">Au dessus</option>
          <option value="2">Bien au dessus</option>
        </select></p> 
<input type="submit" name="creation" value="Créer ce nouveau personnage"/>
</form>
</div>

<?php

if(isset( $_POST['creation'])){
    $_POST['nom'] = htmlspecialchars($_POST['nom']);

    if($_POST['nom'] == ""){
        echo "<p>Erreur : Vous n'avez pas saisi de nom!</p>";
    }
    else{   ?>

        <h4>Voici la carte proposée pour votre personnage :</h4>
        <div class="div_presentation"> 
        <img src=<?= $_POST['nom'].".png" ?> alt="Le nom saisi ne renvoi vers aucune image, rappel : format attendu .png" />
        </div>
            <p class="infos_presentation"><span style="text-align  center;"> <?= $_POST['nom'] ?> </span>
                <br/> Points de vie :  <?= 10 + 2*$_POST['stars'] ?>
                <br/> Attaque : <?=  2 + 2*$_POST['atk'] + $stars*2 ?>
                <br/> Défense : <?= 2 + 2*$_POST['def'] + $stars*2 ?>
                <br/> Element : <?= $_POST['stars'] ?>
            </p> 
            <?php $liste_valeurs = $_POST;
        <h4> Confirmez-vous la création du personnage? </h4>
        <form method="POST">
            < 
            <?php
        
        // ATK et DEF : 2 + 2*$_POST['atk'] + $stars*2  Soit 3* :  8 classique, 4 à 12, 4* pareil +2, 5* pareil +4
        $requete = $db->prepare('INSERT INTO Cartes_Personnages(NOM,PVM,ATK,DEF,ELMT,STARS) VALUES (?, ?, ?, ?, ?, ?)');
        $requete->execute( array($_POST['nom'], 10 + 2*$_POST['stars'], 2 + 2*$_POST['atk'] + $stars*2,
                  10 + 2*$_POST['stars'], 2 + 2*$_POST['def'] + $stars*2, $_POST['elmt'], $_POST['stars']));
    }
}


echo "<h4>Liste des personnages <span style=\"color: silver;\">4 étoiles</span> : </h4>";

<form action="index.php" method="post">
<input type="submit" value="Retourner au jeu"/>
</form>

</body>
</html>