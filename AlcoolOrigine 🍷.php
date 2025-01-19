<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

fctAfficheEntete("Avec quoi fabrique-t-on cet alcool 🍷 ?");
fctAfficheBtnBack();

fctAfficheScore();
echo "<BR>";
fctAfficheProgressBar();

$reponsesDifferentes = false;

do {
    $listePhrases = fctRandMots('AlcoolOrigine.txt', 4);
    $reponsesArray = array();
    $reponsesDifferentes = true; // Supposons d'abord qu'il y a des réponses différentes

    foreach ($listePhrases as $phrase) {
        $parties = explode("#", $phrase);
        $choixReponse = trim($parties[1]);
        
        if (in_array($choixReponse, $reponsesArray)) {
            $reponsesDifferentes = false; // Si une réponse est dupliquée, définir comme faux
            break; // Sortir de la boucle car on a trouvé une duplication
        } else {
            $reponsesArray[] = $choixReponse; // Ajouter la réponse unique au tableau
        }
    }

} while (!$reponsesDifferentes); // Répéter tant qu'il y a des réponses dupliquées



$nbPhrases = $_SESSION['AlcoolOrigine.txt_count'];

//echo $listeFilms[0];

$winPhrase = $listePhrases[array_rand($listePhrases)];

$parties = explode("#", $winPhrase);
$winQuestion = trim($parties[0]);
$winReponse = trim($parties[1]);

//fctAfficheImage($film,50);

echo "<H3>";
echo $winQuestion;
fctAfficheGoogle($winQuestion);
echo "</H3>";

echo "\n<IMG src=\"".fctBingSrc($winQuestion." alcool",200)."\" >";



echo "\r\n<TABLE BORDER=0 WIDTH=100%><TR>";
// Boucler sur toutes les valeurs des mots au hasard
$nb = 0;
foreach ($listePhrases as $Phrase) {
    $parties = explode("#", $Phrase);
    $choixReponse = trim($parties[1]);
	$keyBing = $choixReponse;
	$keyBing = str_replace("&"," ",$keyBing);
	$keyBing = str_replace(" ","+",$keyBing);
	if ($nb==2)
		echo "</TR><TR>";
	echo "<TD width=50% ALIGN=CENTER>";
	
		echo "\n<DIV class='flex-bouton' id='sol".$nb."' onclick=\"clicDivBouton(this,'".str_replace("'","\'",$winReponse)."','".str_replace("'","\'",$choixReponse)."','monFormulaire',0,3,".$_SESSION['WinStreak'].");\">";
			echo "\n<IMG src=\"".fctBingSrc($keyBing,500)."\" >";
            echo $choixReponse;
			echo "\r\n<span class='flex-text' id=\"".$choixReponse."\"></span>";
		echo "</DIV>";
	echo "</TD>";
	$nb++;
}
echo "\r\n</TR></TABLE>";

echo "\r\n<BR><BR><div id=\"messageDiv\" class=\"centered-div\" style=\"font-size:20px;\">";

echo "\r\n</div>";

echo "<BR>$nbPhrases alcools en stock";

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