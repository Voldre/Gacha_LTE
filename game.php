<?php

/*
Notions intéressantes :

Idée très cool :
    liste les personnages (objets) dans un tableau!
    Comme ça j'ai le tableau[ round ] [ camp ] et à l'intérieur tous les persos rangés!

Utiliser une liste dans une classe? L'envoyer à la classe par le constructeur.

Afficher une image dynamiquement (qui scroll):    
        body {
        background-image: url("BlueSpace.png");
        background-position: top left;
        animation: mymove 50s infinite;
        }
    @keyframes mymove {
        100% { background-position: bottom right; }

J'ai cherché 30 MINUTES une erreur d'un return de fonction, tout ça juste parce que je ne
sais pas écrire correctement un if!  if($value_of_fight = 0){ while $value_of_fight == 0}
    Là-dessus j'ai été débile! SURTOUT qu'il ne sert à rien, car le while() fait tout!

    Vider un sous-tableau en PHP? Facile : $persos_totaux[1] = array(), ne videra pas [0].

    Effacer un tableau pour que isset() ne marche pas?  unset($variable) ou $tableau

    Pour utiliser des index différentes, exemple : perso 1,3,5,7 = ally, 2,4,6,8 = enemy
    avec 1,2,3,4 = position du round+1
        Alors il faut définir un index de ref, ex : 1,3,5,7 et jouer dessus:
        ally = $i+1
        $position_round = ($i+1)/2  : soit (1+1)/2 (1+3)/2 (1+5/2), ... donc 1,2,3,4


*/

    $liste_complete = array("Aria"=>[2,-2,"terre"],"Ether"=>[-1,2,"terre"],"Hearth"=>[2,-1,"glace"],
    "Kuro"=>[-1,2,"foudre"],"Kzina"=>[2,-1,"tenebre"],"Silarius"=>[3,-2,"tenebre"],
    "Velrod"=>[5,2,"foudre"],"Yune"=>[1,0,"vent"],"Zelcia"=>[3,-2,"feu"],
    "Zerito"=>[2,-1,"vent"]);

require("Perso.php");

session_start();    // Toujours en premier


if(isset($_SESSION['persos_totaux']) ){
    $persos_totaux =  $_SESSION['persos_totaux'];

}

function place() { ?>
    <div id="div2"></div>
    <?php } 
    
function place_character($perso,$camp) {
    if($camp == 0)
    {
    show_details($perso,"div3");
    }
    else{
    show_details($perso/*[$round][$camp][$i]*/,"div2");  
    }
    ?>
    <!--
    <div id="div2">
    <img src= ?= //$character ?> id="drag2" width="70" height="70" /> -->
    <?php }    
?>
    

<!DOCTYPE HTML>
<html>
<head>

<meta charset="utf-8" />

<link rel="stylesheet" href="mon_style.css"/>
</head>

<body>

<h1>Tournament of the Thirty Choosen</h1>



<form action="game.php" method="POST">
<?php
if(!isset($_POST['play'])){    
    $mes_persos_o = array();
    $persos_ennemis_o = array();
    /*
    echo "persos :<br/>";
    print_r($_SESSION['mes_persos']);
    echo "ennemis :<br/>";
    print_r($_SESSION['ennemis']);*/

    // A vérifier si le if() est bien à mettre comme ça :
    if(!isset($_SESSION['persos_totaux']) ) {
        foreach($_SESSION["mes_persos"] as $key => $value){
            $mes_persos_o[$key] = new Perso($value,$liste_complete,0);   }
        foreach($_SESSION["ennemis"] as $key => $value){    //Last value = $CAMP
            $persos_ennemis_o[$key] = new Perso($value,$liste_complete,1);  }

        for($i=1; $i<=8; $i = $i+2){
        /*  $persos_totaux[0][0][$i] = $mes_persos_o[$i];
            $persos_totaux[0][1][$i] = $persos_ennemis_o[$i]; 

            C'est FINI d'avoir un tableau "Camp", parce que pour retrouver
            Si c'est l'index 0 ou 1, c'est chiant! Trop de isset()? ? ?
        Maintenant : */
                                        // Récupérer l'index 1,2,3,4 de la liste 1,3,5,7
                                                // x+1/2 : 3->2, 5->3, 7->4
            $persos_totaux[0][$i] = $mes_persos_o[($i+1)/2];
            $persos_totaux[0][$i+1] = $persos_ennemis_o[($i+1)/2]; 

            // 1 vs 2, 3 vs 4, 5 vs 6, 7 vs 8.

        }
        $_SESSION['persos_totaux'] = $persos_totaux;

    }
    ?>
<input type="submit" name="play" value="Commencer" />  

<?php } 

