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

require("Perso.php"); // Classe en tout tout premier

session_start(); // Avant tout code HTML mais après les classes

?>
 <audio autoplay controls loop  style="display: none;">
  <source src="Caelestinum_Genshin.ogg" type="audio/ogg">
  </audio>
<?php

if(isset($_SESSION['persos_totaux']) ){
    $persos_totaux =  $_SESSION['persos_totaux']; } 
    
function place_character($perso,$camp) {
    if($camp == 0)
    {
    show_details($perso,"div3");
    }
    else{
    show_details($perso/*[$round][$camp][$i]*/,"div2");  
    }
}   
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
    
require("header.php");
?>


<h1>Tournament of the Thirty Choosen</h1>

<form action="game.php" method="POST">
<?php

    // Si le tournoi n'a pas encore commencé !

if(!isset($_POST['play'])){    
    $mes_persos_o = array();
    $persos_ennemis_o = array();

    /*
    echo "persos :<br/>";
    print_r($_SESSION['mes_persos']);
    echo "ennemis :<br/>";
    print_r($_SESSION['ennemis']); */

    if(!isset($_SESSION['persos_totaux']) ) {            
      /* mes persos sont déjà définis en objet dans l'intro (car stats obligatoire), donc on en refait pas des objets

        foreach($_SESSION["mes_persos"] as $key => $value){
           $mes_persos_o[$key] = new Perso($value,$liste_complete,0);   } */
        $mes_persos_o = $_SESSION["mes_persos"] ;

        // Les ennemis obtiennent leurs stats maintenant, car sinon c'est trop facile de choisir où mettre ses persos!
        foreach($_SESSION["ennemis"] as $key => $value){    //Last value = $CAMP
            global $liste_complete_o;
            $persos_ennemis_o[$key] = new Perso($value,$liste_complete_o,1);  }

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
        $_SESSION['winSince'] = 2; // Par défaut, on considère gagné depuis round 1 (donc après round 0)
            
    }
    ?>
    <input type="submit" name="play" value="Commencer" />  
<?php 
}           
            // A partir du commencement du Tournoi !

else if($_POST['play'] == "Commencer"){

    $persos_totaux = combats_du_round(0, $_SESSION['persos_totaux']); // Round 0

    echo "<br/>Deuxième manche : <br/>";
     ?>
<input type="submit" name="play" value="Continuer" />  
<?php } 

else if($_POST['play'] == "Continuer"){
   
    $persos_totaux = combats_du_round(1, $_SESSION['persos_totaux']); // Round 1

    echo "<br/>Troisième manche : <br/>";
     ?>
<input type="submit" name="play" value="Finir" />  
<?php } ?>

</form>

<?php   // Fin du tournoi

if( isset($_POST['play']) && $_POST['play'] == "Finir"){ 
    
    $persos_totaux = combats_du_round(2, $_SESSION['persos_totaux']); // Round 2

    // L'index du gagnant doit quand même être [1], même s'il est tout seul, pas juste $persos[3]
                        // Parce que la fonction retourne toujours un double tableau [$round][$index] !
    echo "<br/> Le gagnant est : ".$persos_totaux[3][1]->nom()." dans le camp ".$persos_totaux[3][1]->camp();
    
    ?>
    <form action="index.php" method="post">
    <?php

    if($persos_totaux[3][1]->camp() == 0){ ?>
        <input type="submit" name="fin" value="Vous avez gagné!" />  

        <h4>Vous avez reçu <?= 2 + $_SESSION['winSince'] ; ?> d'argents!</h4>
    <?php $_SESSION['argent'] += 2 + $_SESSION['winSince'] ;
    }           // winSince correspond au nb de tour depuis lequel on a gagné, 0, 1 ou 2
    else { ?>
    <input type="submit" name="fin" value="Vous avez perdu!" />  
    <h4>Vous avez perdu 2 d'argents!</h4>
    <?php $_SESSION['argent'] -= 2;  
    }

unset($_SESSION['persos_totaux']);
unset($_SESSION['ennemis']);

// Heal des personnages
foreach($_SESSION['personnages'] as $key => $value){
    $_SESSION['personnages'][$key]->setPv($_SESSION['personnages'][$key]->pvm()); }
     ?>

    </form>
    <?php
}

