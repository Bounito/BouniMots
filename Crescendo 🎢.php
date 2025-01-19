<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";


fctAfficheEntete("Crescendo 🎢 ");

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

if (isset($_SESSION['nbCrescendo'])) {
	$nbCrescendo = $_SESSION['nbCrescendo'];
	if ($_SESSION['score'] > $_SESSION['scoreCrescendo']) {
		$nbCrescendo++;
	} else {
		$nbCrescendo = 2;
	}
	
} else {
	$nbCrescendo = 2;
}
$_SESSION['nbCrescendo'] = $nbCrescendo;
$_SESSION['scoreCrescendo'] = $_SESSION['score'];


$motsAuHasard = fctRandMots($themeRepertoire.'.txt',$nbCrescendo);
$motChoisi = $motsAuHasard[array_rand($motsAuHasard)];
echo "\r\n<div class=\"centered-div\">";
echo "\r\n<BR>Quelle image représente <b>$motChoisi</b> sur le thème <b>$theme</b> ?";
echo "\r\n</div>";

echo "\n<DIV class='flex-container'>";
for ($i=0;$i<$nbCrescendo;$i++) {
	$bing = $motsAuHasard[$i]." (".$theme.")";
	$mot = str_replace("'","\'",$motsAuHasard[$i]);
	$motChoisi = str_replace("'","\'",$motChoisi);
    echo "\n<DIV class='flex-item'>";
        echo "\n<DIV class='flex-bouton' id='sol".$i."' onclick=\"clicDivBouton(this,'$motChoisi','$mot','monFormulaire',0,".($nbCrescendo-1).",".$_SESSION['WinStreak'].");\">";
            echo "\n<IMG title=\"$bing\" src='".fctBingSrc($bing,600)."' />";
			echo "\n<span class='flex-text' id='".$mot."'></span>"; // Texte par-dessus l'image
        echo "\n</DIV>";
    echo "\n</DIV>";
}
echo "\n</DIV>";


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