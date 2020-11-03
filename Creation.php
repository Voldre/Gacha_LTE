<!DOCTYPE HTML>
<html>
<head>

<meta charset="utf-8" />

<link rel="stylesheet" href="style2.css"/>

</head>

<body>
<?php   

session_start();

/*

Mettre à jour les fichiers de force (CSS qui refuse de se mettre à jour) : 
    Ctrl + F5 force le navigateur à supprimer la mémoire cache, donc reset le CSS

 */



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
  <source src="Plus_que_Dechu.ogg" type="audio/ogg">
  </audio>

<h3>Bienvenue dans l'espace création du jeu Gacha LTE !
<br/>Ici vous pouvez créer vos propres personnages et personnaliser leurs caractéristiques.</h3>
<h4>&nbsp; <span class="underline">Pour cela, rien de plus simple, remplissez le formulaire ci-dessous :</span></h4>

<div class="container_creation">

<form action="" method="post">
<h4 class="gold_red">Saisissez le nom de votre personnage en veillant bien à ce que son nom soit identique à son nom de fichier.</a></h4>
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
<h4 class="gold_red"> Par rapport aux autres personnages de même rareté, comment se situe-t-il niveau ...</h4>
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
    
    $requete_nb = $db->prepare('SELECT COUNT(*) AS nb FROM Cartes_Personnages WHERE Nom = ?');
    $requete_nb->execute( array( $_POST['nom']) );
    $data = $requete_nb->fetch();
    $requete_nb->closeCursor();

    if($_POST['nom'] == ""){
        echo "<p>Erreur : Vous n'avez pas saisi de nom!</p>";
    }
    else if($data['nb'] != 0){
    ?>
        <p>Erreur ! Ce personnage existe déjà!</p>
        <p>Vous pouvez l'effacer pour le recréer en appuyant... 
            <form method="post">
                <input type="submit" name="delete" value="ici"/>
                <input type="hidden" name="nom_perso" value= <?= $_POST['nom'] ?> />
        </form> </p>
    <?php
    }
    else{
    ?>
        <h4>Voici la carte proposée pour votre personnage :</h4>
        <div class="div_presentation"> 
        <img src=<?= $_POST['nom'].".png" ?> alt="Le nom saisi ne renvoi vers aucune image, rappel : format attendu .png" />
        </div>
            <?php $stars = $_POST['stars']; // PERMET DE SIMPLIFIER L'ECRITURE ?>
            <p class="infos_presentation"><span style="text-align  center;"> <?= $_POST['nom'] ?> </span>
                <br/> Points de vie :  <?= 10 + 2*$stars; ?>
                <br/> Attaque : <?=  2 + 2*$_POST['atk'] + $stars*2; ?>
                <br/> Défense : <?= 2 + 2*$_POST['def'] + $stars*2; ?>
                <br/> Element : <?= $_POST['elmt'] ?>   </p> 

            <?php $_SESSION['tempo'] = $_POST;  // PERMET DE GARDER EN MEMOIRE LES STATS après la validation d'un nouveau formulaire ?>

        <h4> Confirmez-vous la création du personnage ? </h4>
        <form method="POST">
            <select name="valide">
                <option value="1">Oui</option>
                <option value="0">Non</option>
            </select>
            <input type="submit" value="valider"/>
        </form>

    <?php
    }
}
        // Une fois le personnage créé :

if(isset($_POST['valide']) && $_POST['valide'] == 1){

    // Utilisation des données du personnage enregistré temporairement :
    $perso = $_SESSION['tempo'];
    unset($_SESSION['tempo']);

    // ATK et DEF : 2 + 2*$_POST['atk'] + $perso['stars']*2  Soit 3* :  8 classique, 4 à 12, 4* pareil +2, 5* pareil +4
    $requete = $db->prepare('INSERT INTO Cartes_Personnages(NOM,PVM,ATK,DEF,ELMT,STARS) VALUES (?, ?, ?, ?, ?, ?)');
    $requete->execute( array($perso['nom'], 10 + 2*$perso['stars'], 2 + 2*$perso['atk'] + $perso['stars']*2,
                            2 + 2*$perso['def'] + $perso['stars']*2, $perso['elmt'], $perso['stars']) );

    echo "<h3>Le personnage <span class=\"new_hero\"> $perso['nom'] </span> a bien été créé !</h3>";
}
else if(isset($_POST['valide'])){
    echo "<p>Vous pouvez changer les paramètres en les réécrivants ci-dessus.</p>";
    unset($_SESSION['tempo']);

}

if( isset($_POST['delete']) ){
    $requete = $db->prepare('DELETE FROM Cartes_Personnages WHERE Nom = ?');
    $requete->execute( array($_POST['nom_perso']));
    $requete->closeCursor();
    echo "<p>Le personnage ".$_POST['nom_perso']." a été supprimé avec succès.</p>";
}
?>

<br/><br/>
<form action="index.php" method="post">
<input type="submit" value="Retourner au jeu"/>
</form>
<br/>
<form action="Presentation_Persos.php" method="post">
<input type="submit" value="Revoir la liste des personnages"/>
</form>


<h4>Pour informations :</h4>
<?php

$requete = $db->query('SELECT elmt, COUNT(elmt) as nb_elmt FROM Cartes_Personnages GROUP BY ELMT');

while($data = $requete->fetch()){
    echo "<p>Element :  ".$data['elmt']." - Quantité : ".$data['nb_elmt'].".</p>";
}
$requete->closeCursor();
?>

</body>
</html>