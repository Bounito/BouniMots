<?php
// functions.php
session_start();


function fctListesTxt($Repertoire) {
	// ==============================  Thèmes
	// Récupérer la liste des fichiers TXT dans le sous-répertoire
	if (!isset($_SESSION[$Repertoire.'_glob'])) {
		// Si les données ne sont pas en session, les lire à partir du fichier
		$Listes_glob = glob($Repertoire.'*.txt');
		// Stocker les données en session pour les appels futurs
		$_SESSION[$Repertoire.'_glob'] = $Listes_glob;
	} else {
		// Si les données sont déjà en session, les récupérer
		$Listes_glob = $_SESSION[$Repertoire.'_glob'];
	}
	return $Listes_glob;
}


function fctRandMot($themeRepertoire) {
	// Vérifier si les données sont déjà enregistrées en session
	if (!isset($_SESSION[$themeRepertoire])) {
		$lignes_fichier = file($themeRepertoire, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$_SESSION[$themeRepertoire] = $lignes_fichier;
		// Stocker le nombre de lignes dans une autre variable de session
		$_SESSION[$themeRepertoire . '_count'] = count($lignes_fichier);
	} else {
		$lignes_fichier = $_SESSION[$themeRepertoire];
	}

	return $lignes_fichier[array_rand($lignes_fichier)];
}

function fctRandMots($ListeTxt,$NbMots) {
	if (!isset($_SESSION[$ListeTxt])) {
		$mots = file($ListeTxt, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$_SESSION[$ListeTxt] = $mots;
		$_SESSION[$ListeTxt . '_count'] = count($mots);
	}
	else {
		$mots = $_SESSION[$ListeTxt];
	}
	// Mélanger le tableau
	shuffle($mots);
	// Remplacer les apostrophes simples par des apostrophes courbes
	$mots = array_map(function($mot) {
		return str_replace("'", "’", $mot);
	}, $mots);
	// Sélectionner les premiers mots (différents)
	return array_slice($mots, 0, $NbMots);
}

function fctMotDansFichier($mot, $cheminFichier) {
	if (!isset($_SESSION[$cheminFichier])) {
		$mots = file($cheminFichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$_SESSION[$cheminFichier] = $mots;
	}
	else {
		$mots = $_SESSION[$cheminFichier];
	}

    foreach ($mots as $ligne) {
        if (str_to_noaccent($ligne) == str_to_noaccent($mot)) {
            return true;
        }
    }
    return false;
}


//====================================================================================
//====================================================================================
//====================================================================================
//====================================================================================

function fctAfficheEntete($titre) {
	fctAfficheHeaderStart($titre);
	fctAfficheBodyStart($titre);
}

function fctAfficheHeaderStart($titre) {

	echo "\r\n<!DOCTYPE html>";
	echo "\r\n<html lang=\"fr\">";
	echo "\r\n<head>";
	echo "\r\n	<meta charset=\"UTF-8\">";
	echo "\r\n	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
	echo "\r\n	<link rel=\"stylesheet\" href=\"styles.css\">";
	echo "\r\n	<title>".$titre."</title>";
	echo "\r\n	<script src=\"https://code.jquery.com/jquery-3.6.4.min.js\"></script>";

}

function fctAfficheBodyStart($titre) {
	echo "\r\n</head>";
	echo "\r\n<body>";
	echo "\r\n<script type=\"text/javascript\" src=\"scripts.js\"></script>";
	echo "\r\n<audio id=\"myAudio\"></audio>"; // Tag Audio
	echo "\r\n<div class=\"centered-div\">";
	echo "\r\n<TABLE BORDER=0 WIDTH=100%><TR><TD width=35>&nbsp;</TD><TD><H2 style='margin: 0 auto;'>".$titre."</H2></TD><TD width=130>&nbsp;</TD></TR></TABLE>";
}

function fctAfficheBtnBack() {
	echo "<div class=\"divBtnBack\">";
	echo "<IMG src='img/BackButton.png' WIDTH=50 onclick='window.location.href = \".\";'  />";
	echo "</div>";	
}
// =================================================================================
// =================================================================================

function fctLog($game) {

	if (isset($_SESSION['user'])) {
		$cheminFichier = 'log/game.txt';
		$nouvellesLignes = $_SESSION['user'].";".$_SESSION['avatar'].";".$game.";".$_SESSION['score'].";".date("Y-m-d H:i:s"). PHP_EOL;
		// Ajouter les nouvelles lignes à la fin du fichier
		$resultatEcriture = file_put_contents($cheminFichier, $nouvellesLignes, FILE_APPEND | LOCK_EX);
		// Vérifier le résultat de l'opération
		if ($resultatEcriture !== false) {
			//echo "Les nouvelles lignes ont été ajoutées avec succès.";
		} else {
			echo "Erreur lors de l'ajout des nouvelles lignes.";
		}
	}
}

// =================================================================================
// =================================================================================

function fctSaveScore() {

	if (isset($_SESSION['user'])) {
		// Chemin vers le fichier
		$fichierScores = 'log/user.txt';
	
		// Lire les lignes existantes du fichier
		$lignes = file($fichierScores, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	
		// Rechercher l'utilisateur dans les lignes existantes
		$trouve = false;
		foreach ($lignes as &$ligne) {
			$infos = explode(':', $ligne);
			if ($infos[0] == $_SESSION['user']) {
				// Mettre à jour le score
				$infos[3] = $_SESSION['score'];
				$ligne = implode(':', $infos);
				break;
			}
		}	
		// Écrire les lignes mises à jour dans le fichier
		file_put_contents($fichierScores, implode("\n", $lignes));
	}

}


function fctAfficheScore() {
	echo "<div id='starsContainer'></div>";
	echo "<div class=\"divFinalScore\" id=\"divFinalScore\">Score</div>";
	if (isset($_SESSION['score']))
	{
		$score = $_SESSION['score'];
	}
	else
	{
		$score = 0;
		$_SESSION['score'] = 0;
	}

	if (isset($_SESSION['WinStreak'])){
		$WinStreak = $_SESSION['WinStreak'];
	}
	else {
		$WinStreak = 0;
		$_SESSION['WinStreak'] = 0;
	}


	$scoreAjout = 0;
	// Vérifier si le formulaire a été soumis (1ere méthode)
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['scoreHidden'])) {
			$scoreAjout = $_POST['scoreHidden'];
			$score = $_POST['score'];

			if ($score<>$_SESSION['score']){
				if ($scoreAjout<0) {
					$WinStreak = 0;
				}		
				elseif ($scoreAjout>0) {
					$WinStreak++;
					if ($WinStreak % 5 === 0) {
						$scoreAjout = $scoreAjout + 50;
						$score = $score + 50;
						//echo "<SCRIPT>";
						//echo "playSound('brass-fanfare.mp3');";
						//echo "document.getElementById('divFinalScore').innerHTML = '<H3>🔥 Winstreak !</H3><IMG src=\"img/WS0.png\" height=20><IMG src=\"img/WS1.png\" height=30><IMG src=\"img/WS2.png\" height=40><IMG src=\"img/WS3.png\" height=50><IMG src=\"img/WS4.png\" height=60><H2>".$WinStreak." à la suite !</H2><H2 style=\"color: LimeGreen;\">+ 50 points !</H2>';";
						//echo "document.getElementById('divFinalScore').style.display = 'block';";
						//echo "</SCRIPT>";
					}
				}
			}

			$_SESSION['WinStreak'] = $WinStreak;
			$_SESSION['score'] = $score;
			fctSaveScore();
		}  
	}
	// Vérifier si $_SESSION['scoreAjout'] existe (2nd méthode)
	if (isset($_SESSION['scoreAjout']))
	{
		$scoreAjout = $_SESSION['scoreAjout'];
		$score = intval($score) + intval($scoreAjout);

		if ($scoreAjout<0) {
			$WinStreak = 0;
		}		
		elseif ($scoreAjout>0) {
			$WinStreak++;
			if ($WinStreak % 5 === 0) {
				$scoreAjout = $scoreAjout + 50;
				$score = $score + 50;
				//echo "<SCRIPT>";
				//echo "playSound('brass-fanfare.mp3');";
				//echo "document.getElementById('divFinalScore').innerHTML = '<H3>🔥 Winstreak !<BR><IMG src=\"img/WS0.png\" height=20><IMG src=\"img/WS1.png\" height=30><IMG src=\"img/WS2.png\" height=40><BR>5 à la suite !<BR>+50 points !</H3>';";
				//echo "document.getElementById('divFinalScore').style.display = 'block';";
				//echo "</SCRIPT>";
			}
		}
		$_SESSION['WinStreak'] = $WinStreak;
		$_SESSION['score'] = $score;
		fctSaveScore();
		unset($_SESSION['scoreAjout']);

	}



	echo "<script>";
	echo "setTimeout(function() {";
		echo "  var div = document.getElementById('divFinalScore');";
		echo "  div.style.display = 'none';";
		echo "}, 1000); // 1000 millisecondes = 1 seconde";
	echo "</script>";



	echo "<div class=\"divScore\" id=\"scoreDiv\">";


	//========================================= USER
	//=========Affichage du panneau
	echo "\r\n<div class=\"divThemeContainer\" id=\"divUserContainer\">";
		echo "<div class=\"divThemeHeader\">";
			echo "<TABLE WIDTH=100%><TR><TD ALIGN=LEFT><B>Profil</B></TD>";
			echo "<TD ALIGN=RIGHT style=\"cursor: pointer;\" onclick=\"document.getElementById('divUserContainer').style.display = 'none';\">✖</TD></TR>";
			echo "</TABLE>";
		echo "</div>";
		echo "\r\n<div class=\"divThemeBody\">";

		echo "\r\n<form id=\"loginForm\" action=\"login.php\" method=\"post\">";

		if (isset($_SESSION['user'])) {
			echo "<IMG src='img/avatar/".$_SESSION['avatar'].".png' />";
			echo "<BR>Tu es connecté en tant que <B>".$_SESSION['user']."</B><BR>";



			echo "\r\n    <button type=\"button\" class=\"MonButton\" onclick=\"afficherDiv(this,'formUser')\">Changer</button>";
			echo "\r\n<DIV id='formUser' style='display:none;'>";
		}
		else {
			echo "\r\n<DIV id='formUser'>";
		}
			echo "\r\n<BR>Connecte-toi ou crée un nouveu compte !<BR>";
			echo "\r\n    <label for=\"username\">Pseudo (3-10) :</label>";
			echo "\r\n    <input type=\"text\" id=\"username\" name=\"username\" pattern=\".{3,10}\" required>";
			echo "\r\n    <br>";
			echo "\r\n    <label for=\"password\">Password (3-10) :</label>";
			echo "\r\n    <input type=\"password\" id=\"password\" name=\"password\" pattern=\".{3,10}\" required>";
			echo "\r\n    <br>";
			echo "\r\n<div class=\"divThemeBoxChoice\">";
			for ($i=1;$i<17;$i++) {
				echo "\r\n    <label>";
				echo "\r\n    <img src='img/avatar/".$i.".png' width=50 >";
				echo "\r\n    <input type=\"radio\" name=\"avatar\" value=\"$i\" required>";
				echo "\r\n    </label>";
			}
				
			echo "\r\n</div>";
			echo "\r\n    <button type=\"button\" class=\"MonButton\" onclick=\"submitForm()\">Login</button>";
			echo "\r\n</DIV>";


		echo "\r\n</form>";

		echo "\r\n<script>";
		echo "\r\nfunction submitForm() {";
		echo "\r\n    // Récupérer les valeurs du formulaire";
		echo "\r\n    var username = document.getElementById('username').value;";
		echo "\r\n    var password = document.getElementById('password').value;";
		echo "\r\n    var avatar = $(\"input[name='avatar']:checked\").val();";
		echo "\r\n";

		echo "\r\n    // Vérifier si les champs sont bien renseignés";
		echo "\r\n    if (!avatar) {";
		echo "\r\n        alert(\"Veuillez choisir un avatar !\");";
		echo "\r\n        return;";
		echo "\r\n    }";
		
		echo "\r\n    // Vérifier la taille du nom d\'utilisateur";
		echo "\r\n    if (username.length < 3 || username.length > 10) {";
		echo "\r\n        alert(\"Le pseudo doit avoir entre 3 et 10 caractères.\");";
		echo "\r\n        return;";
		echo "\r\n    }";
		
		echo "\r\n    // Vérifier la taille du mot de passe";
		echo "\r\n    if (password.length < 3 || password.length > 10) {";
		echo "\r\n        alert(\"Le mot de passe doit avoir entre 3 et 10 caractères.\");";
		echo "\r\n        return;";
		echo "\r\n    }";


		echo "\r\n    // Envoyer les données au script PHP via AJAX";
		echo "\r\n    \$.ajax({";
		echo "\r\n        type: \"POST\",";
		echo "\r\n        url: \"login.php\",";
		echo "\r\n        data: { username: username, password: password, avatar: avatar  },";
		echo "\r\n        success: function(response) {";
		echo "\r\n            // Afficher la réponse du serveur (peut être un message de succès ou d'erreur)";
		echo "\r\n            alert(response);";
		echo "\r\n            window.location.href = \"\";";
		echo "\r\n        }";
		echo "\r\n    });";
		echo "\r\n}";
		echo "\r\n</script>";

		echo "</div>"; //fin divThemeBody
	echo "</div>";	//fin divThemeContainer

	echo "<TABLE BORDER=0><TR><TD VALIGN=MIDDLE>";
	// ===================================================Avatar
	if (isset($_SESSION['user'])) {
		echo "<IMG src='img/avatar/".$_SESSION['avatar'].".png' WIDTH=38 style='cursor: pointer;' onclick=\"afficherDivModal('divUserContainer');\" />";
	}
	else {
		echo "<IMG src='img/userGray.png' WIDTH=30 style='cursor: pointer;' onclick=\"afficherDivModal('divUserContainer');\" />";
	}
	echo "</TD><TD VALIGN=MIDDLE>";
	// ===================================================Winstreak
	echo "<div class='containerDoubleText'>";
	$imgWinStreak = $_SESSION['WinStreak'] % 5;
	echo "   <div class='bottom-text'><IMG src='img/WS".$imgWinStreak.".png' height=30></div>";
	echo "   <div class='top-text'>".$_SESSION['WinStreak']."</div>";
	echo "</div>";	
	// ===================================================ScoreAjout
	if ($scoreAjout<0) {
		echo "</TD><TD VALIGN=MIDDLE>";
		echo "<span style=\"color: OrangeRed;\">".$scoreAjout." </span>";		
	}		
	elseif ($scoreAjout>0) {
		echo "</TD><TD VALIGN=MIDDLE>";
		echo "<span style=\"color: LimeGreen;\">+".$scoreAjout." </span>";
	}
	$scoreAjout=0;
	echo "</TD><TD VALIGN=MIDDLE>";
	// ===================================================Score
	echo "⭐".$score;
	echo "</TD></TR></TABLE>";

	echo "</div>";

}
// =================================================================================
// =================================================================================
// =================================================================================
function fctAfficheProgressBar() {

	echo "\n<div class=\"progress-container\">";
	echo "\n<div class=\"progress-bar\" id=\"myBar\">";
	echo "\n</div>";
	echo "\n</div>";
	echo "\n<script>";
	echo "\nvar stopButtonClicked = false;";
	echo "\nvar scoreProgress;";
	echo "\nvar nbErrors = 0;";
	echo "\nfunction stopProgress() {";
	echo "\n    stopButtonClicked = true;";
	echo "\n}";
	echo "\ndocument.addEventListener(\"DOMContentLoaded\", function() {";
	echo "\n    var progressBar = document.getElementById(\"myBar\");";
	echo "\n    var duration = 20000; // Durée en millisecondes (10 secondes)";
	echo "\n    var interval = 50; // Intervalle de mise à jour de la barre en millisecondes";
	echo "\n    var startTime = new Date().getTime();";
	echo "\n    function updateProgressBar() {";
	echo "\n        if (stopButtonClicked) {";
	echo "\n            return; // Arrêter la progression si le bouton est cliqué";
	echo "\n        }";
	echo "\n        var currentTime = new Date().getTime();";
	echo "\n        var elapsedTime = currentTime - startTime;";
	echo "\n        var progress = 100 - (elapsedTime / duration) * 100;";
	echo "\n        progressBar.style.width = progress + '%';";
	echo "\n        scoreProgress = Math.round(progress / 5);";
	echo "\n        progressBar.innerText = scoreProgress + ' points';";
	echo "\n        if (elapsedTime < duration - 2000) {";
	echo "\n            setTimeout(updateProgressBar, interval);";
	echo "\n        }";
	echo "\n    }";
	echo "\n    updateProgressBar();";
	echo "\n});";
	echo "\n</script>";

}

// =================================================================================
// =================================================================================
// $mesthemeRepertoire = ["Listes/", "NomPrenom/", "Phrases/"];
function fctAfficheBtnTheme($themeRepertoires) {
	// Vérifier si le paramètre "theme" est présent dans l'URL
	if (isset($_GET['th'])) {
		if ($_GET['th']=="🎲") {
			$listeFichersTXT = $_SESSION['Listes/_glob'];
			$theme = $listeFichersTXT[array_rand($listeFichersTXT)];
			$theme = basename($theme, '.txt');
			$themeRepertoire = 'Listes/'.$theme;
			$_SESSION['Aleatoire'] = "true";
		}
		else {
			$themeRepertoire = $_GET['th'];
			unset($_SESSION['Aleatoire']);
		}    
	}
	else {
		$listeFichersTXT = glob('Listes/*.txt');
		$theme = $listeFichersTXT[array_rand($listeFichersTXT)];
		$theme = basename($theme, '.txt');
		$themeRepertoire = 'Listes/'.$theme;
		$_SESSION['Aleatoire'] = "true";
	}
	$theme = basename($themeRepertoire);
	//echo $themeRepertoire;


	//=========Affichage du Bouton
	echo "\r\n<div class=\"divBtnTheme\">";

	if (isset($_SESSION['Aleatoire'])) {
		$imgIcon = "img/ThemeButtonAleatoire.png";
	}
	else {
		$imgIcon = "img/ThemeButton.png";
	}

	echo "<IMG src='$imgIcon' WIDTH=40 onclick=\"afficherDivModal('divThemeContainer');\"  />";
	echo "</div>";
	//=========Affichage le panneau
	echo "\r\n<div class=\"divThemeContainer\" id=\"divThemeContainer\">";
		echo "<div class=\"divThemeHeader\" id=\"divThemeContainer\">";
			echo "<TABLE WIDTH=100%><TR><TD ALIGN=LEFT><B>Sélection d'un thème</B></TD>";
			echo "<TD ALIGN=RIGHT style=\"cursor: pointer;\" onclick=\"document.getElementById('divThemeContainer').style.display = 'none';\">✖</TD></TR>";
			echo "<TR><TD COLSPAN=2 style=\"border: 1px solid MidnightBlue; background-color: #E1E1EC; cursor: pointer;\" onclick=\"window.location.href = '?th=🎲';\">Thème Aléatoire 🎲</TD></TR>";
			echo "</TABLE>";
		echo "</div>";
		echo "\r\n<div class=\"divThemeBody\" id=\"divThemeContainer\">";

			$NbThemes = 0;		
			foreach ($themeRepertoires as $MonthemeRepertoire) {
				// Récupérer la liste des fichiers TXT dans le sous-répertoire
				if (!isset($_SESSION[$MonthemeRepertoire.'_glob'])) {
					$fichiers = glob($MonthemeRepertoire . '*.txt');
					$_SESSION[$MonthemeRepertoire.'_glob'] = $fichiers;
				} else {
					$fichiers = $_SESSION[$MonthemeRepertoire.'_glob'];
				}		
	
				echo "\r\n<fieldset class=\"fieldsetTheme\"><legend class=\"legendTheme\">".substr($MonthemeRepertoire,0,-1)." (".count($fichiers).") :</legend>";
				echo "\r\n<div class=\"divThemeBoxChoice\">";
				// Afficher une case à cocher pour chaque fichier
				foreach ($fichiers as $nomFichier) {
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
		
					echo "\r\n<div class=\"divThemeChoice\" onclick=\"window.location.href = '?th=".$MonthemeRepertoire.$nomFichier."';\" >";
					fctAfficheImage($nomFichier,75);
					echo "\r\n<BR>".$nomFichier;
					echo "\r\n<BR><SMALL>(".number_format($nombreDeMots, 0, ',', ' ')." mots)</SMALL>";
					$NbThemes++;
					echo "\r\n</div>";
				}
				echo "\r\n</div>";
				echo '</fieldset>';
			}
		echo "</div>";
	echo "</div>";

	return $themeRepertoire;
}

function fctAffichePiedPage() {
	echo "\r\n<BR><BR>";
	echo "\r\n<SMALL>Bounito 2024 ©<BR><A href=\"/.\" class=\"MonLien\">Retour Menu</A></SMALL>";
	echo "\r\n</div>"; //fin de la centred div
	echo "\r\n</body>";
	echo "\r\n</html>";
}



function fctAfficheGoogle($mot) {
    echo "<A href='https://www.google.com/search?q=$mot' target=_blank>";
	echo "<IMG title='Rechercher $mot sur Google' src='Google.ico'>";
	echo "</A>";	
}

function fctAfficheImage($mot,$taille) {
    echo "<IMG title='$mot' src='".fctBingSrc($mot,$taille)."'>";
}

function fctBingSrc($mot,$taille) {
	$mot = str_replace("&"," ",$mot);
	$mot = str_replace(" ","%20",$mot);
    return "https://th.bing.com/th?w=$taille&h=$taille&rs=1&c=0&p=0&mkt=fr-FR&cc=FR&setlang=fr&q=+".$mot;
}

function fctDefPetitLarousse($mot) {
	// URL de la page HTML
	$url = 'https://www.larousse.fr/dictionnaires/francais/'.$mot;
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$html = curl_exec($curl);
	curl_close($curl);

	$dom = new DOMDocument();
	libxml_use_internal_errors(true);
	$dom->loadHTML($html);
	libxml_clear_errors();
	$xpath = new DOMXPath($dom);
	
	// Trouver le premier span avec la classe "tlf_cdefinition"
	$Query = $xpath->query('//li[@class="DivisionDefinition"]');
	$spanElement = $Query->item(0);
	// Vérifier si le span a été trouvé
	if ($spanElement) {
		// Obtenir le contenu du span
		//$contenuSpan = str_replace("�"," ",utf8_decode($spanElement->nodeValue));
		$contenuSpan = mb_convert_encoding(utf8_decode($spanElement->nodeValue), 'UTF-8', 'auto');
		// Supprimer le préfixe "1. " en début de chaîne
		if (is_numeric(substr($contenuSpan,0,1))) {
			$contenuSpan = substr($contenuSpan,4);
		}

		// Afficher le contenu du span
		return $contenuSpan;
	} else {
		return "🤔 pas de définition pour $mot sur Larousse";
	}
}

function fctReturnDefinitionPetitLarousse($mot,$taille) {
        // URL de la page HTML
        $url = 'https://www.larousse.fr/dictionnaires/francais/'.$mot;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($curl);
        curl_close($curl);

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        $xpath = new DOMXPath($dom);
		
        // Trouver le premier span avec la classe "tlf_cdefinition"
		$Query = $xpath->query('//li[@class="DivisionDefinition"]');
		$spanElement = $Query->item(0);
        // Vérifier si le span a été trouvé
        if ($spanElement) {
            // Obtenir le contenu du span
            //$contenuSpan = str_replace("�"," ",utf8_decode($spanElement->nodeValue));
			$contenuSpan = mb_convert_encoding(utf8_decode($spanElement->nodeValue), 'UTF-8', 'auto');
			// Supprimer le préfixe "1. " en début de chaîne
			if (is_numeric(substr($contenuSpan,0,1))) {
				$contenuSpan = substr($contenuSpan,4);
			}

            // Afficher le contenu du span
            //return substr($contenuSpan,0,$taille)."<a href='$url' target=_blank>...</A>";
			return substr($contenuSpan,0,$taille)."...";
        } else {
            //return "🤔 pas de définition pour $mot sur <a href='$url' target=_blank>Larousse</A>";
			return "🤔 pas de définition pour $mot sur Larousse";
        }
	}


	function fctReturnDefinition($mot,$taille) {
        // URL de la page HTML
        $url = 'https://www.cnrtl.fr/definition/'.$mot;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($curl);
        curl_close($curl);

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        $xpath = new DOMXPath($dom);
		
        // Trouver le premier span avec la classe "tlf_cdefinition"
		$Query = $xpath->query('//span[@class="tlf_cdefinition"]');
        $spanElement = $Query->item(0);

        // Vérifier si le span a été trouvé
        if ($spanElement) {
            // Obtenir le contenu du span
            $contenuSpan = $spanElement->nodeValue;

            // Afficher le contenu du span
            return substr($contenuSpan,0,$taille)."<a href='$url' target=_blank>...</A>";
        } else {
            return "🤔 pas de définition pour $mot sur <a href='$url' target=_blank>CNRTL</A>";
        }
	}


function fctAfficheDefinition($mot,$taille) {
	$definition = fctReturnDefinition($mot,$taille);
	echo $definition;
}


function fctAfficheSynoAnto($mot,$nb_retours,$synonymie_antonymie) {
        // URL de la page HTML
        $url = 'https://www.cnrtl.fr/'.$synonymie_antonymie.'/'.$mot;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($curl);
        curl_close($curl);

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        $xpath = new DOMXPath($dom);
		
		if ($synonymie_antonymie == "synonymie")
        	$spanElements = $xpath->query('//td[@class="syno_format"]');
		else
			$spanElements = $xpath->query('//td[@class="anto_format"]');

		// Vérifier si des spans ont été trouvés
		if ($spanElements->length > 0) {
			foreach ($spanElements as $spanElement) {
				// Obtenir le contenu de chaque span
				$contenuSpan = $spanElement->nodeValue;

				// Afficher le contenu de chaque span
				echo $contenuSpan."<br>";
			}
		} else {
			echo "🤔 Pas de $synonymie_antonymie pour $mot sur <a href='$url' target='_blank'>CNRTL</a>";
		}
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


function afficherDifferenceDate($date) {
    // Convertir la date en objet DateTime
    $dateObj = new DateTime($date);

    // Date actuelle
    $now = new DateTime();

    // Calculer la différence entre les deux dates
    $diff = $now->diff($dateObj);

    // Afficher le résultat en fonction de la différence
    if ($diff->y > 0) {
        echo "Il y a {$diff->y} année(s)";
    } elseif ($diff->m > 0) {
        echo "Il y a {$diff->m} mois";
    } elseif ($diff->d > 0) {
        echo "Il y a {$diff->d} jour(s)";
    } elseif ($diff->h > 0) {
        echo "Il y a {$diff->h} heure(s)";
    } elseif ($diff->i > 0) {
        echo "Il y a {$diff->i} minute(s)";
    } else {
        echo "Il y a quelques instants";
    }
}





?>
