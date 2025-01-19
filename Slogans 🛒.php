<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

//Nombres de mots
$nbMots = 9;
$caseWidth = 100;

// Initialiser un tableau pour stocker les variables
$tableauDonnees = [];
// Liste des marques déjà sélectionnées
$marquesSelectionnees = [];
// Parcourir les lignes au hasard
for ($i=0;$i<$nbMots;$i++) {
    // Sélectionner une 'Marque' unique
    do {
        $sol = fctRandMot('Slogans.txt');
        $parties = explode("-", $sol);
        $marque = trim($parties[0]);
    } while (in_array($marque, $marquesSelectionnees));

    // Ajouter la 'Marque' à la liste des sélectionnées
    $marquesSelectionnees[] = $marque;
    // Ajouter les variables au tableau
    $tableauDonnees[] = ['Marque' => trim($parties[0]), 'Slogan' => trim($parties[1]), 'No' => $i];
}


$nbSlogans = $_SESSION['Slogans.txt_count'];

shuffle($tableauDonnees);

fctAfficheEntete("Quelle marque pour ce slogan ?");

fctAfficheBtnBack();

fctAfficheScore();
echo "<BR>";
fctAfficheProgressBar();


//Retrouver le mot gagnant :
foreach ($tableauDonnees as $ligne) {
    if ($ligne['No'] === 0) {
        //echo "Mot gagnant : " . $ligne[0] . "<br>";
		echo "<H2>";
		echo $ligne['Slogan'];
        $motChoisi = $ligne['Marque'];
		echo "</H2>";
        break; // Sortir de la boucle une fois que la ligne est trouvée
    }
}



echo "<DIV class='flex-container'>";
for ($i=0;$i<$nbMots;$i++) {
    echo "<DIV class='flex-3x3'>";
            echo "\n<DIV class='flex-bouton' id='sol".$i."' onclick=\"clicDivBouton(this,'".str_replace("'","\'",$motChoisi)."','".str_replace("'","\'",$tableauDonnees[$i]['Marque'])."','monFormulaire',0,8,".$_SESSION['WinStreak'].");\">";
//        echo "<DIV class='flex-bouton' id='sol".$i."' onclick=\"clicVerif(this,'".$tableauDonnees[$i]['No']."');\">";
            echo "<DIV style=\"justify-content: center;align-items: center;\">";
            echo "<IMG width=100% src='".fctBingSrc("Marque ".str_replace("'"," ",$tableauDonnees[$i]['Marque']),600)."' />";
            echo "</DIV>";
            echo "<span style=\"display: inline-block;\">".$tableauDonnees[$i]['Marque']."</span>";
        echo "</DIV>";
    echo "</DIV>";
}
echo "</DIV>";

echo "\r\n<div id=\"messageDiv\" class=\"centered-div\" style=\"font-size:20px;\">";
echo "\r\n</div>";

echo "<BR>$nbSlogans slogans en stock";

?>


<!-- Bouton pour recommencer -->
<form method="post" id="monFormulaire" action="">
    <button type="submit" class="MonButton" name="recommencer">Encore !</button>
	<input type='hidden' name='scoreHidden' id='scoreHidden' value='VIDE'>
	<input type='hidden' name='score' id='score' value='<?php echo $_SESSION['score']; ?>'>

</form>


<?php
fctAffichePiedPage();
?>