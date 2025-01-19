<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";


// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le thème depuis la checkbox
	if (isset($_POST['check_theme']))
    	$themeConserve = $_POST['check_theme'];
	if (isset($_POST['lettre']))
    	$lettreClic = $_POST['lettre'];
	$themeHidden = $_POST['themeHidden'];
	$lettreHidden = $_POST['lettreHidden'];
	$positionHidden = $_POST['positionHidden'];
	$saisiHidden = $_POST['saisiHidden'];
	$themesSelectionnes = $_POST['themes'];
}
else
{
	$themesSelectionnes = [ "Défauts", "Métiers Communs", "Qualités", "Pays", "Prénoms Féminins"];
}





?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Bac Express (by Boun)</title>
	    <style>
        /* Style CSS pour masquer le div au départ */
        #DivSolutions {
            display: none;
        }
		#DivFiltreTheme {
            display: none;
        }
		#DivExemples {
            display: none;
        }

body {
  font-family: Arial;
}
button {
  background-color: #007AFF;
  border: none;
  color: white;
  padding: px 10px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  border-radius: 20px;
}

        .tuile-lettre {
            display: inline-block;
            width: 30px;
            height: 30px;
            margin: 5px;
            text-align: center;
            line-height: 30px;
            border: 1px solid #ccc;
            cursor: pointer;
        }

        .lettre-utilisee {
            background-color: #eee;
            cursor: not-allowed;
        }

        /* Style du conteneur global */
        .conteneur {
            display: flex;
            flex-wrap: wrap;
            gap: 2px; /* Espace entre les paires titre-div */
            justify-content: center; /* Centrage horizontal */
        }

        /* Style de chaque paire titre-div */
        .bloc_theme {
			flex: 10; 
            position: relative;
            border: 2px solid #ccc;
			min-width: 60px;
            padding: 20px;
            border-radius: 20px;
            margin: 10px;
        }
        .bloc_lettre {
			flex: 1; 
            position: relative;
            border: 2px solid #ccc;
			min-width: 60px;
            padding: 20px;
            border-radius: 20px;
            margin: 10px;
        }
        /* Style du titre (superposition) */
        .titre {
			background-color: #E1E1EC;
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 10px;
            padding: 0 10px;
            z-index: 1;
        }

        /* Style du contenu */
        .contenu {
            font-size: 1em;
			overflow-wrap: break-word; /* Permet aux mots longs de se casser sur plusieurs lignes */
        }
</style>
</head>
<body>


    <?php
