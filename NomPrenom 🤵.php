<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

// Vérifier si le paramètre "theme" est présent dans l'URL
if (isset($_GET['th'])) {
    $themeRepertoire = $_GET['th'];
    $_SESSION['themeRepertoire'] = $themeRepertoire;
}
else {
    if (isset($_SESSION['themeRepertoire'])) {
        $themeRepertoire = $_SESSION['themeRepertoire'];
    }        
    else {
        $themeRepertoire = "NomPrenom/Chanteurs Français";
    }
}
$theme = basename($themeRepertoire);
//echo $themeRepertoire;

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le thème depuis la checkbox
    $theme = $_POST['theme'];

}


fctAfficheEntete("Nom Prénom");
fctAfficheBtnBack();
$mesthemeRepertoire = ["NomPrenom/"];
fctAfficheBtnTheme($mesthemeRepertoire);


if (!isset($_SESSION[$themeRepertoire])) {
	// Lire le contenu du fichier
	$contenuFichier = file_get_contents($themeRepertoire.".txt");
	$_SESSION[$themeRepertoire] = $contenuFichier;
}
else {
	$contenuFichier = $_SESSION[$themeRepertoire];
}

// Vérifier si le fichier a été chargé avec succès
if ($contenuFichier !== false) {
    // Séparer les lignes du fichier
    $lignes = explode(PHP_EOL, $contenuFichier);

	// Initialiser un tableau pour stocker le nombre d'occurrences de chaque prénom
	$occurrences = array();

	// Parcourir chaque ligne et extraire les prénoms
	foreach ($lignes as $ligne) {
		$prenom = strtok($ligne, ' '); // Utilisez strtok pour obtenir le premier mot (prénom)
		if (isset($occurrences[$prenom])) {
			$occurrences[$prenom]++;
		} else {
			$occurrences[$prenom] = 1;
		}
	}

	// Filtrer les prénoms avec au moins 2 occurrences
	$prenomsEligibles = array_filter($occurrences, function ($occurrence) {
		return $occurrence >= 2;
	});

	// Choisir un prénom au hasard parmi les prénoms éligibles
	$prenomAuHasard = array_rand($prenomsEligibles);

	echo "<p id=\"texteCache\" style=\"font-size: 30px;\">Connaissez-vous des <B>".$theme."</B> avec le prénom <B>".$prenomAuHasard."</B> ?<BR>";
	echo "</p>";

	// Afficher les titres dans une div masquée
	echo '<BR><div id="titresSuivants" style="display: none;">';
	
	echo "<TABLE BORDER=0 WIDTH=100%>";
	$NbChansons =0;

	    // Afficher les couples Artistes - Titres correspondants pour le mot choisi
    foreach ($lignes as $ligne) {
            // Vérifier si le mot choisi est présent dans le titre
            if (strpos($ligne, $prenomAuHasard) === 0) {

				echo "<TR>";
                echo "<TD>".str_replace($prenomAuHasard,"<B>".$prenomAuHasard."</B>",$ligne)."</TD>";
				echo "<TD>";
				fctAfficheImage($ligne,100);
				echo "</TD></TR>";
				$NbChansons++;
            }
    }
	echo "</TABLE>";
	echo "</div>";
	// Ajouter un bouton pour afficher les titres suivants
	if ($NbChansons!=0)
		echo "<button class=\"MonButton\" onclick=\"document.getElementById('titresSuivants').style.display = 'block'; this.style.display = 'none'; \">&#x21E9; Afficher les ".$NbChansons." solutions &#x21E9;</button>";

	echo "<BR><button class=\"MonButton\" onclick=\"location.reload(true);\"><br />Un Autre !<br />&nbsp;</button>";

} else {
    // Gérer les erreurs de chargement du fichier
    echo "Erreur lors du chargement du fichier.";
}


fctAffichePiedPage();
?>