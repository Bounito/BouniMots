<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le thème depuis la checkbox
    if (isset($_POST['theme']))
        $theme = $_POST['theme'];

}

if (isset($_POST['checkboxFr'])) {
	$checkboxFr = $_POST['checkboxFr'];
	///echo $checkboxMasquerImages;
}
else {
	$checkboxFr = "false";
}


fctAfficheEntete("Occurence");
fctAfficheBtnBack();

/*
table, th, td {
    border-left: none;
    border-right: none;
    }
*/


	if (!isset($theme))
		$theme="MP3";


if (!isset($_SESSION['Occurrences'.$theme]))
{
    // Lire le contenu du fichier
    $contenuFichier = file_get_contents("Phrases/".$theme.".txt");

    // Vérifier si le fichier a été chargé avec succès
    if ($contenuFichier !== false) {
        // Séparer les lignes du fichier
        $lignes = explode(PHP_EOL, $contenuFichier);

        // Initialiser un tableau pour stocker les mots éligibles
        $motsEligibles = [];

        // Parcourir chaque ligne
        foreach ($lignes as $ligne) {
            // Utiliser une expression régulière pour extraire le titre de la chanson après la chaîne " - "
            preg_match('/ - (.*)/', $ligne, $matches);

            // Vérifier si le nom de la chanson a été trouvé
            if (isset($matches[1])) {

                $nomChanson = str_to_noaccent(trim(strtolower($matches[1])));
                // Nettoyer le nom de la chanson (supprimer les espaces et les caractères indésirables)
                $nomChanson = preg_replace('/[^\p{L}\p{N}\s]/u', '',$nomChanson );

                // Séparer les mots de la chanson
                $mots = explode(' ', $nomChanson);

                // Filtrer les mots selon les conditions
                $motsFiltres = array_filter($mots, function ($mot) {
                    $motsClesExclus = ['the', 'and', 'feat', 'remix', 'radio', 'edit', 'version', 'original','instrumental']; // Ajoutez d'autres mots clés ici
                    return mb_strlen($mot, 'UTF-8') > 3 && !in_array(strtolower($mot), $motsClesExclus);
                });

                // Ajouter les mots filtrés au tableau global
                $motsEligibles = array_merge($motsEligibles, $motsFiltres);
            }
        }

        // Compter le nombre d'occurrences de chaque mot
        $occurrences = array_count_values($motsEligibles);
        // Filtrer les mots avec au moins 4 occurrences
        $motsAvecMin4Occurrences = array_filter($occurrences, function ($occurrence) {
            return $occurrence >= 2;
        });

        // Initialiser la liste des mots avec occurrences dans un fichier
        $motsAvecOccurrences = [];

        foreach ($motsAvecMin4Occurrences as $mot => $occurrence) {
            $motsAvecOccurrences[] = "$mot: $occurrence";
            echo "<BR>$mot:$occurrence";
        }

        // Stocker les mots avec occurrences dans un fichier
        $cheminFichierMots = "Occurrences.txt";
        file_put_contents($cheminFichierMots, implode(PHP_EOL, $motsAvecOccurrences));

        echo "<HR>";
        //print_r($motsAvecMin4Occurrences);
 
        print_r($motsHorsFichier);
        if ($checkboxFr!="false") {

        }


        $_SESSION['Occurrences'.$theme] = $motsDansFichier;
        $_SESSION['Occurrences'.$theme.'lignes'] = $lignes;
    }
    else {
        // Gérer les erreurs de chargement du fichier
        echo "Erreur lors du chargement du fichier.";
    }
}
else
{
    $motsAvecMin4Occurrences = $_SESSION['Occurrences'.$theme];
    $lignes = $_SESSION['Occurrences'.$theme.'lignes'];
}

	
    // Sélectionner un mot au hasard parmi ceux avec au moins 4 occurrences
    $motChoisi = array_rand($motsAvecMin4Occurrences);

	echo "<p id=\"texteCache\" style=\"font-size: 30px;\">Titres de chanson contenant le mot : <B>".$motChoisi."</B><BR>";
	echo "\r\n</p>";
    fctAfficheImage($motChoisi,300);

	// Afficher les titres dans une div masquée
	echo "\r\n<BR><div id=\"titresSuivants\" style=\"display: none;text-align: center;\">";
	
	echo "\r\n<TABLE BORDER=1 style=\"margin: auto;\">";
	$NbChansons =0;

	    // Afficher les couples Artistes - Titres correspondants pour le mot choisi
    foreach ($lignes as $ligne) {
        preg_match('/(.*?) - (.*)/', $ligne, $matches);

        if (isset($matches[1]) && isset($matches[2])) {
            $artiste = trim($matches[1]);
            $titre = trim($matches[2]);

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
    echo "<input type='checkbox' id='checkboxFr' name='checkboxFr' value='true' ";
	if ($checkboxFr=="true")
		echo " checked";
	echo ">";
	echo "<label for='checkboxFr'>Seulement mots en français</label>";
	echo "<br>";

	echo "<BR><BR><button class=\"MonButton\" onclick=\"location.reload(true);\"><br />Un Autre !<br />&nbsp;</button>";

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

print_r($_SESSION['Occurrences'.$theme]);



fctAffichePiedPage();
?>