<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Bouton Révélateur</title>
</head>
<body>

<?php


function obtenirSynonymes($mot) {

	//echo '<BR>Mot='.$mot;

    // Construire l'URL
    $url = "https://crisco4.unicaen.fr/des/synonymes/$mot";
	echo '<BR><A href="'.$url.'" target=_blank>Mot</A>';

    // Initialiser cURL
    $ch = curl_init($url);

    // Configurer les options cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Exécuter la requête cURL
    $resultat = curl_exec($ch);

    // Vérifier s'il y a eu des erreurs
    if (curl_errno($ch)) {
        echo 'Erreur cURL : ' . curl_error($ch);
        return;
    }
    // Fermer la session cURL
    curl_close($ch);
    // Convertir le HTML en texte brut
    $MaChaine = strip_tags($resultat);
	// Supprimer les sauts de ligne
	$MaChaine = str_replace(["\r\n", "\r", "\n"], ' ', $MaChaine);
	// Remplacer les tabulations par des espaces
	$MaChaine = str_replace('\t', ' ', $MaChaine);
	// Supprimer les espaces insécables
	$MaChaine = str_replace("\xC2\xA0", ' ', $MaChaine); // Caractère UTF-8 pour l'espace insécable
	// Remplacer les différents types d'espaces par un simple espace
	$MaChaine = preg_replace('/[\pZ\pC]+/u', ' ', $MaChaine);
	// Supprimer les doubles espaces
	$MaChaine = preg_replace('/\s+/', ' ', $MaChaine);
	// Supprimer les espaces en début et fin de chaîne
	$MaChaine = trim($MaChaine);
	//echo "<BR><BR>MaChaine=".$MaChaine."<BR><BR>";
	

	$positionAntonymes = strpos($MaChaine, "antonymes");
	if ($positionAntonymes !== false) {
		//echo "<BR>Antonymes trouvés";
		$antonymes = substr($MaChaine, strpos($MaChaine, "antonymes")+ strlen("antonymes"));
		$antonymes = str_replace(',', ' ', $antonymes);
	}
		
		// Rechercher la position de la sous-chaîne "Classement des premiers synonymes"
		$positionMotif = strpos($MaChaine, "Classement des premiers synonymes");
		// Vérifier si le motif a été trouvé
		if ($positionMotif !== false) {
			echo "<BR>===== Synonymes trouvés";
			$synonymes = substr($MaChaine, $positionMotif + strlen("Classement des premiers synonymes"));
			//echo "<BR>".$synonymes;
			// Découper la chaîne en fonction des espaces
			$liste_mots = explode(' ', $synonymes);
			// Afficher les mots
			foreach ($liste_mots as $MonMot) {
				if (ctype_digit($MonMot)) {
					//echo " (Fin des synonymes)";
					break;
				} else {
					echo "<br>".$MonMot;
				}
			}
			if ($positionAntonymes !== false) {
				echo "<BR>===== Antonymes trouvés";
			}
			// Découper la chaîne en fonction des espaces
			$liste_mots = explode(' ', $antonymes);
			// Afficher les mots
			foreach ($liste_mots as $MonMot) {
				if ($MonMot=="Classement") {
					//echo " (Fin des antonymes)";
					break;
				} else {
					echo "<br>".$MonMot;
				}
			}			
			
			
		}
		else
		{
			echo "<BR>===== Pas trouvé";
		}
			
	
}


// Chemin vers le fichier texte
$cheminFichier = 'liste_francais.txt';

// Lire le contenu du fichier dans un tableau
$mots = file($cheminFichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$mots = array_map('trim', $mots); // Supprimer les espaces autour des mots

// Convertir l'encodage si nécessaire
foreach ($mots as &$mot) {
    $mot = mb_convert_encoding($mot, 'UTF-8', 'ISO-8859-1'); // Adapté à l'exemple de l'encodage ISO-8859-1
}
// Vérifier si le fichier contient des mots
if ($mots !== false && !empty($mots)) {
    // Choisir un mot au hasard
    $motAuHasard = $mots[array_rand($mots)];

    //echo "Mot choisi au hasard : $motAuHasard";
} else {
    echo "Le fichier ne contient aucun mot.";
}



?>

<!-- Texte initial caché -->
<p id="texteCache" style="display: none;">
<?php
echo $motAuHasard;
?>
</p>

<!-- Bouton de révélation -->
<button onclick="reveleTexte()">Révéler le mot</button>

<script>
function reveleTexte() {
    // Sélectionner l'élément texte
    var texte = document.getElementById("texteCache");

    // Changer le style pour afficher le texte
    texte.style.display = "block";
}
</script>


<?php

obtenirSynonymes($motAuHasard);


?>

</body>
</html>