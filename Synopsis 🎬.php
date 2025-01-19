<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

fctAfficheEntete("Synopsis");
fctAfficheBtnBack();

fctAfficheScore();
echo "<BR>";
fctAfficheProgressBar();


$listeFilms=fctRandMots('Synopsis.txt',4);
$nbSynopsis = $_SESSION['Synopsis.txt_count'];

// Remplacer les apostrophes par des espaces dans chaque élément du tableau
$listeFilms = array_map(function($mot) {
    return str_replace("'", " ", $mot);
}, $listeFilms);

$filmChoisi = $listeFilms[array_rand($listeFilms)];

$parties = explode("#", $filmChoisi);
$titreChoisi = trim($parties[0]);
$synopsis = trim($parties[1]);

//fctAfficheImage($film,50);

echo "<DIV id='divSynopsis' style='min-height:50px'>";
//echo $synopsis;
echo "</DIV>";


?>
<SCRIPT>
monDelai = 100;
function afficherLettreParLettre(texte, delai) {
    let index = 0;
    const afficherProchaineLettre = () => {
        if (index < texte.length) {
            document.getElementById("divSynopsis").innerHTML += texte.charAt(index);
            index++;
            setTimeout(afficherProchaineLettre, monDelai);
        }
    };
    afficherProchaineLettre();
}

// Utilisation de la fonction
const texteComplet = "<?php echo str_replace('"','\"',$synopsis); ?>";
const delaiEntreLettres = 80; // Délai en millisecondes
afficherLettreParLettre(texteComplet, delaiEntreLettres);
</SCRIPT>

<?

echo "\r\n<TABLE BORDER=0 WIDTH=100%><TR>";
// Boucler sur toutes les valeurs des mots au hasard
$nb = 0;
foreach ($listeFilms as $TitreSyno) {
    $parties = explode("#", $TitreSyno);
    $film = trim($parties[0]);
	$keyBing = $film." (affiche)";
	$keyBing = str_replace("&"," ",$keyBing);
	$keyBing = str_replace(" ","+",$keyBing);
	if ($nb==2)
		echo "</TR><TR>";
	echo "<TD width=50% ALIGN=CENTER>";
	
		echo "\n<DIV class='flex-bouton' id='sol".$nb."' onclick=\"clicDivBouton(this,'".str_replace("'","\'",$titreChoisi)."','".str_replace("'","\'",$film)."','monFormulaire',0,3,".$_SESSION['WinStreak'].");\">";
			echo "\n<IMG src=\"".fctBingSrc($keyBing,500)."\" >";
			echo "\r\n<span class='flex-text' id=\"".$film."\"></span>";
		echo "</DIV>";
	echo "</TD>";
	$nb++;
}
echo "\r\n</TR></TABLE>";

echo "\r\n<BR><BR><div id=\"messageDiv\" class=\"centered-div\" style=\"font-size:20px;\">";

echo "\r\n</div>";

echo "<BR>$nbSynopsis films en stock";

echo "\r\n<div id=\"messageDiv\" class=\"centered-div\" style=\"font-size:20px;\">";
echo "\r\n</div>";
?>


<!-- Bouton pour recommencer -->
<form method="post" id="monFormulaire" action="">

<?php
echo "\r\n<input type='hidden' name='scoreHidden' id='scoreHidden' value='VIDE'>";
echo "\r\n<input type='hidden' name='score' id='score' value='".$_SESSION['score']."'>";
?>

    <button type="submit" class="MonButton" name="recommencer">Encore !</button>
</form>

<?php
fctAffichePiedPage();
?>