<?php

/*
Notions intéressantes :

On doit d'abord déclarer les CLASSES
et ENSUITE le session_start();

    Sinon erreur de classe "incomplète"

Pour utiliser dans une fonction des variables qui sont déclarés ailleurs, il faut
soit les envoyer en paramètres, soit les déclarer en global dans la fonction!

    Ex : Liste_Persos.php contient $liste_4_stars
    Pour l'utiliser dans une fonction, il faudra écrire :

    function ma_fonction(){
        global $liste_4stars;
        $truc = $liste_4_stars... ;
    }

count() comme python pour connaître le nombre d'éléments d'un tableau, ex :
    $nb_personnages = count($_SESSION["personnages"]);


Lorsqu'on supprime un personnage (donc un array random d'une liste),
il faut penser à renuméroter correctement les array().

    Parce que la variable session personnage va toujours compter le nombre de persos et faire +1 à l'index.

    S'il y a 6 persos, et on supprime le 2e. Alors on aura 0,2,3,4,5. Il y a un trou, l'index 1 n'est plus là.
    Il reste 5 persos, donc le nouveau "6eme" aura l'index 5, et il écrasera l'ancien "6e".
    Du coup, il faut bien penser à renuméroter pour que count() renvoie sur le dernier vrai index!


Situation non prévue : Essayer d'aller au menu "délivrer un personnage" au milieu du combat !

    C'est une solution intéressante pour éviter de perdre 2 d'argents si on sait qu'on va lose.
    MAIS, involontairement, j'ai rendue cette pratique défavorable, car si tu quittes un tournoi
    précipitamment (donc que tu triches), eh bien tes personnnages ne seront pas soignés!
    Autrement dit, tricher pour ne pas perdre 2 argents <=> Ne pas être soigné, donc persos inutilisables
    jusqu'à la fin du prochain tournoi achevé ! 😉

*/

    require("Perso.php"); // Avant le session_start() ! ! ! !

    session_start();

    require("header.php");

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
        
        for($i = 1; $i <= $x; $i++)
        {         
            // **** PROBA DU JOUEUR DE TIRER UN 4* OU 5* ! ****
            // Choix aléatoire des listes selon la rareté!
            $drop = rand(1,100);
            if($drop == 1){
                global $liste_5_stars;
                $liste_use = $liste_5_stars;
            }
            else if($drop <= 10){
                global $liste_4_stars; 
            // global A DECLARER POUR UTILISER LES VARIABLES de Liste_Persos.php
            // Car on utilise des variables globales dans une fonction (ici invocation() ).
                $liste_use = $liste_4_stars;
            }
            else { global $list; $liste_use = $list; }

            $character = array_rand($liste_use,1) ;
    
            $ma_liste[$i] = $character ;
            ?>
            <img src=<?= $character.".png" ?> id="drag1" width="140" height="140" /><!-- draggable="true" ondragstart="drag(event)" -->
        <?php                     
        } ?>         
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
    global $liste_complete_o;                                                             // camp = joueur (donc 0)
    $_SESSION["personnages"][$nb_personnages + $i] = new Perso($ma_liste_de_persos[$i],$liste_complete_o,0);
                            }
}

    // Beginnning

    if(!isset($_SESSION["personnages"]) ){      

        // * * * * * * * * Nombre d'invocations * * * * * * *
        $nb_persos_debut = 6;
        // * *  * * * * * * * * * * * * * * * * * * * * * * *
        
        $ma_liste_de_persos = invocation($nb_persos_debut);

        invocation_creation($ma_liste_de_persos,$nb_persos_debut); // On crée les persos avec leurs stats random

        $_SESSION['argent'] = 2;
    }
    
    // Invocations

    if(isset($_GET['summon1']) || isset($_GET['summon10']) ){

        if($_GET['summon'] == 1 )
        { $requis = 2; $invoc = 1; } // Glitchable en changeant 1 par 10 par exemple
        else if($_GET['summon'] == 10 )
        { $requis = 18; $invoc = 10; }

            if($_SESSION['argent'] < $requis){
                echo "<p>Vous n'avez pas assez d'argent!<br/>";
                echo "<p>En votre possession : $_SESSION['argent'], requis : $requis </p>";
                $invoc = 0;
            }
            else{   $_SESSION['argent'] -= $requis; } // On débite l'argent
        
            if(isset($invoc) && $invoc > 0){
                $persos_obtenus = invocation($invoc);
                invocation_creation($persos_obtenus,$invoc);
                }
    }



