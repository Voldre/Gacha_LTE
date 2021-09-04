<?php

/*
Rééquilibrage de la balance des dégâts et soins.

Avant :
- L'ATK n'avait pas plus d'utilité pour gagner que la DEF
    Pour gagner, 1 ATK = 1 DE
- La DEF était moins forte que l'ATK
    L'ennemi recevait des dégâts à hauteur de "ATK_J2 - DEF_J1 /2"
    Donc si faut choisir une stat, l'ATK était plus utile pour les dégâts
- Que l'on gagne au raz des paquerettes ou de loin, ça n'avait pas d'importance
- Les soins étaient constants (5PV)

Maintenant :
- L'ATK à une utilité : Gagner le round. Car la DEF ne rapporte que 0.9 points
- La DEF à aussi une utilité : Subir moins de dégâts en cas de victoire
    Maintenant c'est "ATK_J2 - DEF_J1 * 0.66", l'utilité est réhaussé de 16%
- Value of Fight influe sur les dégâts subis à l'issu du combat
    Si le match était serré, c'est jusqu'à -3 PV qui s'ajoute. #RP selon difficulté
- Les soins varient selon les PV Max (*0.29), ils varient donc de 4 PV à 6 PV

- Les PV influent un peu plus qu'avant (40% des PV en points, contre 33% avant)
    Ainsi, avoir une bonne défense devient un peu plus important
*/

$ratio_impact_PV = 2.5 ; // Les PV sont 2.5 fois moins important  (*0.4), avant c'était 3
$ratio_soins_PVM = 0.29; // En cas de repos (car allié), soins à hauteur de 29%
                         // des PV Max. Ainsi, de 14 à 22 PV on est à ~ +4 +6 PV
$ratio_impact_VoF= 3; // Value of Fight peut infliger jusqu'à -3 PV si trop faible

/*
Notions intéressantes :

Idée très cool :
    liste les personnages (objets) dans un tableau!
    Comme ça j'ai le tableau[ round ] et à l'intérieur tous les persos rangés!

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
    Là-dessus j'ai été débile! SURTOUT qu'il ne sert à rien, car le while() fait tout! Mais on n'oublie pas un DOUBLE EGALE "==" !

    Vider un sous-tableau en PHP? Facile : $persos_totaux[1] = array(), ne videra pas [0].

    Effacer un tableau pour que isset() ne marche pas?  unset($variable) ou $tableau

    Pour utiliser des index différentes, exemple : perso 1,3,5,7 = ally, 2,4,6,8 = enemy
    avec 1,2,3,4 = position du round+1
        Alors il faut définir un index de ref, ex : 1,3,5,7 et jouer dessus:
        ally = $i+1
        $position_round = ($i+1)/2  : soit (1+1)/2 f+1)/2 (5+1)/2, ... donc 1,2,3,4

*/

require("Perso.php"); // Classe en tout tout premier
include("../Database.php");
session_start(); // Avant tout code HTML mais après les classes

?>
 <audio autoplay controls loop  style="display: none;">
  <source src="Caelestinum_Genshin.ogg" type="audio/ogg">
  </audio>
<?php

if(isset($_SESSION['persos_totaux']) ){
    $persos_totaux =  $_SESSION['persos_totaux']; } 
  
    

function generate_placement_tournament($array,$round,$i,$nb_spacer){
    ?>
    <li class="spacer">&nbsp;</li>  
    <li class="game game-top winner">
    <?php
    if( isset($array[$round]) ){ 
        place_character($array[$round][$i],$array[$round][$i]->camp());
     } else { echo "<div id=\"div2\"></div>"; } ?></li>
    

    <?php for($x = 0; $x < $nb_spacer; $x++){ ?>
        <li class="game game-spacer">&nbsp;</li>
    <?php } ?>
        
    
    <li class="game game-bottom ">
    <?php
    if( isset($array[$round]) ){ 
        place_character($array[$round][$i+1],$array[$round][$i+1]->camp());
     } else { echo "<div id=\"div2\"></div>"; } ?></li>
    <li class="spacer">&nbsp;</li>
    <?php 
}

