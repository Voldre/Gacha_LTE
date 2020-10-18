<?php
    session_start();

    function remove_element($array,$value) {
        foreach (array_keys($array, $value) as $key) {
           unset($array[$key]);
        }  
         return $array;
       }
    function invocation_debut($x,$my_list) {

        $liste_restante = $my_list ;
    
        ?>
        <div id="div1" length = "240"> 
            <h2> Voici la liste des personnages que vous avez obtenus! </h2>
        <?php
        for($i = 0; $i <= $x-1; $i++){ 
            $character = array_rand($liste_restante,1) ;
    
            $liste_restante = remove_element($liste_restante,$my_list[$character]);
    
            $character = $my_list[$character] . ".png" ;
    
            $ma_liste[$i] = $character ;
            ?>
            <img src=<?= $character ?> id="drag1" width="70" height="70" /><!-- draggable="true" ondragstart="drag(event)" -->
    
          <?php                     } ?> 
        
        </div> 
        <?php 
         return $ma_liste;
        }

    $list = array("Aria","Ether","Hearth","Kuro","Kzina","Silarius","Velrod","Yune","Zelcia","Zerito");

    $_SESSION['argent'] = 2;

    $_SESSION['personnages'] = invocation_debut(6,$list);

    ?>

    <form action="index.php" method="post">
        <input type="submit" value="Valider"/>
    </form>
