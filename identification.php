<?php

/*
Notions intéressantes :

A vraiment retenir : on ne met JAMAIS un mot de passe dans une bdd en VARCAHR(55)!

    Le mot de passe haché fait PLUS DE 55 CARACTERES.
    Du coup le mot de passe haché enregistré sera différent du mot de passe vérifié! Car pas transcrit en intégralité!
    C'est une erreur énorme et on peut ne pas trouver facilement l'erreur! J'ai galéré moi!
    Donc on utilise tous les bits (255) et on met VARCHAR(255)

En cas de deconnexion, pas besoin de valider on locate directe.

Pour rajouter des éléments dans un tableau sans écraser l'élément précédent (et sans avoir à préciser d'index) :

    $montableau[indexconnu][] = $ma_valeur
    Et on créera (si vierge) : $montableau[indexconnu][0], puis [1], puis [2], etc...

    Bref : Cela permet de générer un tableau de plusieurs éléments avec une série de $tab[] = truc


Rappel de syntaxe UPDATE : 

    $requete= $db->prepare('UPDATE Joueurs SET argent  = ? WHERE ID = ?');
    $requete->execute( array($_SESSION['argent'], $_SESSION['id'] )  );
    $requete->closeCursor();


Lorsqu'on donne un surnom à une table, ex : Cartes_des_Joueurs AS CdJ, alors on 
ne peut plus appeler une/des colonnes avec le nom Cartes_des_Joueurs,

    ex :
        $reponse = $db->prepare('SELECT CdJ.*, CP.*
                                FROM Cartes_des_Joueurs AS CdJ , Cartes_Personnages AS CP
                                WHERE idJoueur = ?  AND CdJ.Nom_P = CP.NOM');

        On ne peut pas écrire SELECT Carte_des_Joueurs.*, car la table a un surnom qui est CdJ !

*/

require("Perso.php"); // Classe en tout tout premier

session_start(); // Avant tout code HTML mais après les classes

require("header.php");

    // Connexion

if(isset($_POST['Ma_Connexion'])){
    ?>
    <h4>Vous pouvez vous connecter en remplissant le formulaire ci-dessous.</h4>
    <form method="post">
    <p>Login    : <input type="text" name="login"/></p>
    <p>Password : <input type="password" name="mdp"/></p>
    <input type="submit" name="Connexion" value="Connexion"/>
    </form>
    <?php
}

