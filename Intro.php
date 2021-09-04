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

?>
    <div style="text-align : center;">
    <form action="index.php" method="post">
        <input type="submit" value="Retour"/>
    </form></div>
<?php


    function invocation($nb){
        $liste_des_persos = generation($nb);
        
        affichage($liste_des_persos, $nb);
    }

    function generation($nb){
  
        if(isset($_SESSION['personnages'])){ $nb_personnages = count($_SESSION["personnages"]);
        } else { $nb_personnages = 0;}

        for($i = 1; $i <= $nb; $i++)
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
            // Car sans ça on ne peut pas les utiliser, car issu d'un autre PHP.
                $liste_use = $liste_4_stars;
            }
            else { global $list; $liste_use = $list; }

            // Tirage de 1 personnage parmi ceux de la liste utilisée
            $character = array_rand($liste_use,1) ;
                
            global $liste_complete_o;                                                             // camp = joueur (donc 0)
            $_SESSION["personnages"][$nb_personnages + $i] = new Perso($character,$liste_complete_o,0);
    
            $characters_list[$i] = $character;         
        } 
        
        return $characters_list;
    }

    function affichage($liste_des_persos, $nb){
        ?>
        <div class="summon"> 
            <h3> Voici la liste des personnages que vous avez obtenus! </h3>
        <?php
        for($i = 1; $i <= $nb; $i++){
        ?>
        <img src=<?= $liste_des_persos[$i].".png" ?> id="drag1" width="140" height="140" /><!-- draggable="true" ondragstart="drag(event)" -->
        <?php  
        }
    }

    // Beginnning
    
    if(!isset($_SESSION["personnages"]) ){      

        // * * * * * * * * Nouvelle Partie Lancée * * * * * * *
        $nb_persos_debut = 6;
        // * *  * * * * * * * * * * * * * * * * * * * * * * * *
        
        invocation($nb_persos_debut);
        $_SESSION['argent'] = 2;
    }
    
    // Invocations

    if(isset($_GET['summon'])){

        if($_GET['nb_summon'] == 1 )
        { $requis = 2; $invoc = 1; }
        else if($_GET['nb_summon'] == 10 )
        { $requis = 18; $invoc = 10; }
        else{ return ;  } // Si on n'a pas de nombre, on sort.

        if($_SESSION['argent'] < $requis){
            echo "<p>Vous n'avez pas assez d'argent!<br/>";
            echo "<p>En votre possession : ".$_SESSION['argent'].", requis : $requis </p>";
            return; // $invoc = 0;
        }
        else{   $_SESSION['argent'] -= $requis; } // On débite l'argent
        
        invocation($invoc);
    }
?>

</body>

</html>