function affiche_liste_persos() {

    //print_r($_SESSION['personnages']);

    foreach($_SESSION['personnages'] as $key => $value){ 
        ?>

<!-- TRANSITION & HOVER -->
<div class="container" >
    <div id="transition-hover" >
        <div  id="div1"> <!-- ondrop="drop(event)" ondragover="allowDrop(event)"> -->
        <img src=<?= $_SESSION['personnages'][$key]->nom().".png" ?> id="drag1" width="70" height="70" /><!-- draggable="true" ondragstart="drag(event)" -->
        <?php // $characterx = "character:".$i; ?>
        <select name=<?= $key ?>>
                    <!-- substr(0,-4) pour retirer ".png" de value -->
            <option value="0"></option>
            <option value="1">Retirer</option>
        </select>      </div>   
        <div id="transition-hover-content" >
            <?php echo "<p class=\"infos_menu\">".$_SESSION['personnages'][$key]->nom().
            "<br/> pv: ".$_SESSION['personnages'][$key]->pv()."/".$_SESSION['personnages'][$key]->pvm().
            "<br/> atk: ".$_SESSION['personnages'][$key]->atk().
            "<br/> def: ".$_SESSION['personnages'][$key]->def().
            "<br/> elmt: ".$_SESSION['personnages'][$key]->type_elmt()."</p>" ; // Pas oublier l'elmt ?>
        </div>
    </div>
</div> 
        <?php
    }
}

    if(isset($_GET['free'])){
        ?>
        <form action="Intro.php" method="POST"> 
        <?php
        affiche_liste_persos();
        ?>  <br/><br/>
        <div  style="text-align : center;">
        <input type="submit" name="leave"  style="text-align : center;"
        value="Relacher les personnages sélectionnés"/>
        <br/>
        </form>
        <?php
    }

    if(isset($_POST['leave'])){
        global $liste_no_o_5_stars;
        global $liste_no_o_4_stars;
        $prix_delete = 0;
        foreach ($_POST as $label => $attribut){

            if($attribut == 1){ // 1 vaut "retirer"
                    // Si le perso est un perso 5* ...
        if(in_array($_SESSION['personnages'][$label]->nom(),$liste_no_o_5_stars)){
                    $prix_delete += 2;
                }
                    // Si le perso est un perso 4* ...
        if(in_array($_SESSION['personnages'][$label]->nom(),$liste_no_o_4_stars)){
                    $prix_delete++;
                }

                unset($_SESSION['personnages'][$label]);
                $prix_delete++;
            }
        }
        $_SESSION['argent'] += $prix_delete;
        echo "<h4>Vous avez gagné ".$prix_delete." argent(s)!</h4>";
        

        // Remise au propre des index, pour qu'en cas d'invocations l'index ne soit pas re-écrasé.

        $index = 0;
        $liste_tempo = $_SESSION['personnages'];
            // Evite qu'on retravaille sur la même liste, car confusion dans le foreach et la réaffectation
        foreach($liste_tempo as $key => $value){
            $_SESSION['personnages'][$index] = $value;
            $index++;
        }
                // On va jusqu'à 100 pour être large, ce n'est pas rigoureux mais pas grave
                // Methode propre : Aller voir la valeur la plus élevée en key dans $_SESSION['personnages']
        for($i = $index+1; $i <= 100 ; $i++){
            unset($_SESSION['personnages'][$i]); 
        }       // On unset() tous les personnages ayant un index supérieur au dernier perso, jusqu'à avoir le nombre actuel de perso

    }
    //echo "<br/>";


    ?>
    <br/>
    <div style="text-align : center;">
    <form action="index.php" method="post">
        <input type="submit" value="Valider"/>
    </form></div>

    </body>
</html>
