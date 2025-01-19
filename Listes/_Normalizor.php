<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le thème depuis la checkbox
    $theme = $_POST['theme'];
}


function str_to_noaccent($str)
{
    $url = $str;
    $url = preg_replace('#Ç#', 'C', $url);
    $url = preg_replace('#ç#', 'c', $url);
    $url = preg_replace('#è|é|ê|ë#', 'e', $url);
    $url = preg_replace('#È|É|Ê|Ë#', 'E', $url);
    $url = preg_replace('#à|á|â|ã|ä|å#', 'a', $url);
    $url = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $url);
    $url = preg_replace('#ì|í|î|ï#', 'i', $url);
    $url = preg_replace('#Ì|Í|Î|Ï#', 'I', $url);
    $url = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $url);
    $url = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $url);
    $url = preg_replace('#ù|ú|û|ü#', 'u', $url);
    $url = preg_replace('#Ù|Ú|Û|Ü#', 'U', $url);
    $url = preg_replace('#ý|ÿ#', 'y', $url);
    $url = preg_replace('#Ý#', 'Y', $url);
     
    return ($url);
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		    <style>

body {
  font-family: Arial;
}
</style>
</head>
<body>
<?php


	// Récupérer la liste des fichiers TXT
	$fichiers = glob('*.txt');

	// ==============================  Thèmes
	echo "\r\n<form method=\"post\" action=\"\" id=\"formBac\">";
	// ==================== Bouton 
	echo "\r\n	<button type=\"submit\" name=\"submit\">Sélectionner</button>";

	echo "<fieldset><legend>Listes (".count($fichiers)."):</legend>";

	// Afficher une case à cocher pour chaque fichier
    foreach ($fichiers as $nomFichierTXT) {
        // Extraire le nom du fichier sans l'extension
		//echo "<BR>nomFichierTXT=".$nomFichierTXT;		
        $nomFichier = basename($nomFichierTXT, '.txt');
        // Lire le fichier et compter le nombre de mots
        $nombreDeMots = count(file($nomFichierTXT, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

        echo "\r\n<div>";
        echo "\r\n<input type='radio' name='theme' value='$nomFichier'>";
        echo "<label for='theme'>$nomFichier (".number_format($nombreDeMots, 0, ',', ' ')." mots)";
        echo "</label></div>";
    }
	echo '</fieldset></form>';


if ($theme=='')
	echo "<H1>Merci de sélectionner un thème</H1>";
else
{
	// Chemin du fichier texte
	$cheminFichier = $theme.'.txt';
//echo "<BR>theme=".$theme;
	// Lire le contenu du fichier
	$listeMots = file($cheminFichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	echo "<H1>".$theme." (".count($listeMots).")</H1>";

echo "<textarea rows=\"30\" cols=\"30\" name=\"textorigin\">";
	foreach ($listeMots as $mot) {
		echo "\r\n".$mot;
	}
echo "</textarea>";


	// Fonction pour supprimer les mots au pluriel si le singulier existe
	function supprimerMotsPlurielAvecSingulier(&$listeMots)
	{
		// Créer un tableau pour stocker les mots au singulier
		$motsSinguliers = [];

		// Filtrer les mots ne se terminant pas par "S"
		$listeMots = array_filter($listeMots, function ($mot) use (&$motsSinguliers) {
			$derniereLettre = mb_substr($mot, -1);
			$motSingulier = rtrim($mot, 'S');

			// Vérifier si le singulier existe dans le tableau des singuliers
			if (in_array($motSingulier, $motsSinguliers)) {
				// Supprimer le mot au pluriel
				return false;
			}

			// Ajouter le singulier au tableau des singuliers
			$motsSinguliers[] = $motSingulier;

			// Conserver le mot au pluriel
			return true;
		});
	}

	// Appeler la fonction pour modifier la liste de mots
	supprimerMotsPlurielAvecSingulier($listeMots);


echo "<textarea rows=\"30\" cols=\"30\" name=\"textcible\">";
	foreach ($listeMots as &$mot) {
		echo "\r\n".strtoupper(str_to_noaccent($mot));
		$mot = strtoupper(str_to_noaccent($mot));
	}
echo "</textarea>";

if (file_put_contents($theme.'.txt', implode("\n", $listeMots)) === false) {
    echo "Erreur d'écriture dans le fichier.";
    // Afficher des informations sur l'erreur spécifique
    echo error_get_last()['message'];
} else {
    echo "Écriture réussie !";
}


	
	
	
	
}


?>
</body>
</html>
