 <div class="menu">      <!-- Renvoi vers Intro.PHP -->
    <form method="GET">
        <input type="submit" name="new_game" value="Lancer une nouvelle partie ?" class="main_button"/>
    </form>
    <div class="no_mobile">
    <br/>
    <h2>Gacha : LTE</h2>
    <p>Jeu réalisé par Voldre<p>
    <h5>12/10/2020 --> 26/01/2021</h5>
    <h5>Update : 07-08/2021</h5>
    </div>

    <?php  

                // Renvoi vers identification.PHP

    if (isset($_SESSION['id']) && isset($_SESSION['login']) ){
            echo "<h4>Bonjour " , strtolower($_SESSION['login']) , " !</h4>";
            ?>
            <form action="identification.php" method="post"> 
            <input type="submit" name="Sauvegarder" value="Sauvegarder"/>
            <input type="submit" name="Deconnexion" value="Deconnexion"/>

            </form>
            <?php
        }
        else{
            ?>
            <form action="identification.php" method="get"> 
            <input type="submit" name="Inscription" value="Inscription"/>
            <input type="submit" name="Ma_Connexion" value="Connexion"/>

            </form>
        <?php
        }


    if(isset($_SESSION['argent'])){
    echo "<p>Argent : ".$_SESSION['argent']." Gold.</p>";
    }
    ?>    <!-- Renvoi vers Intro.PHP et Free.PHP (+ Presentation.PHP) -->

    <form action="Intro.php" method="GET">
        <input type="hidden" name="nb_summon" value="1"/>
        <input type="submit" name="summon" value="Lancer 1 invocation (2G)"/>
    </form>
    <form action="Intro.php" method="GET">
        <input type="hidden" name="nb_summon" value="10"/>
        <input type="submit" name="summon" value="Lancer 10 invocations (18G)"/>
    </form>

    <form action="Free.php" method="GET">
        <input type="submit" name="free" value="Délivrer un personnage"/>
    </form>
    
    <a class="question" style="font-size: 35px; float: right;" href="Presentation_Persos.php"><img src="_question_mark.png"/></a>

    <!-- Rounded switch -->
    <p>Conserver les 4 combattants à leur emplacement?</p>
    <label class="switch">
    <input id="conserver" type="checkbox" <?php if(isset($_COOKIE['conserver'])){ echo $_COOKIE['conserver'];} ?> />
    <span class="slider round"></span>
    </label>
</div>