else if($_POST['play'] == "Commencer"){
    $round = 0;
    $persos_totaux[1] = array(); // Super important car elle est pas effacé entre chaque game!
    for($i=1; $i<=7; $i+=2){

        $camp = combat($persos_totaux[$round][$i],$persos_totaux[$round][$i+1]);
        $persos_totaux[$round+1][($i+1)/2] = $persos_totaux[$round][$i+$camp]; 
            // Pareil, array 1,2,3,4  issu d'un array 1,3,5,7

                            // $i+ $camp permet de faire "+1" si "ennemi"
       // Cela ne nécessite même pas de redéfinir la fonction combat() car elle renvoie 0 ou 1!
        }
    
    $_SESSION['persos_totaux'] = $persos_totaux; // Re-enregistré

    echo "<br/>Deuxième manche : <br/>";
    //print_r($persos_totaux[1]);
     ?>
<input type="submit" name="play" value="Continuer" />  
<?php } 



else if($_POST['play'] == "Continuer"){
    $round = 1;
    $persos_totaux[2] = array();
    
    for($i=1; $i<=3; $i += 2){
                // Vérification des 2 camps
        if($persos_totaux[$round][$i]->camp() != $persos_totaux[$round][$i+1]->camp() ){
            $camp = combat($persos_totaux[$round][$i],$persos_totaux[$round][$i+1]);
            $persos_totaux[$round+1][($i+1)/2] = $persos_totaux[$round][$i+$camp];  
        }                                        // Rappel, "+$camp" fixe si c'est "0" ou "1" 
        else{                                // Donc si c'est nous (0) ou l'ennemi (1)
            $persos_totaux[$round+1][($i+1)/2] = $persos_totaux[$round][rand($i,$i+1)];
                                                // Si même camp, choix random
                // A changer plus tard, si "nous", on peut choisir?
                // Ou par défaut celui avec le plus de PV? Sinon random?

            $value = $persos_totaux[$round+1][($i+1)/2]->pv() + 5;
            $persos_totaux[$round+1][($i+1)/2]->setPv($value) ;
                // Récupération de PV car pas de combat.
            }
        }

    echo "ICI là :<br/>";
    print_r($persos_totaux[2]);

    $_SESSION['persos_totaux'] = $persos_totaux; // Re-enregistré

    echo "<br/>Troisième manche : <br/>";
     ?>
<input type="submit" name="play" value="Finir" />  
<?php 
} 
?>
</form>

<?php
if( isset($_POST['play']) && $_POST['play'] == "Finir"){ 
    $round = 2;
    $persos_totaux[3] = array();
                    // Vérification des 2 camps
        if($persos_totaux[$round][1]->camp() != $persos_totaux[$round][2]->camp() ){
            $camp = combat($persos_totaux[$round][1],$persos_totaux[$round][2]);
            $persos_totaux[$round+1] = $persos_totaux[$round][1+$camp];  
        }                                
        else{                          
            $persos_totaux[$round+1] = $persos_totaux[$round][rand(1,2)];
            $value = $persos_totaux[$round+1]->pv() + 5;
            $persos_totaux[$round+1]->setPv($value) ;
            }
    echo "<br/> Le gagnant est : ".$persos_totaux[3]->nom()." dans le camp ".$persos_totaux[3]->camp();

    $_SESSION['persos_totaux'][3] = $persos_totaux[3]; // Re-enregistré
    
    ?>
    <form action="index.php" method="post">
    <?php
    if($persos_totaux[3]->camp() == 0){ ?>
        <input type="submit" name="fin" value="Vous avez gagné!" />  
    <?php }else { ?>
        <input type="submit" name="fin" value="Vous avez perdu!" />  
    <?php }
     ?>
    </form>
<?php
} ?>

<main id="tournament">
<ul class="round round-1">

<?php

    for($i=1; $i<=8; $i+=2){
        ?>
        <li class="spacer">&nbsp;</li>
                                                        <?php //$this-> camp déjà connu ?>
        <li class="game game-top winner"><?php place_character($persos_totaux[0][$i],0); ?> <span>79</span></li>
        <li class="game game-spacer">&nbsp;</li>
        <li class="game game-bottom "><?php place_character($persos_totaux[0][$i+1],1); ?> <span>48</span></li>
        <li class="spacer">&nbsp;</li>
                    <?php } ?>
    <li class="spacer">&nbsp;</li>
</ul>

