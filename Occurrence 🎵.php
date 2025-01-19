<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

fctAfficheEntete("Occurence");
fctAfficheBtnBack();

/*
table, th, td {
    border-left: none;
    border-right: none;
    }
*/

if (isset($_GET['mot'])) {
    $motChoisi = $_GET['mot'];
}

if (isset($_POST['nameBtnShuffle'])) {
    $motChoisi = explode(":",fctRandMot("OccurWords.txt"))[0];
}

if (isset($_POST['nameBtnChoice'])) {
    unset($motChoisi);
}



if (isset($motChoisi)) {

    echo "<p id=\"texteCache\" style=\"font-size: 30px;\">Titres de chanson contenant le mot : <B>".$motChoisi."</B><BR>";
	echo "\r\n</p>";
    fctAfficheImage($motChoisi,300);

	// Afficher les titres dans une div masquée
	echo "\r\n<BR><div id=\"titresSuivants\" style=\"display: none;text-align: center;\">";
	
	echo "\r\n<TABLE BORDER=1 style=\"margin: auto;\">";
	$NbChansons =0;
    $contenuFichier = file_get_contents("Phrases/MP3.txt");
    $lignes = explode(PHP_EOL, $contenuFichier);
	    // Afficher les couples Artistes - Titres correspondants pour le mot choisi
    foreach ($lignes as $ligne) {
        preg_match('/(.*?) - (.*)/', $ligne, $matches);

        if (isset($matches[1]) && isset($matches[2])) {
            $artiste = str_replace("'"," ",trim($matches[1]));
            $titre = str_replace("'"," ",trim($matches[2]));

            // Vérifier si le mot choisi est présent dans le titre
            if (stripos($titre, $motChoisi) !== false) {
                echo "\r\n<TR><TD style='border-right: none;'>";
                fctAfficheImage($artiste,100);
				echo "\r\n</TD>";
                echo "\r\n<TD style='border-left: none;border-right: none;'>".$artiste."</TD>";
                echo "<TD  style='border-left: none;border-right: none;' align=\"right\">".str_ireplace($motChoisi,"<B>".$motChoisi."</B>",$titre)."</TD>";
                echo "\r\n<TD style='border-left: none;'>";
                fctAfficheImage($artiste." ".$titre,100);
                echo "\r\n</TD>";
				$NbChansons++;
            }
        }
    }
	echo "\r\n</TABLE>";
	echo "\r\n</div>";
	// Ajouter un bouton pour afficher les titres suivants
	if ($NbChansons!=0) {
        echo "<button class=\"MonButton\" onclick=\"document.getElementById('titresSuivants').style.display = 'block'; this.style.display = 'none'; \">&#x21E9; Afficher les ".$NbChansons." titres &#x21E9;</button>";
    }
	
    echo "<form method='post' id='monFormulaire' action=''>";

    echo "<BR><BR><button class=\"MonButton\" type=\"submit\" name=\"nameBtnChoice\"><br />Choisir un autre mot<br />&nbsp;</button>";

	echo "<BR><BR><button class=\"MonButton\" type=\"submit\" name=\"nameBtnShuffle\"><br />Un Autre au hasard !<br />&nbsp;</button>";


} else {
    $contenuFichier = file_get_contents("OccurWords.txt");
    $lignesOccur = explode(PHP_EOL, $contenuFichier);

    // Fonction de comparaison pour le tri
    function comparerOccurrences($a, $b) {
        $occurrenceA = explode(":", $a)[1];
        $occurrenceB = explode(":", $b)[1];

        // Compare les occurrences de manière décroissante
        return intval($occurrenceB) - intval($occurrenceA);
    }

    // Tri du tableau en utilisant la fonction de comparaison
    usort($lignesOccur, 'comparerOccurrences');

    //shuffle($lignesOccur); //Mélange les lignes
    echo "Choisi un mot contenu dans un titre de chanson, les plus faciles en premier :";
    $difficulte = 500;
    echo "<DIV>";
    foreach ($lignesOccur  as $ligne) {
        $mot = explode(":",$ligne);
        if (intval($mot[1])<$difficulte-5) {
            echo "</DIV>";
            echo "<HR>";
            //echo "<BR>De $difficulte à ".$mot[1]." occurences : ";
            echo "<DIV class='divThemeBoxChoice'>";
            $difficulte = intval($mot[1]);
        }

        $fontSize = 14 + (intval($mot[1])/2);
        if ($fontSize>=50)
            $fontSize=50;       
        
        echo "<span style='padding:2px;font-size:".$fontSize."px;'>";
        
        echo "<a href='?mot=".$mot[0]."' style='color: #007BFF; text-decoration: none; transition: color 0.3s ease;' onmouseover=\"this.style.color='#0056b3'\" onmouseout=\"this.style.color='#007BFF'\">".$mot[0]."</a>";

        echo "</span>";
    }
    echo "</DIV>";
}


?>
<script>
// Fonction JavaScript pour rendre le div visible
function afficherDiv(button,mondiv) {
	var div = document.getElementById(mondiv);
	div.style.display = 'block'; // ou 'inline', 'flex', etc., selon le besoin
	button.parentNode.removeChild(button); //efface le bouton
}
</script>

<?php
fctAffichePiedPage();
?>