<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";


// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le thème depuis la checkbox
    $themeRepertoire = $_POST['themeRepertoire'];
    $theme = basename($themeRepertoire);
}



fctAfficheEntete("Trouvez le mot manquant !");
fctAfficheBtnBack();

if (!isset($theme))
{
    // Chemin du sous-répertoire
    $themeRepertoire = 'Phrases/Expressions';
    $theme="Expressions";
}

// Vérifier si les données sont déjà enregistrées en session
if (!isset($_SESSION[$themeRepertoire])) {
    // Si les données ne sont pas en session, les lire à partir du fichier
    $lignes_fichier = file($themeRepertoire.'.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$nb_lignes = count($lignes_fichier);
    // Stocker les données en session pour les appels futurs
    $_SESSION[$themeRepertoire] = $lignes_fichier;
	echo "<SMALL>".$nb_lignes." mots chargés en session</SMALL>";
} else {
    // Si les données sont déjà en session, les récupérer
    $lignes_fichier = $_SESSION[$themeRepertoire];
}

$lignechoisie = $lignes_fichier[array_rand($lignes_fichier)];
// Supprimer le saut de ligne à la fin de la ligne
$lignechoisie = rtrim($lignechoisie);

$lignechoisie = str_replace(';',' ', $lignechoisie);
// Séparer les mots de la ligne
$mots = explode(' ', $lignechoisie);
// Trouver le mot le plus long
$motPlusLong = '';
$longueurMax = 0;

// Initialiser un tableau pour stocker les mots éligibles
$motsEligibles = [];

foreach ($mots as $mot) {
    // Vérifier la longueur du mot et l'absence de '(' et ')'
    if (mb_strlen($mot, 'UTF-8') >= 4 && strpos($mot, "(") === false && strpos($mot, ")") === false) {
        // Ajouter le mot au tableau des mots éligibles
        $motsEligibles[] = $mot;
    }
}

// Choisir un mot au hasard parmi les mots éligibles
$motChoisi = $motsEligibles[array_rand($motsEligibles)];

// Remplacer le mot le plus long par des tirets
$ligneModifiee = str_replace($motChoisi, str_repeat('_', strlen($motChoisi)), $lignechoisie);

echo "<BR>Mot manquant dans le thème : <b>".$theme."</b>";

echo "<BR><p id=\"texteCache\" style=\"font-size: 30px;\">".$ligneModifiee."<BR>";
echo "</p>";

$keyBing = $lignechoisie;
$keyBing = str_replace(" - "," ",$keyBing);
$keyBing = str_replace("&"," ",$keyBing);
$keyBing = str_replace("-"," ",$keyBing);
$keyBing = str_replace(" ","+",$keyBing);
$keyBing = str_replace("++","+",$keyBing);

echo "<CENTER><IMG id=\"imageCache\" style=\"display: none; \" src=\"https://th.bing.com/th?w=300&h=300&q=".$keyBing."\" title=\"".$keyBing."\"></CENTER>";

$solutionGras = $lignechoisie;
$solutionGras = str_replace($motChoisi,"<B>".$motChoisi."</B>",$solutionGras);
$solutionGras = str_replace("'"," ",$solutionGras);
$solutionGras = str_replace("\""," ",$solutionGras);


echo "\r\n<button class=\"MonButton\"  onclick=\" document.getElementById('texteCache').innerHTML = '".$solutionGras."'; document.getElementById('imageCache').style.display = 'block'; this.style.display = 'none'; \"><br />&#x21E9; Solution &#x21E9;<br />&nbsp;</button>";

echo "\r\n<form method=\"post\" action=\"\" id=\"formBac\">";

echo "<button class=\"MonButton\" type=\"submit\"><br />Un autre !<br />&nbsp;</button>";

echo "\r\n<br><BR></CENTER>";

	echo "\r\n<div id='DivFiltreTheme' style=\"display: none;\">";

	$mesthemeRepertoire = ["NomPrenom/", "Phrases/"];
	$NbThemes = 0;
	
	foreach ($mesthemeRepertoire as $MonthemeRepertoire) {
		// ==============================  Thèmes
		// Récupérer la liste des fichiers TXT dans le sous-répertoire
		if (!isset($_SESSION[$MonthemeRepertoire.'_glob'])) {
			// Si les données ne sont pas en session, les lire à partir du fichier
			$Listes_glob = glob($MonthemeRepertoire . '*.txt');
			// Stocker les données en session pour les appels futurs
			$_SESSION[$MonthemeRepertoire.'_glob'] = $Listes_glob;
		} else {
			// Si les données sont déjà en session, les récupérer
			$Listes_glob = $_SESSION[$MonthemeRepertoire.'_glob'];
		}

		$fichiers = $Listes_glob;
		

		echo "<fieldset><legend>".substr($MonthemeRepertoire,0,-1)." (".count($fichiers).") :</legend>";
		echo "\r\n	<button class=\"MonButton\" type=\"submit\" name=\"btnsubmit\"> Valider </button>";

		// Afficher une case à cocher pour chaque fichier
		foreach ($fichiers as $nomFichier) {
			
			//echo $nomFichier;
			// Lire le fichier et compter le nombre de mots
			if (!isset($_SESSION['Count_'.$nomFichier])) {
				// Si les données ne sont pas en session, les lire à partir du fichier
				$nombreDeMots = count(file($nomFichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
				// Stocker les données en session pour les appels futurs
				$_SESSION['Count_'.$nomFichier] = $nombreDeMots;
			} else {
				// Si les données sont déjà en session, les récupérer
				$nombreDeMots = $_SESSION['Count_'.$nomFichier];
			}
			$nomFichier = basename($nomFichier, '.txt');

			echo "\r\n<div>";
			echo "\r\n<IMG id=\"imageCache\" \" title=\"".$nomFichier."\" src=\"https://th.bing.com/th?w=60&h=60&q=".str_replace(" ","+",$nomFichier)."\">";
			echo "\r\n<input type='radio' name='themeRepertoire' id='".$MonthemeRepertoire.$nomFichier."' value='".$MonthemeRepertoire.$nomFichier."'";
			if ($themeRepertoire==$MonthemeRepertoire.$nomFichier)
				echo " checked";
			echo ">";
			echo "\r\n<label for='".$MonthemeRepertoire.$nomFichier."'>$nomFichier (".number_format($nombreDeMots, 0, ',', ' ')." mots)</label>";
			$NbThemes++;
			echo "\r\n</div>";
		}
		echo "	<button class=\"MonButton\" type=\"submit\" name=\"btnsubmit\"> Valider </button>";
		echo '</fieldset>';
	}

	echo '</div><CENTER>';
echo "<button class=\"MonButton\" onclick='afficherDiv(this,\"DivFiltreTheme\")'>".$theme."<BR>Changer le thème (".$NbThemes.")</button>";
echo "<br><BR><BR><BR>";

echo "\r\n</form>";


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