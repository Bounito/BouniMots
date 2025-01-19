<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";
fctAfficheEntete("Contrepèteries");
fctAfficheBtnBack();

$contrepeterie=fctRandMot('Contrepeteries.txt');
// Séparer la contrepèterie en question et solution
$couple = explode(' – ', $contrepeterie, 2);
$question = $couple[0];
$solution = $couple[1];

// Fonction pour comparer les mots et mettre en gras les différences
function compareAndHighlight($str1, $str2) {
	
	$str1 = str_replace(",","",$str1);
	$str2 = str_replace(",","",$str2);
	$str1 = str_replace(".","",$str1);
	$str2 = str_replace(".","",$str2);
	$str1 = trim($str1);
	$str2 = trim($str2);
    $words1 = explode(' ', $str1);
    $words2 = explode(' ', $str2);

    $result = '';
    foreach ($words1 as $key => $word1) {
		$trouve=0;
		foreach ($words2 as $key => $word2) {
			if ($word1 == $word2)
				$trouve=1;
		}
		
//echo "<BR>word1=".$word1." (".$word2.")";
        if ($trouve==1) {
            $result .= $word1 . ' ';
        } else {
            $result .= '<b>' . $word1 . '</b> ';
        }
    }

    return $result;
}

// Comparer les mots dans les deux chaînes
$questionAvecDiff = compareAndHighlight($question, $solution);
$solutionAvecDiff = compareAndHighlight($solution, $question); // Inverser l'ordre pour prendre en compte les différences


// Afficher la question avec les différences en gras
//echo "<p>Question avec différences en gras : $questionAvecDiff</p>";

// Afficher la solution avec les différences en gras
//echo "<p>Solution avec différences en gras : $solutionAvecDiff</p>";





// Afficher la question
echo "<p style=\"font-size: 30px;\">$question</p>\n";


// Afficher l aide masquée
echo "<p id=\"aideCache\" style=\"display: none; font-size: 30px;\">".$questionAvecDiff."<BR>";
echo "</p>";
echo "<button class=\"MonButton\" onclick=\"document.getElementById('aideCache').style.display = 'block'; this.style.display = 'none'; \"><br />&nbsp; Aide &nbsp;<br />&nbsp;</button>";



// Afficher la solution masquée
echo "<p id=\"texteCache\" style=\"display: none; font-size: 30px;\">".$solutionAvecDiff."<BR>";
echo "</p>";
echo "<BR><button class=\"MonButton\" onclick=\"document.getElementById('texteCache').style.display = 'block'; this.style.display = 'none'; \"><br />&nbsp; Solution &nbsp;<br />&nbsp;</button>";



// Bouton pour recharger la page et afficher une autre contrepèterie
echo "<BR><BR><BR><button class=\"MonButton\" onclick=\"location.reload(true);\"><br />Un Autre !<br />&nbsp;</button>";
?>

<?php
fctAffichePiedPage();
?>