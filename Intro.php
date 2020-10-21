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


Lorsqu'on supprime un personnage (donc un array random d'une liste),
il faut penser à renuméroté correctement les array().

    Parce que la variable session personnage va toujours compter le nombre de persos et faire +1 à l'index.

    Si 6 persos, et on supprime le 2e. Alors on aura 0,2,3,4,5.
    Il reste 5 persos, donc le nouveau "6eme" aura l'index 5, et il écrasera l'ancien "6e".
    Du coup, il faut bien penser à renuméro pour count() renvoie sur le dernier vrai index!

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



function affiche_liste_persos() {

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
            "<br/> def: ".$_SESSION['personnages'][$key]->def()."</p>" ; ?>
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
        global $liste_no_o_4_stars;
        $prix_delete = 0;
        foreach ($_POST as $label => $attribut){
            //echo $label." est associé à : ".$attribut ."  " ;
            if($attribut == 1){

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
            $_SESSION['personnages'][$index] = $_SESSION['personnages'][$key];
            $index++;
        }

        for($i = $index; $i <= count($_SESSION['personnages']) ; $i++){
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
