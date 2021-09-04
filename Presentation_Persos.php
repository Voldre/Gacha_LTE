<!DOCTYPE HTML>
<html>
<head>

<meta charset="utf-8" />

<link rel="stylesheet" href="style2.css"/>
    <meta name="viewport" content="width=device-width, minimum-scale=0.7"/>
    <meta name="viewport" content="width=device-width, maximum-scale=3.2"/>

</head>

<body>
<?php   

    include("../Database.php");
    require("Liste_Persos.php");

function show_characters($characters){
    foreach($characters as $key => $value){
        ?>
            <div class="div_presentation"> 
            <img src=<?= $key.".png" ?> />
            </div>
                <p class="infos_presentation"><span style="text-align  center;"> <?= $key ?> </span>
                    <br/> Points de vie :  <?= $characters[$key]['PVM'] ?>
                    <br/> Attaque : <?= $characters[$key]['ATK'] ?>
                    <br/> Défense : <?= $characters[$key]['DEF'] ?>
                    <br/> Element : <?= $characters[$key]['ELMT'] ?>
                </p> <?php
    }
}

?>

<iframe style="float: right; margin-right: 7px;" width=495 src="_Avantage Elementaires.png"></iframe>

 <audio autoplay controls loop  style="display: none;">
  <source src="Valkyrie_Anatomia.mp3" type="audio/mpeg">
  </audio>

<h3>Toutes les statistiques présentées ici sont les valeurs moyennes pour chaque personnage, 
<br/>vous pouvez donc obtenir aléatoirement des statistiques légèrement meilleures ou moins bonnes.</h3>
<h4 style="margin-left: 20px;">Vous pouvez aussi créer un nouveau personnage <a href="Creation.php">ici...</a></h4>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<form action="index.php" method="post" style="display:inline;">
<input type="submit" value="Retourner au jeu"/>
</form> &nbsp; &nbsp; &nbsp;
<form action="Presentation_Persos_Filtres.php" method="post" style="display:inline;">
<input type="submit" value="Filtrer la liste"/>
</form>

<div class="container">
<h4>Liste des personnages <span style="color: gold;">5 étoiles</span> (1% de chance) : </h4>
<?php show_characters($liste_5_stars); ?>
</div>

<div class="container">
<h4>Liste des personnages <span style="color: silver;">4 étoiles</span> (9% de chances) : </h4>
<?php show_characters($liste_4_stars); ?>
</div>

<div class="container">
<h4>Liste des personnages <span style="color: rgb(206, 65, 9);">3 étoiles</span> (90% de chances) : </h4>
<?php show_characters($list); ?>
</div>

<form action="index.php" method="post">
<input type="submit" value="Retourner au jeu"/>
</form>

</body>
</html>