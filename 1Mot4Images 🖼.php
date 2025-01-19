<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

fctAfficheEntete("1 Mot 4 Images");

fctAfficheBtnBack();

fctAfficheScore();
echo "<BR>";
fctAfficheProgressBar();

$mesthemeRepertoire = ["Listes/", "NomPrenom/", "Phrases/"];
$themeRepertoire = fctAfficheBtnTheme($mesthemeRepertoire);
$theme = basename($themeRepertoire);

if (isset($_GET['th'])) {
    echo "\r\n<form method=\"post\" id=\"monFormulaire\" action='".$_SERVER['PHP_SELF']."?th=".$_GET['th']."'>";
}
else {
    echo "\r\n<form method=\"post\" id=\"monFormulaire\" action='".$_SERVER['PHP_SELF']."?th=🎲'>";
}
    
echo "\r\n<input type='hidden' name='scoreHidden' id='scoreHidden' value='VIDE'>";
echo "\r\n<input type='hidden' name='score' id='score' value='".$_SESSION['score']."'>";


$motsAuHasard = fctRandMots($themeRepertoire.'.txt',4);
$motChoisi = $motsAuHasard[array_rand($motsAuHasard)];
echo "\r\n<div class=\"centered-div\">";
echo "\r\n<BR>Quelle image représente <b>$motChoisi</b> sur le thème <b>$theme</b> ?";
echo "\r\n</div>";

echo "\r\n\r\n<TABLE BORDER=0 WIDTH=100%><TR>";
// Boucler sur toutes les valeurs des mots au hasard
$nb = 0;
foreach ($motsAuHasard as $mot) {
	$keyBing = $mot." (".$theme.")";
	//$keyBing = $mot;
	$keyBing = str_replace("&"," ",$keyBing);
	$keyBing = str_replace(" ","+",$keyBing);
	if ($nb==2)
		echo "\r\n\r\n</TR><TR>";
	echo "\r\n\r\n<TD width=50% ALIGN=CENTER>";

		echo "\r\n<DIV class='flex-bouton' id='sol".($nb+1)."'  onclick=\"clicDivBouton(this,'".str_replace("'","\'",$motChoisi)."','".str_replace("'","\'",$mot)."','monFormulaire',1,4,".$_SESSION['WinStreak'].");\">";
			echo "\r\n<IMG title='".$mot."' src=\"".fctBingSrc($keyBing,500)."\">";
			echo "\r\n<span class='flex-text' id=\"".$mot."\"></span>"; // Texte par-dessus l'image
		echo "\r\n</DIV>";

	echo "\r\n\r\n</TD>";
	$nb++;
}
echo "\r\n</TR></TABLE>";
echo "\r\n<div id=\"messageDiv\" class=\"centered-div\" style=\"font-size:20px;\">";
echo "\r\nClic sur <b>$motChoisi</b>";
echo "\r\n</div>";

?>

<BR>
<BR>
<BR>

<HR>
<BR>
<BR>
<BR>

<!-- Bouton pour recharger la page -->
<button class="MonButton" type="submit"><br />Un autre mot !<br />&nbsp;</button>
<HR>


<input type="checkbox" id="checkboxThemeAleatoire" name="checkboxThemeAleatoire" value="true"
<?php
if ($checkboxThemeAleatoire=="true")
    echo " checked";
?>
  />
<label for="checkboxThemeAleatoire">Thème aléatoire</label>




<?php
fctAffichePiedPage();
?>