
<?php
            // Vérification de la présence de la BDD, création de le BDD si elle n'existe pas.
try {
    $bdd = new PDO('mysql:host=localhost;dbname=test', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)); // ACCESS DENIED = LOGIN/MDP faux  OU host=sql.hebergeur.blabla 
    // Un objet (variable...) contenant la BDD, type mysql se trouvant chez moi, son nom est 'test', le login : 'root', mot de passe : ''
                                                                //array(PDO::... => ...) est Un outil pour AFFICHER mieux les erreurs
    }
    catch (Exception $e)
    {
    //die('Erreur : ' . $e->getMessage());
    //echo $e->getMessage();
    //echo "<p>Nous allons importer la Base de Données existante...</p>";

/*
        if(isset($_POST['creation_bdd']))
        {
            include("create_db_if_not_exist.php");
            echo "<p class=\"bdd_ajoutee\">La Base de Données du site web a été ajoutée avec succès! Vous pouvez à présent utiliser l'espace membres présent sur le site.</p>";

        }
        else
        {
            ?>
        <form method="post">
            <p class="creer_la_bdd">Aucune Base de Données n'a été trouvée. | | Vous devez l'importer pour pouvoir utiliser l'espace membres : 
                <input type="submit" name="creation_bdd" value="Importer la Base de Donnee"/>  
            </p> 
        </form>
            <?php
        }
*/


    }
    ?>
    
        
<div class="menu">
    <?php
    echo "<h5>Site réalisé par Voldre (12/10/2020 --> )</h5>";

    if (isset($_SESSION['id']) && isset($_SESSION['pseudo']))
        {
            echo "<h5>Bonjour " , strtolower($_SESSION['pseudo']) , "</h5>";
        }
    else
    {
        echo"<h4><a href=\"page_inscription.php\">Inscription</a></h4>";
    }
    ?>
    
    </h2>

    <?php
    
    if (isset($_SESSION['id']) && isset($_SESSION['pseudo']))
    {
        echo"<h4><a href=\"page_deconnexion.php\" class=\"red\">Déconnexion</a></h4>";
    }
    else
    {
        echo"<h4><a href=\"page_connexion.php\" id=\"IDtest\">Connexion</a></h4>";
    }

    ?>
    <form method="GET">

        <input type="submit" name="new_game" value="Relancer une partie"/>
    </form>

    <?php
    echo "Argent : ";
    ?>

    </div>
