<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";


$Listes_glob = fctListesTxt('Listes/');
shuffle($Listes_glob); //on mélange tout
$themePrincipal = $Listes_glob[0];
for ($i=1;$i<9;$i++) {
	$themeIntrus = $Listes_glob[$i];
}

$tableauDonnees = [];
// Ajouter l'élément 0 = Gagnant
$themeBase = basename($Listes_glob[0],".txt");
$motChoisi = fctRandMot($Listes_glob[0]);
$themeChoisi = $themeBase;
$tableauDonnees[] = [$themeBase, "GAGNE"];
// Boucler sur les index de 1 à 8
for ($i = 1; $i <9; $i++) {
    $themeBase = basename($Listes_glob[$i],".txt");
    // Ajouter l'élément au tableau de données
    $tableauDonnees[] = [$themeBase, "PERDU"];
}


shuffle($tableauDonnees);

fctAfficheEntete("Quel thème pour ce mot ?");
fctAfficheBtnBack();

fctAfficheScore();
echo "<BR>";
fctAfficheProgressBar();



echo "<H2>".$motChoisi."</H2>";

echo "<DIV id='imgCachee' style='display:none;'>";
fctAfficheImage(str_replace("'","",$motChoisi)." (".str_replace("'","",$themeChoisi).")",300);
echo "</DIV>";

echo "<DIV class='flex-container'>";
for ($i=0;$i<9;$i++) {
    echo "\n<DIV class='flex-3x3'>";
        echo "\n<DIV class='flex-bouton' id='sol".$i."' onclick=\"clicDivBouton(this,'$themeChoisi','".$tableauDonnees[$i][0]."','monFormulaire',0,8,".$_SESSION['WinStreak'].");\">";
            echo "\n<DIV style=\"justify-content: center;align-items: center;\">\n";
		    fctAfficheImage(str_replace("'","",$tableauDonnees[$i][0]),600);
		    echo "\n</DIV>";
		    echo "\n<span style=\"display: inline-block;\">".$tableauDonnees[$i][0]."</span>";
        echo "\n</DIV>";
    echo "\n</DIV>";
}
echo "\n</DIV>";


echo "\r\n<div id=\"messageDiv\" class=\"centered-div\" style=\"font-size:20px;\">";
echo "\r\n</div>";
?>

<!-- Bouton pour recommencer -->
<form method="post" id="monFormulaire" action="">
	<input type='hidden' name='scoreHidden' id='scoreHidden' value='VIDE'>
	<input type='hidden' name='score' id='score' value='<?php echo $_SESSION['score']; ?>'>
    <button type="submit" class="MonButton" name="Encore">Un autre</button>
</form>

<?php

fctAffichePiedPage();


?>