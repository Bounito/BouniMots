<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";


if (isset($_POST['recommencer'])) {
    session_unset();
    session_destroy();
    session_start();
}

fctAfficheEntete("BouniMots");


?>
<div id="divIntro" style="position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 9999;">
<svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
  <!-- Cercle transparent -->
  <circle id="myCircle" cx="50%" cy="50%" r="700" fill="none" stroke="MidnightBlue" stroke-width="800"/>
</svg>
</div>
<script>
  // Récupérer l'élément cercle par son ID
  var circle = document.getElementById("myCircle");
  var strokeWidth = 1500;
  function decreaseStrokeWidth() {
        // Décrémenter le stroke-width
        strokeWidth -= 4;
        // Mettre à jour le stroke-width du cercle
        circle.setAttribute("stroke-width", strokeWidth);
        // Vérifier si le stroke-width est supérieur à 0
        if (strokeWidth > 0) {
            // Appeler la fonction à nouveau après un délai de 50 millisecondes
            setTimeout(decreaseStrokeWidth, 5);
        } else {
            document.getElementById("divIntro").style.display = 'none';
        }        
    }

    // Appeler la fonction pour commencer l'animation
    decreaseStrokeWidth();
    
</script>
<?php


fctAfficheScore();

echo "<DIV class='divBtnBack'>";
echo "<IMG src='android-chrome-192x192.png' onclick='playWelcome();' width=50 style='cursor: pointer;' />";
echo "</DIV>";


if (!isset($_SESSION['user'])) {
    echo "<SMALL>Clic sur <IMG src='img/userGray.png' width=20 /> pour te connecter !</SMALL>";
    echo "<BR>";
}

echo "<SMALL>Ajoute cette page (svaret.freeboxos.fr) à tes favoris !</SMALL>";
echo "<BR>";


