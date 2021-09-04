<?php

require("Perso.php"); // Avant le session_start() ! ! ! !

session_start();

require("header.php");

?>
<div style="text-align : center;">
    <form action="index.php" method="post">
        <input type="submit" value="Retour"/>
    </form></div>
<?php

function affiche_liste_persos_a_retirer($threeStars, $doublons) {

    $list_tempo = array(); // on déclare la liste qui contiendra les noms des persos déjà possédés

    foreach($_SESSION['personnages'] as $key => $value){ 
        ?>

<!-- TRANSITION & HOVER -->
<div class="container_delivrer" >
    <div id="transition-hover" >
    <?php
    if( ($threeStars && $_SESSION['personnages'][$key]->stars() == 3) ||  ($doublons && in_array($_SESSION['personnages'][$key]->nom(),$list_tempo))  ){
        echo "<div id='div1' class='retirer' ondblclick=\"this.className = 'non-retirer';\" onclick=\"this.className = 'retirer';\">";
    }
    else{ echo "<div id='div1' class='non-retirer' ondblclick=\"this.className = 'non-retirer';\" onclick=\"this.className = 'retirer';\">";
    }?>
        <img src=<?= $_SESSION['personnages'][$key]->nom().".png" ?> id=<?=$key?> width="70" height="70" /><!-- draggable="true" ondragstart="drag(event)" -->
        </div>
        <div id="transition-hover-content" >
            <?php echo "<p class=\"infos_menu\">".$_SESSION['personnages'][$key]->nom(). // $_SESSION['personnages'][$key]->nom().
            "<br/> pv: ".$_SESSION['personnages'][$key]->pv()."/".$_SESSION['personnages'][$key]->pvm().
            "<br/> atk: ".$_SESSION['personnages'][$key]->atk().
            "<br/> def: ".$_SESSION['personnages'][$key]->def().
            "<br/> elmt: ".$_SESSION['personnages'][$key]->type_elmt()."</p>" ; // Pas oublier l'elmt

                $list_tempo[] = $_SESSION['personnages'][$key]->nom();   // On ajoute le personnage actuel dans la liste, au cas-où futur "doublon"
            ?>
        </div>
    </div>
</div> 
        <?php
    }
}


function vente($id_persos){
    $persos_a_vendre = json_decode($id_persos,true);

    global $liste_no_o_5_stars;
    global $liste_no_o_4_stars;
    $prix_delete = 0;

    foreach($persos_a_vendre as $label){
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
        $_SESSION['argent'] += $prix_delete;
        echo "<h4>Vous avez gagné ".$prix_delete." argent(s)!</h4>";
}

function index_reset(){ 

    // On récupère l'ID des 4 persos envoyés au combat (s'ils existent)
    if(isset($_COOKIE['4_persos'])){
    $persos_au_combat = json_decode($_COOKIE['4_persos'],true);
    } else{ $persos_au_combat = null;}

    $futur_cookie_4_persos = [];

    
    $index = 0;
    $tempo_liste_persos = $_SESSION['personnages'];
    unset($_SESSION['personnages']); // Evite qu'on retravaille sur la même liste, 
    // car confusion dans le foreach et la réaffectation
    
    foreach($tempo_liste_persos as $key => $value){
        $_SESSION['personnages'][$index] = $value; // On le re-remplit de 0

        // Si le personnage actuel est au combat, on réaffecte le bon ID dans le cookie
        if(in_array($key,$persos_au_combat)){
            $futur_cookie_4_persos[] = (string) $index; }

        $index++;            
    }

    // Affectation des 4 nouveaux ID au cookie des persos en combat
    $futur_cookie_4_persos;
    
    echo "<script>";  // Je génère le cookie en JavaScript
    echo "document.cookie=\"4_persos=\" + '".json_encode($futur_cookie_4_persos)."';";
    // Car le PHP n'autorise pas de modifier un cookie après le header. 
    // Le JS, lui, l'autorise. Il suffit juste de passer la variable de PHP à JS.
    echo "</script>";
    echo $_COOKIE['4_persos'];
    
}


if(isset($_POST['Delivrer'])){

    vente($_COOKIE['Delivrer']);

    index_reset();
    // Remise au propre des index (ID)
    // pour qu'en cas d'invocations l'index ne soit pas re-écrasé.
}


    // AFFICHAGE 
?>

<div class="liste">
    <form action="Free.php" method="POST"> 
    <input type="submit" name="select3stars" value="Pré-selectionner tous les persos 3 étoiles"/>
    &nbsp; &nbsp;
    <input type="submit" name="selectdoublons" value="Pré-selectionner tous les persos doublons"/>
    </form>

    <form action="Free.php" method="POST"> 

    <p style="margin: 0 0 -15px 0;font-size:20px;">Cliquez pour sélectionner un personnage | Double cliquez pour le désélectionner</p>

    <br/> <!-- On évite de mettre les persos à côté des boutons -->
    <?php

    /*
    if(isset($_POST['select3stars'])){
        $threeStars = true;
    } else { $threeStars = false;}
    if(isset($_POST['selectdoublons'])){
        $doublons = true;
    } else { $doublons = false;}
    */
    // Simplification des lignes ci-dessus
    $threeStars = isset($_POST['select3stars']);
    $doublons = isset($_POST['selectdoublons']);

    affiche_liste_persos_a_retirer($threeStars, $doublons); ?>

    <br/>
    
    <div  style="text-align : center;">
    <input type="submit" name="Delivrer"  style="text-align : center;"
    value="Relacher les personnages sélectionnés" onclick="get_delivrer()"/>
    <br/>
    </form>
</div>


<br/>

<div style="text-align : center;">
<form action="index.php" method="post">
    <input type="submit" value="Retour"/>
</form></div>

</body>

    
<script>

// Récupération des infos des persos envoyés en JS

/* Récupère les sous parties d'une div (child)
var parent = document.getElementById('parentDiv');
var childs = parent.childNodes;
console.log(childs); 
*/

function get_delivrer(){
    var i;
    var x = document.getElementsByClassName("retirer");

    getDelivrer = [];
    
    console.log(x.length);

    for (i = 0; i < x.length; i++) {
        console.log(x[i]);
        var child = x[i].firstChild;

        // S'il y a bien un enfant
        /* Ce "if" est obligatoire, car s'il n'y a pas
           d'enfant et qu'on fait ces opérations, alors "TypeError"
           et le programme JS cesse
        */
        if(x[i].childNodes.length >= 1){
        while(child && child.nodeType !== 1) {
            child = child.nextSibling;
        }
        //console.log(child)
        console.log(child.id);

        getDelivrer.push(child.id); // en "i", on prend l'ID du personnage
        }
    }
    // Sauvegarde des variables
    //sessionStorage.setItem('Delivrer', JSON.stringify(getDelivrer));
    //console.log(sessionStorage.getItem("Delivrer"));

    document.cookie="Delivrer=" + JSON.stringify(getDelivrer);

}


</script>

</body>
</html>