<?php

require("Perso.php"); // Classe en tout tout premier
session_start(); // Avant tout code HTML mais après les classes
require("header.php");

/*

JS value to PHP with cookie :
https://stackoverflow.com/questions/9789283/how-to-get-javascript-variable-value-in-php

Ecriture :
    JS : document.cookie="variable"+variable.toString();

Lecture :
    JS : function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    }
        JSON.parse(getCookie('variable'))
    PHP: $profile_viewer_uid = $_COOKIE['variable'];


in_array(condition, tableau);

si condition est une valeur existante dans tableau, alors return true

Quand on utilise un else{}, on ne doit pas faire descendre l'accolade sur la ligne d'après, (ni d'un espace)

        else 
        { ?>
        <?php 
        }
    N'est pas valide!
*/

$_SESSION['position'] = "debut";

?>

<audio autoplay controls loop  style="display: none;">
  <source src="Twin_Saga.mp3" type="audio/mpeg">
</audio>

<?php

    // DEFINITION DES FONCTIONS

function generate_placement($first_place, $second_place, $nb_spacer){
    ?>
        <li class="spacer">&nbsp;</li>
        <li class="game game-top winner"><?php $first_place() ?> </li>

        <?php for($x = 0; $x < $nb_spacer; $x++){ ?>
            <li class="game game-spacer">&nbsp;</li>
        <?php } ?>
        
        <li class="game game-bottom "><?php $second_place() ?> </li>
        <li class="spacer">&nbsp;</li>
    <?php
}

function place() { 
    echo "<div id=\"div2\"></div>";} 
        
function place_enemy($x,$my_list) {

    if( !isset($_SESSION['ennemis'][$x]) ){
        $character = array_rand($my_list,1) ;
        $character = $my_list[$character];  

        $_SESSION['ennemis'][$x] = $character ;
        }
    else {
    $character = $_SESSION['ennemis'][$x];
    }
    #echo "Voici : ".$character;
    ?>
    <div id="div2">
    <img src=<?= $character.".png" ?> id="drag2" width="70" height="70" />
    </div>
<?php 
}


function place_for_character($i) { 
    ?>
    <div id="div3">
    <h1 style="color: rgb(160, 96, 0); text-align: center;"><?= $i ?></h1>
    </div>
    <?php                                                                      

} 

function affiche_liste_persos($liste) {

    foreach($liste as $key => $value){ 
        ?>
    <div class="container" ondragenter="return dragEnter(event)" ondrop="return dragDrop(event)" ondragover="return dragOver(event)">
    <!-- Le container peut reaccueillir un personnage, donc c'est un drag-enter aussi. -->
    <div id="transition-hover" >
        <div id="div1">
        <img src=<?= $liste[$key]->nom().".png" ?> width="70" height="70" draggable="true" ondragstart="return dragStart(event)" id=<?=$key?> />
        </div>   
        <div id="transition-hover-content" >
            <?php echo "<p class=\"infos_menu\">".$liste[$key]->nom().
            "<br/> pv: ".$liste[$key]->pv()."/".$liste[$key]->pvm().
            "<br/> atk: ".$liste[$key]->atk().
            "<br/> def: ".$liste[$key]->def().
            "<br/> elmt: ".$liste[$key]->type_elmt()."</p>" ; ?>
        </div>
    </div>
    </div> 
      <?php
    }
}


    // ACTIONS EN EN-TÊTE

if(!isset($_SESSION['personnages']) ){ 
    echo "<p> . . . </p>";
}
else if( !isset($_SESSION['characters']) ){
?>

    <!-- PRESENTATION DE LA PAGE -->

<h1>Tournament of the Thirty Choosen</h1>
<h3>Choisissez les personnages à placer aux 4 emplacements puis validez votre choix.</h3>

<form action="index.php" method="post" style="display:flex; margin-bottom:-15px;">
<div class="emplacement">
    <?php for($i = 0; $i < 4; $i++){ ?>
    <div class="perso" id="div3" ondragenter="return dragEnter(event)" ondrop="return dragDrop(event)" ondragover="return dragOver(event)"></div>
    <?php } ?>
</div>
<div class="liste">
<div id="sticky" >
<input type="submit" name="confirm" value="Valider" onclick="get_4_persos()" />  
&nbsp; &nbsp; &nbsp; &nbsp;
<input type="submit" name="change" value="Changer de Tournois" onclick="get_4_persos()" />
</div>
<?php affiche_liste_persos($_SESSION['personnages']); ?>
</div>
</form>

<?php } ?>


    <!-- INTERFACE DU TOURNOIS -->

<main id="tournament">
<ul class="round round-1">
<?php
    for($i = 1 ; $i <= 4 ; $i++)
    {         
        // **** PROBA DES ENNEMIS D'ÊTRE 4* ou 5* ! ****

        //echo "<p>Test ici : ".$_SESSION['nb_5_s']."</p>";

        $drop = rand(1,100);
        $critere = $_SESSION['nb_5_stars'] * 10;
        if($critere > 50){ $critere = 50;}
         
         if($drop <= $critere+1){ 
             global $liste_no_o_5_stars;
             $liste_use = $liste_no_o_5_stars;
         }
         else if($drop <= $critere*1.1 + 20 ){
            global $liste_no_o_4_stars;
            $liste_use = $liste_no_o_4_stars;
        }
        else { global $list_no_o ; 
            $liste_use = $list_no_o; }
        ?>
        <li class="spacer">&nbsp;</li>
        <li class="game game-top winner"><?php place_for_character($i) ?></li>
        <li class="game game-spacer">&nbsp;</li>
        <li class="game game-bottom "><?php place_enemy($i,$liste_use) ?> </li>
        <li class="spacer">&nbsp;</li>
<?php
    }
    ?>