//===================================================Merci d'avoir joué !
    if (isset($_SERVER["HTTP_REFERER"])) {
        // Récupérer l'URL de référence
        $referer = $_SERVER["HTTP_REFERER"];
        if (substr($referer, -1)!="/") {
            // Obtenir le nom du fichier sans extension
            $nomFichierSansExtension = explode(".",basename($referer))[0];
            $nomFichierSansExtension = urldecode($nomFichierSansExtension);
            if ($nomFichierSansExtension!="") {
                if ($nomFichierSansExtension!="index")
                    echo "Merci d'avoir joué à <B>".$nomFichierSansExtension."</B> !<BR>";
                fctLog($nomFichierSansExtension);
            }
        }            
    }

    //========= Caroussel THEMES
    echo "<div style='text-align:left;background-color: lightblue;'><small>Les derniers thèmes ajoutés ⏱</small>";


    // Chemin du répertoire contenant les fichiers TXT
    $filesTXT = glob('Listes/*.txt');
    $fileData = [];
    foreach ($filesTXT as $file) {
        $fileData[$file] = filemtime($file);
    }
    // Trier par date de modification (du plus récent au plus ancien)
    arsort($fileData);
    echo "<DIV class='divCarousselContainer'>";
    foreach ($fileData as $file => $fileInfo) {
        $theme = basename($file, '.txt');
        echo "<DIV class='divCarousselimageContainer' onclick='window.location.href = \"Crescendo 🎢.php?th=Listes/$theme\";'>"; 
        echo "\r\n<IMG height='80' title='".$theme."' src=\"".fctBingSrc($theme,200)."\">";
        //fctAfficheImage($theme,100);
            echo "<div class='divCarousseltextOverlay'>";
            echo $theme;
            echo "</div>";
        echo "</DIV>"; 
    }
    //print_r($fileData);
    echo "</DIV>"; 

    echo"</div>";
    //========= Caroussel (fin)

    if (!isset($_SESSION['php_glob'])) {
        $repertoire = __DIR__;
        $fichiersPHP = glob($repertoire .'/*.php');
        $_SESSION['php_glob'] = $fichiersPHP;
    }
    else {
        $fichiersPHP = $_SESSION['php_glob'];
    }

    echo "<DIV class=\"divThemeBoxChoice\">";
    // Vérifier si des fichiers PHP ont été trouvés
    if ($fichiersPHP !== false && !empty($fichiersPHP)) {
        // Afficher la liste des fichiers avec des liens
        foreach ($fichiersPHP as $fichier) {
            // Obtenir le nom du fichier
            $nomFichier = substr(basename($fichier),0,-4);
			if (substr($nomFichier,0, 3)!="fct" && substr($nomFichier,-5, 5)!="Verif" && $nomFichier!="index" && $nomFichier!="fonctions" && $nomFichier!="login")
				echo '<a href="' . $nomFichier . '.php"  class="MonLien">' . $nomFichier . '</a>';
        }
    } else {
        echo '<p>Aucun fichier PHP trouvé dans le répertoire.</p>';
    }
    echo "</DIV>";



    echo "<HR>";
    echo "<B>...les derniers évènements...</B>";
    $cheminFichier = "log/game.txt";
    // Lire toutes les lignes du fichier dans un tableau
    $lines = file($cheminFichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);    
    // Extraire les 5 dernières lignes
    $lines = array_reverse($lines);
    $lastFiveLines = array_slice($lines, 0, 5);  
    // Afficher les 5 dernières lignes
    echo "<TABLE WIDTH=100%>";
    foreach ($lastFiveLines as $line) {
        $infos = explode(';', $line);
        echo "<TR><TD><IMG WIDTH=20 src='img/avatar/".$infos[1].".png'</TD>";
        echo "<TD>".$infos[0]."</TD>";
        echo "<TD><SMALL>⭐".$infos[3]."</SMALL></TD>";
        echo "<TD><A href='".$infos[2].".php'>".urldecode($infos[2])."</A></TD>";
        echo "<TD><SMALL>";
        afficherDifferenceDate($infos[4]);
        echo "</SMALL></TD></TR>";        
    }
    echo "</TABLE>";
    echo "<HR>";

    echo "<B>...Top Players...</B>";
    // Chemin du fichier
    $cheminFichier = 'log/user.txt';
    // Lire le fichier et stocker les données dans un tableau
    $donnees = [];
    if (($handle = fopen($cheminFichier, 'r')) !== false) {
        while (($ligne = fgetcsv($handle, 1000, ':')) !== false) {
            $donnees[] = ['pseudo' => $ligne[0], 'avatar' => $ligne[2], 'score' => $ligne[3]];
        }
        fclose($handle);
    }
    // Trier le tableau par score de manière décroissante
    usort($donnees, function($a, $b) {
        return $b['score'] - $a['score'];
    });
    echo "<TABLE WIDTH=100%>";
    foreach ($donnees as $index => $joueur) {        
        echo "<TR><TD><BIG>" . ($index + 1) . "</BIG></TD>";
        echo "<TD><IMG WIDTH=20 src='img/avatar/".$joueur['avatar'].".png'</TD>";
        $joueurAffiche = $joueur['pseudo'];
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']==$joueur['pseudo']) {
                $joueurAffiche = "<b>".$joueur['pseudo']."</b>";
            }
        }
        echo "<TD>".$joueurAffiche."</TD>";
        echo "<TD><SMALL>⭐".$joueur['score']."</SMALL></TD>";
        echo "</TR>";        
    }
    echo "</TABLE>";
    echo "<HR>";

    // Obtenir la liste des noms de variables de session
    //$nomsDeVariablesDeSession = array_keys($_SESSION);
    // Afficher la liste
    //echo '<pre>';
    //print_r($nomsDeVariablesDeSession);
    //echo '</pre>';

    echo "\r\n<br><br><br><form method=\"post\" id=\"monFormulaire\" action=\"".$_SERVER['PHP_SELF']."\">";
    echo "<button class=\"MonButton\" type=\"submit\" name=\"recommencer\">Effacer tous les cookies 🍪</button>";
    echo "\r\n</form>";


?>


<BR><BR><BR>
<SMALL>Bounito 2024 ©</SMALL> 

</center>
</body>
</html>