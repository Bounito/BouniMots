<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";


// Initialiser un tableau pour stocker les variables
$tableauDonnees = [];
// Liste des marques déjà sélectionnées
$marquesSelectionnees = [];
// Parcourir les lignes au hasard
for ($i=0;$i<4;$i++) {
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

//Retrouver le mot gagnant :
foreach ($tableauDonnees as $ligne) {
    if ($ligne['No'] === 0) {
        //echo "Mot gagnant : " . $ligne[0] . "<br>";
		echo "<H1>";
		echo $ligne['Slogan'];
		echo "</H1>";
        break; // Sortir de la boucle une fois que la ligne est trouvée
    }
}




echo "<TABLE WIDTH=100%>";
echo "<TR>";
	echo "<TD WIDTH=50%>";	
	echo "<DIV class=\"MonDivBouton\" style=\"height: 180px;\" id='sol0' ;\" onclick=\"clicVerif(this,'".$tableauDonnees[0]['No']."');\">";
	echo "<DIV style=\"justify-content: center;display: flex;align-items: center;height: 150px;\">";
	fctAfficheImage("Marque ".str_replace("'","",$tableauDonnees[0]['Marque']),150);
	echo "</DIV>";
	echo "<span style=\"display: inline-block;\">".$tableauDonnees[0]['Marque']."</span></DIV>";
	echo "</TD>";
	echo "<TD>";
	echo "<DIV class=\"MonDivBouton\" style=\"height: 180px;\" id='sol1' ;\" onclick=\"clicVerif(this,'".$tableauDonnees[1]['No']."');\">";
	echo "<DIV style=\"justify-content: center;display: flex;align-items: center;height: 150px;\">";
	fctAfficheImage("Marque ".str_replace("'","",$tableauDonnees[1]['Marque']),150);
	echo "</DIV>";
	echo "<span style=\"display: inline-block;\">".$tableauDonnees[1]['Marque']."</span></DIV>";
	echo "</TD>";
echo "</TR>";
echo "<TR>";
	echo "<TD>";
	echo "<DIV class=\"MonDivBouton\" style=\"height: 180px;\" id='sol2' ;\" onclick=\"clicVerif(this,'".$tableauDonnees[2]['No']."');\">";
	echo "<DIV style=\"justify-content: center;display: flex;align-items: center;height: 150px;\">";
	fctAfficheImage("Marque ".str_replace("'","",$tableauDonnees[2]['Marque']),150);
	echo "</DIV>";
	echo "<span style=\"display: inline-block;\">".$tableauDonnees[2]['Marque']."</span></DIV>";
	echo "</TD>";
	echo "<TD>";
	echo "<DIV class=\"MonDivBouton\" style=\"height: 180px;\" id='sol3' ;\" onclick=\"clicVerif(this,'".$tableauDonnees[3]['No']."');\">";
	echo "<DIV style=\"justify-content: center;display: flex;align-items: center;height: 150px;\">";
	fctAfficheImage("Marque ".str_replace("'","",$tableauDonnees[3]['Marque']),150);
	echo "</DIV>";
	echo "<span style=\"display: inline-block;\">".$tableauDonnees[3]['Marque']."</span></DIV>";
	echo "</TD>";
echo "</TR>";
echo "</TABLE>";

echo "<BR>$nbSlogans slogans en stock";

?>



<!-- Bouton pour recommencer -->
<form method="post" id="monFormulaire" action="">
    <button type="submit" class="MonButton" name="recommencer">Encore !</button>
	<input type='hidden' name='scoreHidden' id='scoreHidden' value='0'>
	<input type='hidden' name='score' id='score' value='<?php echo $_SESSION['score']; ?>'>

</form>

<audio id="myAudio"></audio>

<script>
    function goreload() {
        document.getElementById('monFormulaire').submit();
    }
    function clicVerif(clicDiv, boolWin) {
        if (boolWin === '0') {
			if (document.getElementById('scoreHidden').value=='0') {
				document.getElementById('scoreHidden').value = '+3';
			}
			else {
				document.getElementById('scoreHidden').value = '-1';
			}
			document.getElementById('score').value = parseInt(document.getElementById('score').value,10) + parseInt(document.getElementById('scoreHidden').value,10);
			
            clicDiv.style.backgroundColor = 'Lime';
            playSound('success.mp3');
            document.getElementById('sol0').onclick = null;
            document.getElementById('sol1').onclick = null;
            document.getElementById('sol2').onclick = null;
            document.getElementById('sol3').onclick = null;
            setTimeout(goreload, 1000);
        } else {
			document.getElementById('scoreHidden').value = -1;
            clicDiv.style.backgroundColor = 'OrangeRed';
            clicDiv.onclick = null; // Désactiver le clic pour la div actuelle
            playSound('error.mp3');
        }
    }
	function playSound(fileName) {
		var audio = document.getElementById('myAudio');
		audio.src = 'sound/' + fileName;
		audio.play();
	}

</script>

<?php
fctAffichePiedPage();
?>