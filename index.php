<?php

$_SESSION['position'] = "debut";

require("Perso.php"); // Classe en tout tout premier

session_start();    // Toujours en premier MAIS après les classes

?>
<audio autoplay controls loop  style="display: none;">
  <source src="Twin_Saga.mp3" type="audio/mpeg">
  </audio>
  <?php

if(isset($_GET['new_game']))
{   
    session_unset(); 

    $_SESSION['position'] = "debut";
    $_SESSION['invocation'] = 0;


    header('Location : Intro.php');
} 

require("Liste_Persos.php");


$ma_liste_ennemis = [];

if(isset($_POST['change']) ){
    $_SESSION['ennemis'] = array();
}

if(isset($_POST['confirm'])){
    $ma_new_list = array();
    $first = false;
    $second = false;
    $third = false;
    $fourth = false;

    $isOK = true;

    echo "ici";
    print_r($_POST);

    
    for($i = 0; $i <= 4; $i++){
        $variable = 'character:'+$i ;
        $ma_new_list = $_POST ;
    }
    foreach ($ma_new_list as $label => $attribut){
        echo $label." est associé à : ".$attribut ."  " ;
        switch($attribut){
            case 1: $first = true;
        break;
            case 2: $second = true;
        break;
            case 3: $third = true;
        break;
            case 4: $fourth = true;
        break;
            }
        }

    echo "<br/>";
    $last_list = array_count_values($ma_new_list);
    foreach($last_list as $label => $attribut){
        if($label != "0" && $attribut > 1){
            echo "<p>Problème! Plusieurs personnages se trouvent à la même place!</p>";
            $isOK = false;
        }
    }
    if(!$first || !$second || !$third || !$fourth){
        echo "<p>Problème! Une ou plusieurs places n'ont pas été attribuées!</p>";
        $isOK = false;
    }

    if ($isOK){
        ?>
        <p>Le jeu est prêt! Vous pouvez débuter</p>

        <?php
        foreach($ma_new_list as $key => $value){

            if($value*2 != 0 ){ // Vérifie que $value != 0 et $value != string, car string * 2 = 0
                $liste_definitive[$value] = $_SESSION['personnages'][$key];
            }
        }
        $_SESSION['mes_persos'] = $liste_definitive;
        // On peut déclarer une variable de SESSION comme un TABLEAU en rajoutant []

        // Pour rafraîchir les persos de la Game d'avant
        unset($_SESSION['persos_totaux']);

        header('Location: Game.php');
            }
}

function place() { ?>
<div id="div2"></div>
<?php } 

    // Création des ennemis

function place_enemy($x,$my_list) {

    if(isset($_SESSION['ennemis']) ){
        $ma_liste_ennemis = $_SESSION['ennemis'];
    }
    if( !isset($ma_liste_ennemis[$x]) && !isset($_POST['confirm']) ){
        $character = array_rand($my_list,1) ;
        $_SESSION['ennemis'][$x] = $my_list[$character];

        $character = $my_list[$character];    

        global $ma_liste_ennemis ;
        $ma_liste_ennemis[$x] = $character ;
        }
    else {
    $character = $ma_liste_ennemis[$x];
    }
    #echo "Voici : ".$character;
    ?>
    <div id="div2">
    <img src=<?= $character.".png" ?> id="drag2" width="70" height="70" />
<?php 
}


function place_for_character($i) { 
    ?>
    <div id="div3" ondrop="drop(event)" ondragover="allowDrop(event)">
    <h1 style="color: rgb(160, 96, 0); text-align: center;"><?= $i ?>
    </div>
    <?php                                                                      

} 

function remove_element($array,$value) {
    foreach (array_keys($array, $value) as $key) {
       unset($array[$key]);
    }  
     return $array;
   }
   

$ma_liste = array();

function random_character($x, $my_list) {

    $liste_restante = $my_list ;

    for($i = 0; $i <= $x-1; $i++){ 
        $character = array_rand($liste_restante,1) ;

        $liste_restante = remove_element($liste_restante,$my_list[$character]);

        $character = $my_list[$character] . ".png" ;

        $ma_liste[$i] = $character ;
        #echo "Voici : ".$character;
        ?>
        <div id="div1"> <!-- ondrop="drop(event)" ondragover="allowDrop(event)"> -->
        <img src=<?= $character ?> id="drag1" width="70" height="70" /><!-- draggable="true" ondragstart="drag(event)" -->
        <?php // $characterx = "character:".$i; ?>
        <select name=<?= $character ?>>
          <option value="0"></option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
        </select>      </div>   
      <?php
    }
}

