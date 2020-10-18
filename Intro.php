<?php

/*
Notions intéressantes :

On doit d'abord déclarer les CLASSES

et ENSUITE le session_start();

Sinon erreur de classe "incomplète"

Pour utiliser dans une fonction des variables qui sont déclarés ailleurs, il faut
soit les envoyer en paramètre, soit les déclarer en global dans la fonction!

Ex : Liste_Persos.php contient $liste_4_stars
Pour l'utiliser dans une fonction, il faudra écrire :

function ma_fonction(){
    global $liste_4stars;
    $truc = $liste_4_stars... ;
}

count() comme python pour connaître le nombre d'éléments d'un tableau, ex :
    $nb_personnages = count($_SESSION["personnages"]);

*/
    require("Perso.php"); // Avant le session_start() ! ! ! !
    require("header.php");

    session_start();

    include("Liste_Persos.php");

    function remove_element($array,$value) {
        foreach (array_keys($array, $value) as $key) {
           unset($array[$key]);
        }  
         return $array;
       }
    function invocation($x) {

        //$liste_restante = $my_list ;
        // Possibilité de doublon remise
        ?>

        <div class="summon"> 
            <h3> Voici la liste des personnages que vous avez obtenus! </h3>
        <?php
        
        for($i = 1; $i <= $x; $i++){ 
                
            // Choix aléatoires des listes selon la rareté!
            if(rand(1,100) <= 9){
                global $liste_4_stars; 
            // global A DECLARER POUR UTILISER LES VARIABLES de Liste_Persos.php
            // Car se sont des fonctions 
                $liste_use = $liste_4_stars;
            }
            else { global $list; $liste_use = $list; }

            $character = array_rand($liste_use,1) ;
            /*
            echo"<br/>";
            echo "J'ai eu :".$liste_use[$character]."<br/>";
            print_r($liste_use);
            echo "<br/>";*/

            //$liste_restante = remove_element($liste_restante,$my_list[$character]);
    
            $character .= ".png" ;
    
            $ma_liste[$i] = $character ;
            ?>
            <img src=<?= $character ?> id="drag1" width="140" height="140" /><!-- draggable="true" ondragstart="drag(event)" -->
    
          <?php                     } ?> 
        
        </div> 
        <?php 
         return $ma_liste;
        }


function invocation_creation($ma_liste_de_persos, $invoc){
    if(isset($_SESSION['personnages']))
    {
    $nb_personnages = count($_SESSION["personnages"]);
    } else { $nb_personnages = 0;}

for($i=1; $i<= $invoc; $i++){
    $ma_liste_de_persos[$i] = substr($ma_liste_de_persos[$i],0,-4);
    global $liste_complete_o;     
    $_SESSION["personnages"][$nb_personnages + $i] = new Perso($ma_liste_de_persos[$i],$liste_complete_o,0);
                            }
}

    // Beginnning

    if(!isset($_SESSION["personnages"]) ){      

        // * * * * * * * * Nombre d'invocations * * * * * * *
        $nb_persos_debut = 6;
        // * *  * * * * * * * * * * * * * * * * * * * * * * *
        
        $ma_liste_de_persos = invocation($nb_persos_debut);

        invocation_creation($ma_liste_de_persos,$nb_persos_debut);

        $_SESSION['argent'] = 2;
    }
    
    // Invocations

    if(isset($_GET['summon1']) || isset($_GET['summon10']) ){

        if($_GET['summon'] == 1 )
        { $requis = 2; $invoc = 1; } // Glitchable en changeant 1 par 10 par exemples
        else if($_GET['summon'] == 10 )
        { $requis = 18; $invoc = 10; }

            if($_SESSION['argent'] < $requis){
            echo "<p>Vous n'avez pas assez d'argent!<br/>";
            echo "En votre possession : ".$_SESSION['argent'].", requis : ".$requis;
            $invoc = 0;
            }
            else{   $_SESSION['argent'] -= $requis;}
        
            if(isset($invoc) && $invoc > 0){
                $persos_obtenus = invocation($invoc);
                invocation_creation($persos_obtenus,$invoc);
                }
            }



    ?>
    <br/>
    <div style="text-align : center;">
    <form action="index.php" method="post">
        <input type="submit" value="Valider"/>
    </form></div>