?>

<main id="tournament">
<ul class="round round-1">

<?php

    for($i=1; $i<=8; $i+=2){
        ?>
        <li class="spacer">&nbsp;</li>
                                                        <?php //$this-> camp déjà connu ?>
        <li class="game game-top winner"><?php place_character($persos_totaux[0][$i],0); ?> </li>
        <li class="game game-spacer">&nbsp;</li>
        <li class="game game-bottom "><?php place_character($persos_totaux[0][$i+1],1); ?> </li>
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
        </li>
        
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
        </li>
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
        <!--<span>79</span>--></li>
        
        <li class="game game-spacer">&nbsp;</li>
            
        <li class="game game-bottom ">
        <?php
        if( isset($persos_totaux[2][2]) ){ 
            place_character($persos_totaux[2][2],$persos_totaux[2][2]->camp());
         } 
         else { 
            echo "<div id=\"div2\"></div>";
                 } ?>
        </li>

        <li class="spacer">&nbsp;</li>
</ul>
<ul class="round round-4">
        <?php         // L'index du gagnant doit quand même être [1], même s'il est tout seul, pas juste $persos[3]
                            // Parce que la fonction retourne toujours un double tableau [$round][$index] !
        if( isset($persos_totaux[3][1]) ){ 
            place_character($persos_totaux[3][1],$persos_totaux[3][1]->camp());
            } 
            else { 
            echo "<div id=\"div2\"></div>";
                    } ?>
</main>


<?php

function combat($perso1,$perso2){
                                                                                           // Avant :  /4 ! Importance PV réhaussée!
    $value_of_fight = ($perso1->atk() - $perso2->atk()) + ($perso1->def() - $perso2->def()) + ($perso1->pv() - $perso2->pv()) / 3;
    echo "<br/>Value of Fight :".$value_of_fight;
    //if($value_of_fight == 0){ // J'ai oublié le double égale! ALORS QUE LE IF EST USELESS ! 20m perdus
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
                        // Obliger d'envoyer les persos, car variable GLOBAL, eh oui, c'est une fonction!
function combats_du_round($round, $persos_totaux){
    $persos_totaux[$round + 1] = array(); // Au cas où, on vide l'array qu'on va remplir

    $challengers = 8 / ($round+1); // <-- car $round commence à 0 

    for($i=1; $i<= $challengers ; $i += 2){

        // RAPPEL : au round + 1, on a index 1,2,3,4 si on débute avec 8 valeurs,
            // Pour faire ça, sachant que $i varie de 2 en 2, on doit faire [($i+1) / 2] <-- $persos_totaux[$round+1][]
            // Donnant donc 1+1 / 2, puis 3+1 / 2, etc... Donc 1,2,3,4 en sortie de 1,3,5,7 !

                // Vérification des 2 camps
                
        if($persos_totaux[$round][$i]->camp() != $persos_totaux[$round][$i+1]->camp() ){
            $camp = combat($persos_totaux[$round][$i],$persos_totaux[$round][$i+1]);
            $persos_totaux[$round+1][($i+1)/2] = $persos_totaux[$round][$i+$camp];     

            $_SESSION['winSince'] = 2 - $round; // 2 - 0, puis 2-1 et sinon 2-2

        }                                        // Rappel, "+$camp" est fixe si c'est "0" ou "1" 
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
    $_SESSION['persos_totaux'] = $persos_totaux; // Re-enregistré 

    return $persos_totaux; // Comme ça on utilise $persos_totaux dehors si on veut
}

    // Avant d'utiliser combats_du_round() on avait 415 lignes
?>

</body>

</html>
