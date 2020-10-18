<?php
$list = array("Aria"=>[2,-2,"terre"],"Ether"=>[-1,2,"terre"],"Hearth"=>[2,-1,"glace"],
    "Kuro"=>[-1,2,"foudre"],"Kzina"=>[2,-1,"tenebres"],"Silarius"=>[3,-2,"tenebres"],
    "Yune"=>[1,0,"vent"],"Zelcia"=>[3,-2,"feu"],"Zerito"=>[2,-1,"vent"],
    "Eroste"=>[3,-2,"glace"],"Jessica"=>[2,-2,"vent"],"Kendrick"=>[1,1,"physique"],
    "Ketsu"=>[1,-1,"tenebre"],"Kimmy"=>[-1,2,"physique"],"Mentor"=>[1,1,"lumiere"] );

$liste_4_stars = array("Velrod"=>[4,2,"foudre"],"Nerio"=>[3,2,"tenebres"],
        "Brahms"=>[2,4,"lumiere"],"Chrome"=>[4,2,"lumiere"],
        "Dark_Silarius"=>[6,0,"tenebre"],"Felmos"=>[5,1,"feu"] );


$liste_no_o = array("Aria","Ether","Hearth","Kuro","Kzina","Silarius",
                "Yune","Zelcia","Zerito","Eroste","Jessica","Kendrick",
                "Ketsu","Kimmy","Mentor");

$liste_no_o_4_stars = array("Velrod","Nerio","Brahms","Chrome","Dark_Silarius","Felmos");

$liste_complete = $liste_no_o + $liste_no_o_4_stars;

$liste_complete_o = $list + $liste_4_stars;

//print_r($liste_complete_o);