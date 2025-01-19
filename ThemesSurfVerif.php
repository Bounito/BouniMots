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





	if ($theme=='')
	{
		echo "<H1>Merci de sélectionner un thème</H1>";
	
	
		// Récupérer la liste des fichiers TXT
		$fichiers = glob('*/*.txt', GLOB_BRACE);

		// ==============================  Thèmes
		echo "\r\n<form method=\"post\" action=\"\" id=\"formBac\">";
		// ==================== Bouton 
		echo "\r\n	<button type=\"submit\" name=\"submit\">Sélectionner</button>";

		echo "<fieldset><legend>Listes (".count($fichiers)."):</legend>";

		// Afficher une case à cocher pour chaque fichier
		foreach ($fichiers as $nomFichierTXT) {
			// Extraire le nom du fichier sans l'extension
			//echo "<BR>nomFichierTXT=".$nomFichierTXT;		
			//$nomFichier = basename($nomFichierTXT, '.txt');
			// Lire le fichier et compter le nombre de mots
			$nombreDeMots = count(file($nomFichierTXT, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

			echo "\r\n<div>";
			
			$keyBing = basename($nomFichierTXT, '.txt');
			$keyBing = str_replace(" - "," ",$keyBing);
			$keyBing = str_replace("&"," ",$keyBing);
			$keyBing = str_replace("-"," ",$keyBing);
			$keyBing = str_replace(" ","+",$keyBing);
			$keyBing = str_replace("++","+",$keyBing);
			echo "<IMG id=\"imageCache\" src=\"https://th.bing.com/th?w=100&h=100&q=".$keyBing."\" title=\"".$keyBing."\">";
			
			echo "\r\n<input type='radio' name='theme' value='$nomFichierTXT'>";
			echo "<label for='theme'>$nomFichierTXT (".number_format($nombreDeMots, 0, ',', ' ')." mots)";
			echo "</label></div>";
		}
		echo '</fieldset></form>';
	}
	else
	{
		// Chemin du fichier texte
		$cheminFichier = $theme;
	//echo "<BR>theme=".$theme;
		// Lire le contenu du fichier
		$listeMots = file($cheminFichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		echo "<H1>".$theme." (".count($listeMots).")</H1>";

	echo "<textarea rows=\"30\" cols=\"100\" name=\"textcible\">";
		foreach ($listeMots as &$mot) {
			echo "\r\n".strtoupper(str_to_noaccent($mot));
			$mot = strtoupper(str_to_noaccent($mot));
		}
	echo "</textarea>";

	$limit = 200;
	echo "<H3>Les $limit premiers...</H3>";
	// Limiter à 30 mots

	$themeBase = basename($theme, ".txt");
	// Afficher les 30 premiers mots
	for ($i = 0; $i < min($limit, count($listeMots)); $i++) {
		
		$keyBing = $listeMots[$i]." (".$themeBase.")";
		$keyBing = str_replace(" - "," ",$keyBing);
		$keyBing = str_replace("&"," ",$keyBing);
		$keyBing = str_replace("-"," ",$keyBing);
		$keyBing = str_replace(" ","+",$keyBing);
		$keyBing = str_replace("++","+",$keyBing);
		echo "<BR><IMG id=\"imageCache\" src=\"https://th.bing.com/th?w=300&h=300&q=".$keyBing."\" title=\"".$keyBing."\">";
		echo "\r\n" . $listeMots[$i];
	}



	
	
	
	
}


?>
<BR><BR><BR>
<SMALL>Bounito 2024 ©<BR><A class="MonLien" href="/.">Retour Menu</A></SMALL>

</center>
</body>
</html>