fctAfficheBtnBack();
//echo "<BR>themeConserve=".$themeConserve;
//echo "<BR>themeHidden=".$themeHidden;
//echo "<BR>lettreHidden=".$lettreHidden;
//echo "<BR>lettreClic=".$lettreClic;
//echo "<BR>positionHidden=".$positionHidden;
//echo "<BR>saisiHidden=".$saisiHidden;
//foreach ($themesSelectionnes as $ThemeS)
//	echo "<BR>ThemeS=".$ThemeS;

    // Chemin du sous-répertoire
    $sousRepertoire = 'Listes/';

	// Récupérer la liste des fichiers TXT dans le sous-répertoire
	if (!isset($_SESSION[$sousRepertoire.'_glob'])) {
		// Si les données ne sont pas en session, les lire à partir du fichier
		$fichiers = glob($sousRepertoire . '*.txt');
		// Stocker les données en session pour les appels futurs
		$_SESSION[$sousRepertoire.'_glob'] = $fichiers;
	} else {
		// Si les données sont déjà en session, les récupérer
		$fichiers = $_SESSION[$sousRepertoire.'_glob'];
	}

	$LstThemeComplet = $fichiers;
				
	if (isset($lettreClic) && $lettreClic!='')
	{
		$themeChoisi = $themeHidden;
		//echo "<SMALL>Lettre clic : $lettreClic</SMALL><BR>";
	}		
	else
		if (!isset($themeConserve))
		{
			if (isset($themesSelectionnes[0]))
			{
				$fichiers = $themesSelectionnes;
				//echo "<SMALL>La liste des thèmes est filtrée</SMALL><BR>";
			}
			else
			{
				$themesSelectionnes = $fichiers;
				//echo "<SMALL>Aucun thème sélectionné, je reselectionne tout...😋</SMALL><BR>";
			}

			// Choisir un thème au hasard
			$fichierChoisi = $fichiers[array_rand($fichiers)];
			// Extraire le nom du fichier sans l'extension
			$themeChoisi = basename($fichierChoisi, '.txt');
		}
		else
			$themeChoisi = $themeConserve;


    // Lire le fichier correspondant au thème choisi
    $cheminFichierTheme = "Listes/$themeChoisi.txt";
	$themeRepertoire = "Listes/".$themeChoisi;
    // Lire le fichier et compter le nombre de mots
	if (!isset($_SESSION[$themeRepertoire]))
	{
		$motsTheme = file($cheminFichierTheme, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		//echo "Chargement de ".$cheminFichierTheme." en session";
		$_SESSION[$themeRepertoire] = $motsTheme;
	}
	else
	{
		//echo "Données en session trouvées : ".$cheminFichierTheme;
		$motsTheme = $_SESSION[$themeRepertoire];
	}
	$nombreDeMots = count($motsTheme);
	
	
    // Choisir une lettre au hasard (par exemple, de A à Z)
	// Liste des lettres utilisées

	$lettresUtilisees = [];
	do {
		if (!isset($lettreClic) || $lettreClic=='')
			$lettreChoisie = chr(rand(65, 90));
		else
			$lettreChoisie = $lettreHidden;
		// Vérifier si la lettre a déjà été utilisée
		$lettreDejaUtilisee = in_array($lettreChoisie, $lettresUtilisees);
		if (!$lettreDejaUtilisee) {
			// Ajouter la lettre à la liste des lettres utilisées
			$lettresUtilisees[] = $lettreChoisie;
			// Filtrer les mots commençant par la lettre choisie
			$motsCommencantParLettre = array_filter($motsTheme, function ($mot) use ($lettreChoisie) {
				return mb_stripos($mot, $lettreChoisie) === 0;
			});
			$nombreDeMotsCommencantParLettre = count($motsCommencantParLettre);
			//if ($nombreDeMotsCommencantParLettre==0)
			//	echo "<SMALL>(Aucun mot pour la lettre ".$lettreChoisie.", je cherche une autre lettre)</SMALL><BR>";
		}
    } while ($nombreDeMotsCommencantParLettre==0);
	
	// ============ Exemples
	$motsCommencantPASParLettre = array_filter($motsTheme, function ($mot) use ($lettreChoisie) {
		return mb_stripos($mot, $lettreChoisie) !== 0;
		});
	$exemples = $motsCommencantPASParLettre[array_rand($motsCommencantPASParLettre)].", ".
	$motsCommencantPASParLettre[array_rand($motsCommencantPASParLettre)].", ".
	$motsCommencantPASParLettre[array_rand($motsCommencantPASParLettre)].", ".
	$motsCommencantPASParLettre[array_rand($motsCommencantPASParLettre)].", ".
	$motsCommencantPASParLettre[array_rand($motsCommencantPASParLettre)];

	echo "\r\n<CENTER>";
	
	echo "\r\n<form method=\"post\" action=\"\" id=\"formBac\">";
	
	// ================== THEME ==================

	echo '<div class="conteneur">';
	echo '	<div class="bloc_theme">';
	echo '		<div class="titre">Thème</div>';
	echo '		<div class="contenu">';
	echo "\r\n<span style=\"font-size:28px;color: #007AFF;\">$themeChoisi</span>";
	echo "\r\n<button onclick='afficherDiv(this,\"DivExemples\")'>?</button>";

	echo "\r\n<div id=\"DivExemples\"><SMALL>Exemples de mots de ce thème : <I>".$exemples."</I><BR><B>".number_format($nombreDeMots, 0, ',', ' ')." mots</B></SMALL></div><br>";
	
	echo "\r\n	<input type=\"checkbox\" id=\"check_theme\" name=\"check_theme\" value=\"".$themeChoisi."\" ";
	if ($themeConserve!="")
		echo " checked";
	echo ">";
	echo "\r\n	<label for=\"check_theme\"> <SMALL>Conserver ce thème</SMALL></label>";
	
	
	
	echo "		</div>";
	echo "	</div>";


	// ================== LETTRE ==================
	echo '	<div class="bloc_lettre">';
	echo '		<div class="titre">Lettre</div>';
	echo '		<div class="contenu">';
	echo "			<span style=\"font-size:80px;color: #007AFF;\">$lettreChoisie</span>";
	echo '		</div>';
	echo '	</div>';
	echo '</div>';

	if (!isset($lettreClic)) {
		$lettreClic="";
	}
		
	switch ($lettreClic) {
    case '':
		$position = 1; //La première lettre (0) est connue
		$saisi = $lettreChoisie;
        break;
    case '#':
		$position = 1; //Recommencer saisie ? idem début de partie ci-dessus
		$saisi = $lettreChoisie;
        break;
    case '§':                 //Effacer dernier caractère
		$position = $positionHidden;
		if ($position>1)
		{
			$saisi = substr($saisiHidden, 0, -1);
			$position--;
		}
		
        break;
	default :                 //Sasie d'une lettre du clavier
		$position = $positionHidden;
		$saisi = $saisiHidden.$lettreClic;
		$position++;
        break;
	}

	$motsCommencantParSaisi = array_filter($motsTheme, function ($mot) use ($saisi) {
	return mb_stripos($mot, $saisi) === 0;
	});

	


	//echo "	<BR>";
	echo "\r\n	<input type=\"hidden\" id=\"themeHidden\" name=\"themeHidden\" value=\"".$themeChoisi."\">";
	echo "\r\n	<input type=\"hidden\" id=\"lettreHidden\" name=\"lettreHidden\" value=\"".$lettreChoisie."\">";
	echo "\r\n	<input type=\"hidden\" id=\"lettre\" name=\"lettre\">";
	echo "\r\n	<input type=\"hidden\" id=\"saisiHidden\" name=\"saisiHidden\" value=\"".$saisi."\">";
	echo "\r\n	<input type=\"hidden\" id=\"positionHidden\" name=\"positionHidden\" value=\"".$position."\">";
		




	// ================ ZONE d'affichage des lettres du mot
	// Convertir tous les mots dans le tableau en majuscules
	$motsCommencantParLettreEnMajuscules = array_map('strtoupper', $motsCommencantParLettre);

	echo "<div id=\"clavier\" style=\"border:1px solid black;\">";
	echo "<div id=\"zone_mot\">";
	if(count($motsCommencantParSaisi)==0)
	{
		echo "<span style=\"font-size:40px;\">".$saisi."</span> (aucune correspondance 😪)";
		$gagne=-1;
	}
	else
		if (in_array($saisi, $motsCommencantParLettreEnMajuscules))
		{
			echo "<span style=\"font-size:40px;\">".$saisi." 🏆 Bravo !</span>";
			$gagne=1;
		}
		else
		{
			$gagne=0;
			echo "<span style=\"font-size:40px;\">".$saisi." _</span> (".count($motsCommencantParSaisi)." mots)";
		}
	echo "</div>";


	// Liste des lettres de l'alphabet ================ Lettres trouvées ?
	$listeLettresPossibles = '';
	// Extraire la deuxième lettre de chaque mot et la convertir en majuscules
	foreach ($motsCommencantParSaisi as $mot) {
            $letr = substr($mot, $position, 1);
			//echo "<BR>letr = ".$letr;
			// Forcer l'encodage en UTF-8
			$letr = utf8_encode($letr);
			//echo "<BR>letr = ".$letr;
			$letr = strtoupper(str_to_noaccent($letr));
			//echo "<BR>letr = ".$letr;
			//echo "<BR>letr = ".$letr." : ".stripos($listeLettresPossibles,$letr);
			if (!in_array($saisi, $motsCommencantParLettreEnMajuscules))
			{
				if (strpos($listeLettresPossibles, $letr) === false)
					$listeLettresPossibles .= $letr;
			}
			//echo "<BR>listeLettresPossibles = ".$listeLettresPossibles;
        }
	//echo "\r\n<BR>listeLettresPossibles = ".$listeLettresPossibles;
	
	if ($position>1)
	{
		echo "\r\n<button onclick='choisirLettre(\"§\")'>Effacer (&larr;)</button>";
		echo "\r\n<button onclick='choisirLettre(\"#\")'>Recommencer (ESC)</button>";
		echo "<BR>";
	}

	// ====================================================Clavier
	$lettresAlphabet = range('A', 'Z');
	if ($gagne!=1 && $gagne!=-1)
	{
		foreach ($lettresAlphabet as $lettre) {
			$classeLettre = '';
			if (isset($_SESSION['lettresDevinnees']) && in_array($lettre, $_SESSION['lettresDevinnees'])) {
				$classeLettre = 'lettre-utilisee';
			}
			echo "\r\n<div class='tuile-lettre $classeLettre' onclick='choisirLettre(\"$lettre\")'>$lettre</div>";
		}
		echo "\r\n<div class='tuile-lettre $classeLettre' onclick='choisirLettre(\" \")'>&nbsp;</div>";
	}
	/*
	for ($i = 0; $i < strlen($listeLettresPossibles); $i++) {
		$lettre = $listeLettresPossibles[$i];
		$classeLettre = in_array($lettre, $_SESSION['lettresDevinnees']) ? 'lettre-utilisee' : '';
		echo "<div class='tuile-lettre $classeLettre' onclick='choisirLettre(\"$lettre\")'>$lettre</div>";
	}
	*/
	echo "\r\n</div>";

	// =================== Question Facile !
	if ($nombreDeMotsCommencantParLettre>=10)
		echo "<BR>$nombreDeMotsCommencantParLettre possibilités... facile...😎";
	else
		echo "<BR>Seulement $nombreDeMotsCommencantParLettre possibilités... question difficile 🤓 !";

	// ===========================Afficher les mots commençant par la lettre choisie
    echo '</CENTER><div id="DivSolutions">';
	if (!empty($motsCommencantParLettre)) {
        echo "<p>Les $nombreDeMotsCommencantParLettre mots commençant par '$lettreChoisie' pour le thème $themeChoisi :</p>";
        echo "<ul>";
        foreach ($motsCommencantParLettre as $mot) {
            echo "\r\n<li>$mot</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Aucun mot trouvé pour la lettre '$lettreChoisie' dans le thème '$themeChoisi'.</p>";
    }
	echo "\r\n<BR><A href=\"#top\">Retour en haut de page</A></div><CENTER>";
	
	if ($nombreDeMotsCommencantParLettre==0)
		echo "<BR>Je ne trouve par de solution dans mes données... Clique sur 'Un autre SpeedBac !' ci-dessus...";
	

echo "<br>";
// Bouton pour rendre le div visible
echo "\r\n<button id=\"BoutonSolutions\" onclick='afficherDiv(this,\"DivSolutions\")'>Afficher les <br /><B><span style='font-size:32px;'>".$nombreDeMotsCommencantParLettre."</span></B><br /> mots solutions (²)</button>";

// ==================== Bouton Un Autre !
echo "\r\n	<button type=\"submit\" name=\"recommencer\">Un autre<br /><span style=\"font-size:30px;\">Bac Express</span><br />(Enter)</button>";

echo "<br><BR>";

	// ============================== Filtre Thèmes
	echo "\r\n</CENTER><div id='DivFiltreTheme'><HR>Filtrage des thèmes (".count($themesSelectionnes)."/".count($LstThemeComplet)."):<BR><BR>";
	
	echo "\r\n<input type='checkbox' onClick='toggle(this)' value='Selectionner tout'/><I><B>Selectionner Tout/Rien</B></I><BR>";
		
	// Afficher une case à cocher pour chaque fichier
    foreach ($LstThemeComplet as $nomFichierTXT) {
        // Extraire le nom du fichier sans l'extension
        $nomFichier = basename($nomFichierTXT, '.txt');

		// Lire le fichier et compter le nombre de mots
		if (!isset($_SESSION['Count_'.$nomFichier])) {
			// Si les données ne sont pas en session, les lire à partir du fichier
			$nombreDeMots = count(file($nomFichierTXT, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
			// Stocker les données en session pour les appels futurs
			$_SESSION['Count_'.$nomFichier] = $nombreDeMots;
		} else {
			// Si les données sont déjà en session, les récupérer
			$nombreDeMots = $_SESSION['Count_'.$nomFichier];
		}



        echo "\r\n<label>";
		echo "\r\n<IMG id=\"imageCache\" \" title=\"".$nomFichier."\" src=\"https://th.bing.com/th?w=60&h=60&q=".str_replace(" ","+",$nomFichier)."\">";
        echo "\r\n<input type='checkbox' name='themes[]' value='$nomFichier' ";
		if (in_array($nomFichier,$themesSelectionnes))
			echo " checked";
		echo ">";
        echo "$nomFichier (".number_format($nombreDeMots, 0, ',', ' ')." mots)";
        echo "</label><br>";
    }
	echo "	<button type=\"submit\" name=\"recommencer\"><br />Un autre Bac Express !<br />&nbsp;</button>";
	echo '</div><CENTER>';
echo "\r\n<button onclick='afficherDiv(this,\"DivFiltreTheme\")'>Filtrer les thèmes (".count($themesSelectionnes)."/".count($LstThemeComplet).")</button>";
echo "<br><BR><BR><BR>";


	echo "</form>";
	
	
	//echo "gagne=".$gagne;
	//echo "position=".$position;
?>




<script>
function rechargerPage() {
	location.reload(true); // Utilisez "true" pour forcer le rechargement depuis le serveur
}

// Fonction JavaScript pour rendre le div visible
function afficherDiv(button,mondiv) {
	var div = document.getElementById(mondiv);
	div.style.display = 'block'; // ou 'inline', 'flex', etc., selon le besoin
	button.parentNode.removeChild(button); //efface le bouton
}

function choisirLettre(lettre) {
	// Mettre à jour le champ de saisie avec la lettre choisie
	document.getElementById('lettre').value = lettre;
	// Soumettre automatiquement le formulaire
	document.getElementById('formBac').submit();
}

function toggle(source) {
  checkboxes = document.getElementsByName('themes[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}

// Ajoutez un écouteur d'événements "keydown" à l'objet window
window.addEventListener('keydown', function(event) {
    // Récupérez la touche enfoncée à partir de l'événement
	var touche = event.key;
	
	// Vérifiez si la touche est une lettre (A-Z)
	<?php
	if ($gagne==0)
		echo " if (/^[a-zA-Z]$/.test(touche)) {choisirLettre(touche.toUpperCase());} else if (touche === ' ') {choisirLettre(' ');} ";
	if ($position>=2)
	{
		echo " if (touche === 'Escape') {choisirLettre('#');} "; 
		echo " else if (touche === 'Backspace') {choisirLettre('§');} ";
	}
	echo " if (touche === 'Enter') {choisirLettre('');} "; 
	echo " if (touche === '²') {afficherDiv(\"foo\",\"DivSolutions\"); button.parentNode.removeChild(document.getElementById(\"BoutonSolutions\"));} "; 
	?>
});

</script>

<BR><BR><BR>
<HR>
Merci à <A href="https://www.bing.com/images" target=_blank>bing.com/images</A> !<BR>

<BR>
<SMALL>Bounito 2024 ©<BR><A href="/." class="MonLien">Retour Menu</A></SMALL> 
</CENTER>
</body>
</html>
