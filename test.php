<?php 
            $fichier = fopen('Liste_URL.txt', 'c+b');
            $filesize = filesize("Liste_URL.txt");


?>

<html>

<head>
    <title>Stockage des URL</title>
    
    <meta charset="utf-8" />

    <link rel="stylesheet" href="style.css">
    <script type="text/javascript">
        function handleDragDropEvent(oEvent) {

            switch (oEvent.type) {
                case "dragover":
                case "dragenter":
                    oEvent.returnValue = false;
                    break;
                case "drop":
            }
        }
    </script>
</head>

<body>
    <P>Sauvegarder et réutiliser toutes les URL des pages Firefox actuellement ouverte.</p>
<ul>
<li>Première étape, ajouter l'extension <a href="https://addons.mozilla.org/en-US/firefox/addon/copy-all-tab-urls-we/" target="blank">Copy All Tab Urlq</a></li>
<li>Deuxième étape, cliquer sur l'extension apparu en haut à droite. (Une pop-up apparaîtra en bas à droite, stipulant "Copy Tabs - Link(s) copied : [value]"</li>
<li>Troisième étape, collé ce qui a été enregistré dans le presse-papier dans cette zone de texte. (Ctrl+V)</li> 
</ul>   <div>

 <form method="POST">
    <textarea name="message" rows="1" cols="10" ondragenter="handleDragDropEvent(event)" ondragover="handleDragDropEvent(event)" ondrop="handleDragDropEvent(event)">Collez vos URL ici...</textarea>
    <input type="submit" value="N'enregistrer que ces URL" name="save">
    <input type="submit" value="Ajouter ces URL à la liste précédente" name="add">
    </form>

<button onclick="maFonction()">Ouvrir mes liens enregistrées</button>
<p class="red">Attention, vous devez autoriser cette page à ouvrir les pop-ups si vous souhaitez utiliser ce bouton.</p>

<?php
    if(isset($_POST['message']))
    {
        if(isset($_POST['save']))
        {
        file_put_contents('Liste_URL.txt', $_POST['message']);
        echo "Les URL ont bien été enregistrées !";
        }
        else if(isset($_POST['add']))
        {
            
        }
    }
    
    function tronque_chaine ($chaine, $lg_max) {
            if (strlen($chaine) > $lg_max)
            {
            $chaine = substr($chaine, 0, $lg_max);
            $chaine += "...";
            return $chaine;
            }
        }

    echo "LONGUEUR : ".$filesize."<br/>";
    if ($filesize > 10)
    {
        $lines = file("Liste_URL.txt");

        foreach($lines as $n => $line){

            $path = parse_url($line, PHP_URL_PATH);

            $name = basename($path);

            $line = substr($line,0,strlen($line)-2);

            //$intitule = substr($line,12,60);
        echo "<a href=\"".$line."\">".$name."</a><br />";
        }
    }
    //echo implode($lines);

    function php2js ($var) {
        if (is_array($var)) {
            $res = "[";
            $array = array();
            foreach ($var as $a_var) {
                $array[] = php2js($a_var);
            }
            return "[" . join(",", $array) . "]";
        }
        elseif (is_bool($var)) {
            return $var ? "true" : "false";
        }
        elseif (is_int($var) || is_integer($var) || is_double($var) || is_float($var)) {
            return $var;
        }
        elseif (is_string($var)) {
            return "\"" . addslashes(stripslashes($var)) . "\"";
        }
        // autres cas: objets, on ne les gère pas
        return FALSE;
    }
?>	
<?php
  $tab = array(1, 2, array(3, 4), 5, "salut", True);
  $js = php2js($tab); // [1,2,[3,4],5,'salut',true]
?>
<script language="JavaScript">
function maFonction(){


  var tab =<?php echo $js; ?>
  alert(tab);
}
</script>
</div>
</body>

</html>