</ul>

<ul class="round round-2">
    <?php
    for($i = 0 ; $i <= 1 ; $i++)
    { generate_placement("place","place",6); }  ?>
</ul>

<ul class="round round-3">
    <?php generate_placement("place","place",1); ?>
</ul>

<ul class="round round-4">
    <?php echo "<div id=\"div2\"></div>"; // place(); ?>
</ul>

</main>



<script type="text/javascript">

    // Drag & Drop

    function dragStart(ev) {
        ev.dataTransfer.effectAllowed='move';
        ev.dataTransfer.setData("Text", ev.target.getAttribute('id'));
        ev.dataTransfer.setDragImage(ev.target,0,0);
        return true;
    }
    function dragEnter(ev) {
        event.preventDefault();
        return true;
    }
    function dragOver(ev) {
        return false;
    }
    function dragDrop(ev) {
    
        event.preventDefault();

        var src = ev.dataTransfer.getData("Text");
        var drag_target = $('#'+src)[0]; // jQuery
        var tempo = document.createElement('span');
        tempo.className='hide';

        // On empêche de drop si le parent a un élément qui existe déjà!
        //console.log(ev.target.parentNode.childElementCount)
        if(ev.target.parentNode.childElementCount != 1){
            ev.target.appendChild(document.getElementById(src));
        }
        else{  // S'il y a déjà un élément, on INVERSE les 2
            event.target.before(tempo);
            drag_target.before(event.target)
            tempo.replaceWith(drag_target)

            //document.getElementById(src).appendChild(ev.target.parentNode.children)
            //ev.target.appendChild(document.getElementById(src));
        }
        ev.stopPropagation();
        return false;
    }
    


// Récupération des infos des persos envoyés en JS

/* Récupère les sous parties d'une div (child)
var parent = document.getElementById('parentDiv');
var childs = parent.childNodes;
console.log(childs); 
*/

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

// Initialisation

function load_perso(){

    // Si on veut conserver
    if(document.getElementById("conserver").checked) {

        console.log(getCookie("4_persos"));

        // on récupère ce qui a été préalablement enregistré
        var $ma_liste = JSON.parse(getCookie("4_persos"));

        var x = document.getElementsByClassName("perso");
        for(i=0; i < x.length; i++){
            //console.log($ma_liste[i])
            //console.log(document.getElementById($ma_liste[i]))
            x[i].appendChild(document.getElementById($ma_liste[i]));
        } 
    }
}

function isConserved(){
        // La case est cochée?
    if(document.getElementById("conserver").checked) {
        document.cookie = "conserver=checked";
    } else{ document.cookie = "conserver="; }

}

function get_4_persos(){

    isConserved();

    var i;
    var x = document.getElementsByClassName("perso");

    get4persos = [];
    
    console.log(x.length);

    for (i = 0; i < x.length; i++) {
        console.log(x[i]);

        var child = x[i].firstChild;

        // S'il y a bien un enfant
        /* Ce "if" est obligatoire, car s'il n'y a pas
           d'enfant et qu'on fait ces opérations, alors "TypeError"
           et le programme JS cesse
        */
        if(x[i].childNodes.length == 1){

        while(child && child.nodeType !== 1) {
            child = child.nextSibling;
        }
        console.log(child)
        console.log(child.id);

        get4persos.push(child.id); // en "i", on prend l'ID du personnage
        }
    }
    // Sauvegarde des variables
    if(get4persos.length == 4){
    //sessionStorage.setItem('4_persos', JSON.stringify(get4persos));
    document.cookie="4_persos=" + JSON.stringify(get4persos);
    } else{ document.cookie="4_persos=" ; }
}

// Conserver les 4 persos placés pour la prochaine fois ?

</script>



<?php   // AVANCEMENT DE LA PAGE

if(isset($_POST['change']) ){
    $_SESSION['ennemis'] = array();
}

if(isset($_POST['confirm'])){

    // Version 2.0, 15/07/2021 : Récupération de la variable JS encodé JSON par cookie
    $ma_liste = json_decode($_COOKIE['4_persos'],true);

    if( !is_array($ma_liste)){
        echo "<p class=\"erreur\">Problème : Une ou plusieurs places n'ont pas été attribuées!</p>";
    }
    else{
        ?>
        <p>Le jeu est prêt! Vous pouvez débuter</p>

        <?php   // Pour chaque données envoyée
        //foreach($_POST as $key => $value){
        foreach($ma_liste as $key => $value){
            $liste_definitive[$key+1] = $_SESSION['personnages'][$value];
        }
        $_SESSION['mes_persos'] = $liste_definitive;
        // On peut déclarer une variable de SESSION comme un TABLEAU en rajoutant []

        // Pour rafraîchir les persos de la Game d'avant
        
        unset($_SESSION['persos_totaux']);

        echo '<script language="Javascript">document.location.replace("game.php");</script>';
    }
}
?>

<!-- iframe pour pouvoir appeler le paramètre onload -->
<iframe onload="load_perso()" style="display:none" ></iframe>



<script type="text/javascript" language="javascript">

// bloquer le menu en haut
$(window).scroll(function() {    
    var scroll = $(window).scrollTop();

    /* Menu déroulant des 4 emplacements (au cas où on ait trop de persos)
    if (scroll > -50 && scroll < $(".liste").height()-300) {
        $(".emplacement").css("position","sticky");
    } else {
        $(".emplacement").css("position","relative");
    }
    */

    if(scroll > $(".liste").height()){
        $("#sticky").css("position","fixed");
        $("#sticky").css("left","20%");
    }
    else{ $("#sticky").css("position","relative"); 
        $("#sticky").css("left","0%"); 
    }
});
</script>

    </body>
</html>