function place_character($perso,$camp) {
    if($camp == 0)
    {
    show_details($perso,"div3");
    }
    else{
    show_details($perso/*[$round][$camp][$i]*/,"div2");  
    }
}   

function show_details($mon_perso,$ma_div){ ?>
<!-- TRANSITION & HOVER -->
<div class="container">
    <div id="transition-hover">
        <span>
    <div id=<?= $ma_div ?> >
    <img src=<?= $mon_perso->nom().".png" ?> width="70" height="70" ?>
        </span>
        <div id="transition-hover-content">
            <?php echo "<p class=\"infos\">".$mon_perso->nom().
            "<br/> pv: ".$mon_perso->pv()."/".$mon_perso->pvm().
            "<br/> atk: ".$mon_perso->atk().
            "<br/> def: ".$mon_perso->def().
            "<br/> elmt: ".$mon_perso->type_elmt()."</p>" ; ?>
        </div>
    </div>
</div>
<?php } ?>


<!-- Ajout unique sur game.php : On cache le menu car superflux sur mobile ! -->
<div class="no_mobile">
<?php
require("header.php");
?>
</div>

<h1 class="title">Tournament of <br/>the Thirty Choosen</h1>

<form action="game.php" method="POST" style="line-height:100%;">
<?php

    // Si le tournoi n'a pas encore commencé

