<?php

//require("header.php");

/*  Création des personnages fait à la main

$list = array("Aria"=>[2,-2,"terre"],"Ether"=>[-1,2,"terre"],"Hearth"=>[2,-1,"glace"],
    "Kuro"=>[-1,2,"foudre"],"Kzina"=>[2,-1,"tenebres"],"Silarius"=>[3,-2,"tenebres"],
    "Yune"=>[1,0,"vent"],"Zelcia"=>[3,-2,"feu"],"Zerito"=>[2,-1,"vent"],
    "Eroste"=>[3,-2,"glace"],"Jessica"=>[2,-2,"vent"],"Kendrick"=>[1,1,"physique"],
    "Ketsu"=>[1,-1,"tenebre"],"Kimmy"=>[-1,2,"physique"],"Mentor"=>[1,1,"lumiere"] );

$liste_4_stars = array("Velrod"=>[4,2,"foudre"],"Nerio"=>[3,2,"tenebres"],
        "Brahms"=>[2,4,"lumiere"],"Chrome"=>[4,2,"lumiere"],
        "Dark_Silarius"=>[6,0,"tenebres"],"Felmos"=>[5,1,"feu"] );
        

$liste_no_o = array("Aria","Ether","Hearth","Kuro","Kzina","Silarius",
                "Yune","Zelcia","Zerito","Eroste","Jessica","Kendrick",
                "Ketsu","Kimmy","Mentor");

$liste_no_o_4_stars = array("Velrod","Nerio","Brahms","Chrome","Dark_Silarius","Felmos");

*/


    // Création de personnages en requête SQL

// Objects lists

$list = array();
$reponse = $db->query('SELECT * FROM Cartes_Personnages WHERE STARS = 3');

while( $data = $reponse->fetch()){
    $list[$data['NOM']] = $data; // [] permet d'ajouter un nouvel élément
}
$reponse->closeCursor();

$liste_4_stars = array();
$reponse = $db->query('SELECT * FROM Cartes_Personnages WHERE STARS = 4');

while( $data = $reponse->fetch()){
    $liste_4_stars[$data['NOM']] = $data; // [] permet d'ajouter un nouvel élément
}
$reponse->closeCursor();


// No-Objects lists

$list_no_o = array();
$reponse = $db->query('SELECT NOM FROM Cartes_Personnages WHERE STARS = 3');

while( $data = $reponse->fetch()){
    $list_no_o[] = $data['NOM']; // [] permet d'ajouter un nouvel élément
}
$reponse->closeCursor();

$list_no_o_4_stars = array();
$reponse = $db->query('SELECT NOM FROM Cartes_Personnages WHERE STARS = 4');

while( $data = $reponse->fetch()){
    $liste_no_o_4_stars[] = $data['NOM']; // [] permet d'ajouter un nouvel élément
}
$reponse->closeCursor();



$liste_complete = $list_no_o + $liste_no_o_4_stars;

$liste_complete_o = $list + $liste_4_stars;







/* Voici les seules lignes de codes existantes pour fabriquer et remplir la table 'Cartes_Personnages'

foreach($list as $key => $value){
    $requete = $db ->prepare('INSERT INTO Cartes_Personnages(NOM, PVM, ATK, DEF, ELMT, STARS) VALUES (?, ?, ?, ?, ?, ?) ');  
    $requete->execute(array($key,16, 8+$list[$key][0], 8+$list[$key][1], $list[$key][2], 3) );
    $requete->closeCursor();
}

foreach($liste_4_stars as $key => $value){
    $requete = $db ->prepare('INSERT INTO Cartes_Personnages(NOM, PVM, ATK, DEF, ELMT, STARS) VALUES (?, ?, ?, ?, ?, ?) ');  
    $requete->execute(array($key, 18, 8+ $liste_4_stars[$key][0], 8+ $liste_4_stars[$key][1], $liste_4_stars[$key][2], 4) );
    $requete->closeCursor();
}

Ajout dans SQL des Cartes_Personnages!

*/

//print_r($liste_complete_o);