function affiche_liste_persos($liste) {

    foreach($liste as $key => $value){ 
        ?>

<!-- TRANSITION & HOVER -->
<div class="container" >
    <div id="transition-hover" >
        <div  id="div1"> <!-- ondrop="drop(event)" ondragover="allowDrop(event)"> -->
        <img src=<?= $liste[$key]->nom().".png" ?> id="drag1" width="70" height="70" /><!-- draggable="true" ondragstart="drag(event)" -->
        <?php // $characterx = "character:".$i; ?>
        <select name=<?= $key ?>>
                    <!-- substr(0,-4) pour retirer ".png" de value -->
          <option value="0"></option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
        </select>      </div>   
        <div id="transition-hover-content" >
            <?php echo "<p class=\"infos_menu\">".$liste[$key]->nom().
            "<br/> pv: ".$liste[$key]->pv()."/".$liste[$key]->pvm().
            "<br/> atk: ".$liste[$key]->atk().
            "<br/> def: ".$liste[$key]->def()."</p>" ; ?>
        </div>
    </div>
</div> 
      <?php
    }
}

     require("header.php");

if(!isset($_SESSION['personnages']) ){ ?>
    <form action ="Intro.php" method="GET">
        <input type="submit" name="new_game" value="Lancer une nouvelle partie"/>
    </form>
<?php
}
else if( !isset($_SESSION['characters']) ){
?>

<form action="index.php" method="post">

<div class="liste">
<h3> Zone des personnages </h3>
<?php affiche_liste_persos($_SESSION['personnages']); ?>
</div>
<h1>Tournament of the Thirty Choosen</h1>
<h3>Choisissez les personnages à placer aux 4 emplacements puis validez votre choix.</h3>
<input type="submit" name="confirm" value="Envoyer" />  

<input type="submit" name="change" value="Changer de Tournois"/>

</form>
<?php } ?>

<main id="tournament">
<ul class="round round-1">

<?php

    for($i = 1 ; $i <= 4 ; $i++)
    {          // **** (9) *2 en proba en % ! ****
        if(rand(1,100) <= 9 + 9){
            global $liste_no_o_4_stars;
            $liste_use = $liste_no_o_4_stars;
        }
        else { global $liste_no_o ; 
            $liste_use = $liste_no_o; }
        ?>
        <li class="spacer">&nbsp;</li>
        <li class="game game-top winner"><?php place_for_character($i) ?></li>
        <li class="game game-spacer">&nbsp;</li>
        <li class="game game-bottom "><?php place_enemy($i,$liste_use) ?> </li>
        <li class="spacer">&nbsp;</li>
<?php
    }
    ?>

    <li class="spacer">&nbsp;</li>
</ul>
<ul class="round round-2">
    <?php
    for($i = 0 ; $i <= 1 ; $i++)
    {
        ?>
        <li class="spacer">&nbsp;</li>
        
        <li class="game game-top winner"><?php place()?> </li>
        <li class="game game-spacer">&nbsp;</li>
        <li class="game game-spacer">&nbsp;</li>
        <li class="game game-spacer">&nbsp;</li>
        <li class="game game-spacer">&nbsp;</li>
        <li class="game game-spacer">&nbsp;</li>
        <li class="game game-bottom "><?php place() ?> </li>
        <li class="spacer">&nbsp;</li>

<?php
    }
    ?>

    <li class="spacer">&nbsp;</li>
</ul>
<ul class="round round-3">
<?php
    for($i = 0 ; $i <= 0 ; $i++)
    {
        ?>
        <li class="spacer">&nbsp;</li>
        
        <li class="game game-top winner"><?php place() #echo "player n°".$i ;?> <span>79</span></li>
        <li class="game game-spacer">&nbsp;</li>
        <li class="game game-bottom "><?php place() #echo "player n°".$i*2 ;?> <span>48</span></li>
        <li class="spacer">&nbsp;</li>

<?php
    }  ?>
</ul>
<ul class="round round-4">

    <?php echo "<div id=\"div2\"></div>"; ?>
</main>

    <script>
    function allowDrop(ev) {
    ev.preventDefault();
    }

    function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
    alert(data)
    }

    function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    ev.target.appendChild(document.getElementById(data));
    alert(data)
    }
    </script>


    </body>
</html>