if(!isset($_POST['play'])){    
    $mes_persos_o = array();
    $persos_ennemis_o = array();
    
    echo "<p class=\"manche\">Première manche :</p>";

    if(!isset($_SESSION['persos_totaux']) ) {            
      /* mes persos sont déjà définis en objet dans l'intro (car stats obligatoire), donc on en refait pas des objets
        foreach($_SESSION["mes_persos"] as $key => $value){
           $mes_persos_o[$key] = new Perso($value,$liste_complete,0);   } */
        $mes_persos_o = $_SESSION["mes_persos"] ;

        // Les ennemis obtiennent leurs stats que maintenant, car sinon c'est trop facile de choisir où mettre ses persos
        foreach($_SESSION["ennemis"] as $key => $value){    //Last value = $CAMP
            global $liste_complete_o;
            $persos_ennemis_o[$key] = new Perso($value,$liste_complete_o,1);   
        }

        for($i=1; $i<=8; $i = $i+2){ // persos_totaux 1 à 8 issu de 2 tableaux de 4 elmts
        /*  C'est FINI d'avoir un tableau "Camp" pour retrouver si c'est "0" ou "1"
        Maintenant :  On obtient l'index 1,2,3,4 via la liste x = {1,3,5,7}
                        par cette formule : (x+1 /2), car => (1+1 /2) ->1, 3->2, 5->3, 7->4 */
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
        echo "<p class=\"manche\">Deuxième manche :</p>";
        $persos_totaux = combats_du_round(0, $_SESSION['persos_totaux']); // Round 0 ?>
        <input type="submit" name="play" value="Continuer" />  
    <?php } 

    else if($_POST['play'] == "Continuer"){
        echo "<p class=\"manche\">Troisième manche :</p>";
        $persos_totaux = combats_du_round(1, $_SESSION['persos_totaux']); // Round 1 ?>
        <input type="submit" name="play" value=" Finir " />  
    <?php }   
    
    else if($_POST['play'] == " Finir "){ 
        $persos_totaux = combats_du_round(2, $_SESSION['persos_totaux']); // Round 2

        // L'index du gagnant doit quand même être présent (càd [3][1]), même s'il est tout seul, pas juste $persos[3]
                        // Parce que la fonction combats_du_round() retourne toujours un double tableau [$round][$index] !
        echo "<p>Le gagnant est : ".$persos_totaux[3][1]->nom()." avec <b>".$persos_totaux[3][1]->pv()." PV</b> restant(s)</p>";        
    ?>
</form>
        <!-- Fin du tournois -->

        <form action="index.php" method="post">
        <?php

        if($persos_totaux[3][1]->camp() == 0){ ?>
            <input type="submit" name="fin" value="Vous avez gagné !" class="win" />  

            <h4>Vous avez reçu <?= 2 + $_SESSION['winSince'] ; ?> d'argents!</h4>
            <?php $_SESSION['argent'] += 2 + $_SESSION['winSince'] ;
        }           // winSince correspond au nb de tour depuis lequel on a gagné, 0, 1 ou 2
        else { ?>
            <input type="submit" name="fin" value="Vous avez perdu !" class="lose" />  
            <h4>Vous avez perdu 2 d'argents!</h4>
            <?php $_SESSION['argent'] -= 2;  
        }

        unset($_SESSION['persos_totaux']);
        unset($_SESSION['ennemis']);

        // Heal des personnages
        foreach($_SESSION['personnages'] as $key => $value){
            $_SESSION['personnages'][$key]->setPv($_SESSION['personnages'][$key]->pvm()); 
        } ?>
        </form>
    <?php } ?>


    <!-- AFFICHAGE DU TOURNOIS -->

<main id="tournament">
<ul style="margin:0px;"class="round round-1">
    <?php
    for($i=1; $i<=8; $i+=2){
    generate_placement_tournament($persos_totaux,0,$i,1); } ?>
    <li class="spacer">&nbsp;</li>
</ul>

<ul class="round round-2">
    <?php 
    for($i = 1 ; $i <= 3 ; $i= $i+2){ 
    generate_placement_tournament($persos_totaux,1,$i,12); } ?>
    <li class="spacer">&nbsp;</li>
</ul>

<ul class="round round-3">
    <?php                  // tableau, round, index, nb spacer
    generate_placement_tournament($persos_totaux,2,1,1);
    ?>
</ul>

<ul class="round round-4">
    <?php  // L'index du gagnant doit quand même être [1], même s'il est tout seul, pas juste $persos[3]
            // Parce que la fonction retourne toujours un double tableau [$round][$index] !
    if( isset($persos_totaux[3][1]) ){ 
        place_character($persos_totaux[3][1],$persos_totaux[3][1]->camp());
    } else { echo "<div id=\"div2\"></div>"; } ?>
</main>



<?php   

function AvElem($elmtP1, $elmtP2){
    switch($elmtP1){
        case "feu" :
            return choice("glace","tenebres",$elmtP2);
            break;
        case "glace" :
            return choice("terre","lumiere",$elmtP2);
            break;
        case "foudre":
            return choice("glace","physique",$elmtP2);
            break;
        case "terre":
            return choice("foudre","feu",$elmtP2);
            break;
        case "vent":
            return choice("feu","lumiere",$elmtP2);
            break;
        case "physique":
            return choice("vent","terre",$elmtP2);
            break;
        case "tenebres":
            return choice("physique","vent",$elmtP2);
            break;
        case "lumiere":
            return choice("tenebres","foudre",$elmtP2);
            break;
    }
}
function choice($elmt1,$elmt2,$elmtP2){
    if($elmtP2 == $elmt1 OR $elmtP2 == $elmt2){
        // 2 est la valeur choisi arbitrairement comme bonus pour le $value_of_fight
        return 2;
    }
    else { return 0; }
}

function combat($perso1,$perso2){

    global $ratio_impact_PV;
    global $ratio_impact_VoF; 

    $bonus1sur2 = AvElem($perso1->type_elmt(),$perso2->type_elmt());
    $bonus2sur1 = AvElem($perso2->type_elmt(),$perso1->type_elmt());
    // On vérifie l'avantage des 2 côtés, cela revient à regarder les av et desav de l'un ou de l'autre.
    // La fonction est 2 fois plus courte, et nécessite simplement 2 appels
    // On regarde la quantité bonus apporté des 2 côtés, l'un valant 0 et l'autre peut être quelque chose.
    $bonus = $bonus1sur2 - $bonus2sur1 ;

                                                                                           // Avant :  /3 ! Importance des PV réhaussée!
    $value_of_fight = ($perso1->atk() - $perso2->atk()) + ($perso1->def() - $perso2->def())*0.9 + ($perso1->pv() - $perso2->pv()) / $ratio_impact_PV;
    $value_of_fight += $bonus;
    // echo "<br/>Value of Fight :".$value_of_fight;
    //if($value_of_fight == 0){ // J'ai oublié le double égale! ALORS QUE LE IF EST USELESS ! 30m perdus
    while($value_of_fight == 0){
        $value_of_fight = rand(-1,1); } //}

    if($value_of_fight > 0){
        if($value_of_fight < 1){ $value_of_fight = 1; } 
        $value = $perso1->pv() - ($perso2->atk() - $perso1->def()*0.66) - abs($ratio_impact_VoF/$value_of_fight);
        if($perso1->pv() == 0){ $value = 1;}
        $perso1->setPv($value);
        $perso2->setPv(0);
        return 0;
    }
    else if($value_of_fight < 0){
        if($value_of_fight > -1){ $value_of_fight = -1; }
        $value = $perso2->pv() - ($perso1->atk() - $perso2->def()*0.66) - abs($ratio_impact_VoF/$value_of_fight);
        if($perso2->pv() == 0){ $value = 1;}
        $perso2->setPv($value);
        $perso1->setPv(0);     
        return 1;
    }
}
                        // Obligé d'envoyer les persos, car variable GLOBAL, eh oui, c'est une fonction!
function combats_du_round($round, $persos_totaux){
    
    global $ratio_soins_PVM; 

    $persos_totaux[$round + 1] = array(); // Au cas où, on vide l'array qu'on va remplir

                    // 8 car au round 0 y a 8 persos, mais count() automatise le tout
    $challengers = count($persos_totaux[0]) / ($round+1); // <-- car $round commence à 0 

    for($i=1; $i<= $challengers ; $i += 2){

        // RAPPEL : au round + 1, on a index 1,2,3,4 si on débute avec 8 valeurs,
            // Pour faire ça, sachant que $i varie de 2 en 2, on doit faire [($i+1) / 2] <-- $persos_totaux[$round+1][]
            // Donnant donc 1+1 / 2, puis 3+1 / 2, etc... Donc 1,2,3,4 en sortie de 1,3,5,7 !

                // Vérification des 2 camps
                
        if($persos_totaux[$round][$i]->camp() != $persos_totaux[$round][$i+1]->camp() ){
            $camp = combat($persos_totaux[$round][$i],$persos_totaux[$round][$i+1]);
            $persos_totaux[$round+1][($i+1)/2] = $persos_totaux[$round][$i+$camp];     
                                                            // Rappel, "+$camp" est fixe si c'est "0" ou "1" 
                                                                // Donc si c'est nous (0) ou l'ennemi (1)
            $_SESSION['winSince'] = 2 - $round; // 2 - 0, puis 2-1 et sinon 2-2
        }                                      
        else{                                
            $persos_totaux[$round+1][($i+1)/2] = $persos_totaux[$round][rand($i,$i+1)];
                                                    // Si même camp, choix random

                // A changer plus tard, si "nous", on peut choisir?
                // Ou par défaut celui avec le plus de PV? Sinon random?
                // Ou le plus fort, avec $value_of_fight?

            // SOINS car repos
            $value = $persos_totaux[$round+1][($i+1)/2]->pv() + $ratio_soins_PVM * $persos_totaux[$round+1][($i+1)/2]->pvm();
            $persos_totaux[$round+1][($i+1)/2]->setPv($value) ; // ->setPv($this->pv()+5)
        }
    }
    $_SESSION['persos_totaux'] = $persos_totaux; // Re-enregistré 

    return $persos_totaux; // Comme ça on utilise $persos_totaux dehors si on veut
}
?>

</body>

</html>
