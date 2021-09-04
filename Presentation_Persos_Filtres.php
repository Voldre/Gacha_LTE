<!DOCTYPE HTML>
<html>
<head>

<meta charset="utf-8" />

<link rel="stylesheet" href="style2.css"/>
    <meta name="viewport" content="width=device-width, minimum-scale=0.7"/>
    <meta name="viewport" content="width=device-width, maximum-scale=3.2"/>

</head>

<script type="text/javascript">

function getCookie(cname) {
    var name = cname + "=";
    var decoded_cookie = decodeURIComponent(document.cookie);
    var carr = decoded_cookie.split(';');
    for(var i=0; i<carr.length;i++){
    var c = carr[i];
    while(c.charAt(0)==' '){
        c=c.substring(1);
    }
    if(c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
    }
     }
     return "";
}

function loading(){

    elmts_filters = JSON.parse(getCookie('elmts_filters'));
    stars_filters = JSON.parse(getCookie('stars_filters'));

    console.log("Activation :" + stars_filters + elmts_filters)

    if(elmts_filters.length > 0){
        Object.values(elmts_filters).forEach(value => {
        document.getElementById(value).checked = true;});
    }
    if(stars_filters.length > 0){
        Object.values(stars_filters).forEach(value => {
        document.getElementById(value).checked = true;});
    }
}
</script>


<body onload="loading()">

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

function get_characters($stars, $elmts, $db){

    $stars = substr(json_encode($stars),1,-1);
    if($stars == ""){ $stars = '3,4,5';}    
    $elmts = substr(json_encode($elmts),1,-1);
    if($elmts == ""){ $elmts = '"feu","glace","foudre","terre","vent","tenebres","lumiere","physique"' ;}

    $reponse = $db->query("SELECT * FROM Cartes_Personnages WHERE STARS IN($stars) AND ELMT IN($elmts)");

    while( $data = $reponse->fetch()){
        $characters[$data['NOM']] = $data;
    }
    $reponse->closeCursor();

    return $characters;
}


function filter_button($id,$text){ ?>
<label class="switch">
<input id=<?= $id ?> type="checkbox" onclick="take_filters()"/>
<span class="slider round"><?= $text ?></span>
</label>
<?php
} ?>

<iframe style="float: right; margin-top: -20px; margin-right: 7px;" width=495 src="_Avantage Elementaires.png"></iframe>

<audio autoplay controls loop  style="display: none;">
  <source src="Valkyrie_Anatomia.mp3" type="audio/mpeg">
</audio>
<div class="no_mobile">
<h3>Toutes les statistiques présentées ici sont les valeurs moyennes pour chaque personnage, 
<br/>vous pouvez donc obtenir aléatoirement des statistiques légèrement meilleures ou moins bonnes.</h3>
</div>
<h4 style="margin-left: 20px;">Vous pouvez aussi créer un nouveau personnage <a href="Creation.php">ici...</a></h4>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<form action="index.php" method="post" style="display:inline;">
<input type="submit" value="Retourner au jeu"/>
</form> &nbsp; &nbsp; &nbsp;
<form action="Presentation_Persos.php" method="post" style="display:inline;">
<input type="submit" value="Quitter les filtres"/>
</form>

<!-- LISTE DES BOUTONS DE FILTRES -->
<br/>
<div class="container" style="max-width:900px;">
<?php
    filter_button("3","3 étoiles");
    filter_button("4","4 étoiles");
    filter_button("5","5 étoiles");
    echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";
$requete = $db->query('SELECT DISTINCT elmt FROM Cartes_Personnages');
$reponse = [];
while($reponse = $requete->fetch()){
    $liste_elmts[] = $reponse['elmt']; }
$requete->closeCursor();

foreach($liste_elmts as $elmt){
    filter_button($elmt,$elmt); 
}

echo '<script>document.cookie=\'liste_elmts='.json_encode($liste_elmts).'\'</script>';

?>
</div>



<!-- APPLICATION DES FILTRES -->

<script type="text/javascript">

function take_filters(){
var filters = [];
var filters_elmts_apply = [];
var filters_stars_apply = [];

filters = JSON.parse(getCookie("liste_elmts"));

console.log("filters :"+filters);

Object.values(filters).forEach(value => {
    if(document.getElementById(value).checked) {
        filters_elmts_apply.push(value); } 
});

for(i = 3; i <= 5; i++){
    if(document.getElementById(i).checked) {
        filters_stars_apply.push(i); } 
}

console.log("filters_stars_apply : "+filters_stars_apply);
console.log("filters_elmts_apply : "+filters_elmts_apply);

document.cookie="stars_filters=" + JSON.stringify(filters_stars_apply);
document.cookie="elmts_filters=" + JSON.stringify(filters_elmts_apply);

console.log("cookie:"+getCookie("elmts_filters"));

location.reload();

}

</script>



<!-- Récupération des personnages -->
<div class="container" style="margin-left: -10px;">
<?php 

if(isset($_COOKIE['stars_filters']) && $_COOKIE['elmts_filters']){
    $stars_filters = json_decode($_COOKIE['stars_filters']);
    $elmts_filters = json_decode($_COOKIE['elmts_filters']);
    
    show_characters(get_characters($stars_filters,$elmts_filters, $db));
} 
?>
</div>

</body>
</html>