if(isset($_POST['Connexion'])){   // Login est une clé unique car j'empêche 2 personnes d'avoir le même pseudo!

    $_POST['login'] = strtolower($_POST['login']);
    $_POST['login'] = htmlspecialchars($_POST['login']);

    $requete = $db->prepare('SELECT ID, login, mdp, argent FROM Joueurs WHERE login = ?');
    $requete->execute(array($_POST['login']));

    $donnees = $requete->fetch();

    $isPasswordCorrect = password_verify($_POST['mdp'],$donnees['mdp']);
    // Evite de saisir Pseudo, pseudo, pSEUDo comme 3 logins différents !
        // Si PseUDo  == pseudo (donc oui car strtolower)
    if($_POST['login'] == $donnees['login'] && $isPasswordCorrect){

        echo "<p>Vous vous êtes connecté avec succès!</p>";
        $_SESSION['id'] = $donnees['ID'];
        $_SESSION['login'] = $donnees['login'];

        // On reprend la donnée "argent" de la req. $requete

            if($donnees['argent'] != -100){ // CAR -100 CORRESPOND A LA VALEUR DE CREATION, DONC 0 PERSO, jamais joué
                $_SESSION['argent'] = $donnees['argent'];
                /*
                $req_nb = $db->prepare('SELECT COUNT(idJoueur) AS nb_persos FROM Cartes_des_Joueurs WHERE idJoueur = ?');
                $req_nb->execute(array($_SESSION['id']));
                    $data = $req_nb->fetch(); */

                //function PersosFromSQL($id){

                    $reponse = $db->prepare('SELECT CdJ.*, CP.*
                                            FROM Cartes_des_Joueurs AS CdJ , Cartes_Personnages AS CP
                                            WHERE idJoueur = ?  AND CdJ.Nom_P = CP.NOM');
                            $reponse->execute(array($_SESSION['id']));

                    $_SESSION['personnages'] = array(); // Pour pas rajouter ceux d'avant

                    while($persos_SQL = $reponse->fetch() ){
                                                        //nom, liste, camp, stars
                        $_SESSION['personnages'][] = new Perso(0, 0, 0, 0, $persos_SQL);     
                                        // [] obligatoire pour dire qu'on AJOUTE UN ELEMENT A LA LISTE ! ! !  :)
                        // Le 5e attribut vient rendre les 4 premiers inutiles, sauf camp, donc 0.

                        //print_r($persos_SQL);
                    }
                    $reponse->closeCursor();
                //}
                //PersosFromSQL($_SESSION['id']);

            }
            else{   // SI le joueur débute (-100 argent), donc 0 personnage, alors SUMMON DU DEBUT ! :)
                header("Location: Intro.php");
            }            

    }
    else{
        echo "<p class=\"red\">Mauvais identifiant ou mot de passe !</p>";
        ?> 
        <form action="identification.php" method="post"> 
        <input type="submit" name="Ma_Connexion" value="Recommencer la connexion"/>
        </form>   
        <?php
    }   
    $requete->closeCursor();
}

    // Deconnexion

if(isset($_POST['Deconnexion'])){
    // Suppression des variables de session et de la session
    $_SESSION = array();
    session_destroy();

    // Suppression des cookies de connexion automatique
    setcookie('login', '');
    setcookie('pass_hache', '');

    header("Location: index.php");

}

    // Sauvegarde

if(isset($_POST['Sauvegarder'])){
    if(!isset($_SESSION['login']) ){
        echo "Vous n'êtes pas connecté, vous ne pouvez pas sauvegarder.";
    }
    else{
        echo $_SESSION['id'];
                            // En faite, le prepare et DELETE FROM marchent, mais pas de * entre DELETE et FROM
        $requete =$db ->prepare('DELETE FROM Cartes_des_Joueurs WHERE idJoueur = ? ');
        $requete->execute( array( $_SESSION['id'] ) );
        $requete->closeCursor();
        

        foreach($_SESSION['personnages'] as $key => $value){         // Plus idPerso mais Nom_P, car c'est la key dans ['personnages']
            $requete = $db->prepare('INSERT INTO Cartes_des_Joueurs(idJoueur, Nom_P, PVM_P, ATK_P, DEF_P) VALUES (?, ?, ?, ?, ?)');
            $requete->execute( array($_SESSION['id'], $_SESSION['personnages'][$key]->nom(), // NOM car Nom_P dans la table
                                $_SESSION['personnages'][$key]->pvm(), $_SESSION['personnages'][$key]->atk(), 
                                 $_SESSION['personnages'][$key]->def() ) );

                $requete->closeCursor();
            }
                    // UPDATE l'argent
        $requete= $db->prepare('UPDATE Joueurs SET argent  = ? WHERE ID = ?');
        $requete->execute( array($_SESSION['argent'], $_SESSION['id'] )  );
        $requete->closeCursor();
        
        echo "<p>Votre progression a bien été sauvegardée!</p>";
    }
}

    // Inscription

if(isset($_POST['Inscription'])){
    ?>
    <h4>Vous pouvez vous inscrire en remplissant le formulaire ci-dessous.</h4>
    <p class="red">Attention, votre mot de passe doit faire plus de 5 caractères.</p>
    <form method="post">
    <p>Login    : <input type="text" name="login"/></p>
    <p>Password : <input type="password" name="mdp"/></p>
    <input type="submit" name="subscribe" value="S'inscrire"/>
    </form>
    <?php
}
if(isset($_POST['subscribe']) ){

    $isLoginOK = true;
    $isMdpOK = true;

    $reponse = $db->query('SELECT login FROM Joueurs');
    while ($donnees = $reponse->fetch()) 
    {         // Evite de saisir Pseudo, pseudo, pSEUDo comme 3 logins différents ! Tout sauvegardé en minuscule!
        $_POST['login'] = strtolower($_POST['login']); 
        if($donnees['login'] == $_POST['login']){
            $isLoginOK = false;
        }
    }
    $reponse->closeCursor();

    if(strlen($_POST['mdp']) <= 4){
        $isMdpOK = false;
    }

    if(!$isLoginOK){
        echo "<p class=\"red\">Erreur! Ce login existe déjà, essayez-en un autre!</p>";
    }
    if(!$isMdpOK){
        echo "<p class=\"red\">Erreur! Votre mot de passe ne comporte pas assez de caractères, <br/>";
        echo "5 au minimum était attendu, vous avez saisi ".strlen($_POST['mdp'])." caractères. </p>";
    }
    
    if($isLoginOK && $isMdpOK){
        $requete = $db->prepare('INSERT INTO Joueurs(login, mdp, argent) VALUES (?,?,?)');
        $password = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
        $requete->execute( array($_POST['login'], $password , -100 ));
                                    // Valeur par défaut pour dire "N'a jamais joué, donc 0 perso".
        $requete->closeCursor();
        ?>
        <p>Votre inscription a bien été réalisée!</p>"        
        <?php
    }    ?> 
    <form action="identification.php" method="post"> 
    <input type="submit" name="Ma_Connexion" value="Connexion"/>
    </form>   
<?php
}

?>
    <br/>
<form action="index.php" method="post">
<input type="submit" value="Retourner au jeu"/>
</form>


</body>
</html>