<ul class="round round-2">
    <?php
    /*   ! ! ! PLUS NECESSAIRE CAR LE CAMP EST STOCKE DANS LE PERSO ! ! !

    for($i = 1 ; $i <= 3 ; $i= $i+2)
    {
        if(isset($_POST['play']) && $_POST['play'] == "Commencer"){ 
            if(!isset($persos_totaux[1][0][$i]) ){
                $camp1 = 1;}
                else{ $camp1 = 0;}
            if(!isset($persos_totaux[1][0][$i+1]) ){
                $camp2 = 1;}
                else{ $camp2 = 0;}                               
                                    // index 1 et 2
            if($camp1 == $camp2){ $no_fight[(1+$i)/2] = true; }
    } */
    
    for($i = 1 ; $i <= 3 ; $i= $i+2)
    {
        ?>
        <li class="spacer">&nbsp;</li>        
        <li class="spacer">&nbsp;</li>        
        
        <li class="game game-top winner">
        <?php
        if( isset($persos_totaux[1][$i]) ){ 
            place_character($persos_totaux[1][$i],$persos_totaux[1][$i]->camp());
         } 
        else{ 
            echo "<div id=\"div2\"></div>";
                 } ?>
        <span>79</span></li>
        
        <li class="game game-spacer">&nbsp;</li>
        <li class="game game-spacer">&nbsp;</li>
        <li class="game game-spacer">&nbsp;</li>
        <li class="game game-spacer">&nbsp;</li>
        <li class="game game-spacer">&nbsp;</li>

        <li class="game game-bottom ">
        <?php
        if( isset($persos_totaux[1][$i+1]) ){ 
            place_character($persos_totaux[1][$i+1],$persos_totaux[1][$i+1]->camp());
         } 
        else{ 
            echo "<div id=\"div2\"></div>";
                 } ?>
        <span>48</span></li>
        <li class="spacer">&nbsp;</li>
<?php
    }
    ?>

    <li class="spacer">&nbsp;</li>
</ul>
<ul class="round round-3">
<?php
        ?>
        <li class="spacer">&nbsp;</li>
        
        <li class="game game-top winner">
        <?php
        if( isset($persos_totaux[2][1]) ){ 
            place_character($persos_totaux[2][1],$persos_totaux[2][1]->camp());
         } 
         else { 
            echo "<div id=\"div2\"></div>";
                 } ?>
        <span>79</span></li>
        
        <li class="game game-spacer">&nbsp;</li>
            
        <li class="game game-bottom ">
        <?php
        if( isset($persos_totaux[2][2]) ){ 
            place_character($persos_totaux[2][2],$persos_totaux[2][2]->camp());
         } 
         else { 
            echo "<div id=\"div2\"></div>";
                 } ?>
        <span>48</span></li>

        <li class="spacer">&nbsp;</li>
</ul>
<ul class="round round-4">
        <?php
        if( isset($persos_totaux[3]) ){ 
            place_character($persos_totaux[3],$persos_totaux[3]->camp());
            } 
            else { 
            echo "<div id=\"div2\"></div>";
                    } ?>
</main>


<?php
function show_details($mon_perso,$ma_div)
{ ?>
<!-- TRANSITION & HOVER -->
<div class="container">
    <div id="transition-hover">
        <span>
    <div id=<?= $ma_div ?> ondrop="drop(event)" ondragover="allowDrop(event)">
    <img src=<?= $mon_perso->nom().".png" ?> id="drag3" width="70" height="70" ?>
        </span>
        <div id="transition-hover-content">
            <?php echo "<p class=\"infos\">".$mon_perso->nom().
            "<br/> pv: ".$mon_perso->pv()."/".$mon_perso->pvm().
            "<br/> atk: ".$mon_perso->atk().
            "<br/> def: ".$mon_perso->def()."</p>" ; ?>
        </div>
    </div>
</div>
<?php } 


function combat($perso1,$perso2){

    $value_of_fight = ($perso1->atk() - $perso2->atk()) + ($perso1->def() - $perso2->def()) + ($perso1->pv() - $perso2->pv())/4;
    echo "<br/>Value of Fight :".$value_of_fight;
    //if($value_of_fight == 0){ // J'ai oublié le double égale!
    while($value_of_fight == 0){
        $value_of_fight = rand(-10,10);
    } //}

    if($value_of_fight > 0){
        $value = $perso1->pv() - ($perso2->atk() - $perso1->def() ) *(1 + $perso2->atk() / $perso1->atk() );
        $perso1->setPv($value);
        $perso2->setPv(0);
        return 0;
    }
    else if($value_of_fight < 0){
        $value = $perso2->pv() - ($perso1->atk() - $perso2->def() ) *(1 + $perso1->atk() / $perso2->atk() );
        $perso2->setPv($value);
        $perso1->setPv(0);
        
        return 1;
    }

}



 require("Menu.php") 
?>

</body>
</html>
