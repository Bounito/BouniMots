<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

if (isset($_POST['checkboxMasquerImages'])) {
	$checkboxMasquerImages = $_POST['checkboxMasquerImages'];
	///echo $checkboxMasquerImages;
}
else {
	$checkboxMasquerImages = "false";
}




$Listes_glob = fctListesTxt('Listes/');
shuffle($Listes_glob); //on mélange tout
$themePrincipal = $Listes_glob[0];
$themeIntrus = $Listes_glob[1];


$sol[0]=fctRandMot($themePrincipal);
$sol[1]=fctRandMot($themePrincipal);
$sol[2]=fctRandMot($themePrincipal);
$sol[3]=fctRandMot($themeIntrus);

// Créer un tableau de solutions (mot, thème + mot, GAGNE/PERDU)
$tableauDonnees = [
    [$sol[0], $sol[0]." ".basename($themePrincipal,".txt"), "PERDU"],
    [$sol[1], $sol[1]." ".basename($themePrincipal,".txt"), "PERDU"],
    [$sol[2], $sol[2]." ".basename($themePrincipal,".txt"), "PERDU"],
    [$sol[3], $sol[3]." ".basename($themeIntrus,".txt"), "GAGNE"],
];

shuffle($tableauDonnees);

fctAfficheEntete("Qui est l'Intrus ?");
fctAfficheBtnBack();

echo "<TABLE WIDTH=100%>";
echo "<TR>";
	echo "<TD WIDTH=50%>";
	if ($checkboxMasquerImages=="false")
		fctAfficheImage($tableauDonnees[0][1],200);
	echo "<DIV class=\"MonDivBouton\" id='sol0' ;\" onclick=\"clicVerif(this,'".$tableauDonnees[0][2]."');\">";
	echo "<span>".$tableauDonnees[0][0]."</span></DIV>";
	echo "</TD>";
	echo "<TD>";
	if ($checkboxMasquerImages=="false")
		fctAfficheImage($tableauDonnees[1][1],200);
	echo "<DIV class=\"MonDivBouton\" id='sol1' ;\" onclick=\"clicVerif(this,'".$tableauDonnees[1][2]."');\">";
	echo "<span>".$tableauDonnees[1][0]."</span></DIV>";
	echo "</TD>";
echo "</TR>";
echo "<TR>";
	echo "<TD>";
	if ($checkboxMasquerImages=="false")
		fctAfficheImage($tableauDonnees[2][1],200);
	echo "<DIV class=\"MonDivBouton\" id='sol2' ;\" onclick=\"clicVerif(this,'".$tableauDonnees[2][2]."');\">";
	echo "<span>".$tableauDonnees[2][0]."</span></DIV>";
	echo "</TD>";
	echo "<TD>";
	if ($checkboxMasquerImages=="false")
		fctAfficheImage($tableauDonnees[3][1],200);
	echo "<DIV class=\"MonDivBouton\" id='sol3' ;\" onclick=\"clicVerif(this,'".$tableauDonnees[3][2]."');\">";
	echo "<span>".$tableauDonnees[3][0]."</span></DIV>";
	echo "</TD>";
echo "</TR>";
echo "</TABLE>";
?>

<!-- Bouton pour recommencer -->
<form method="post" id="monFormulaire" action="">
	<input type="checkbox" id="checkboxMasquerImages" name="checkboxMasquerImages" value="true"
		<?php
		if ($checkboxMasquerImages=="true")
		echo " checked";
		?>/>
	<label for="checkboxMasquerImages">Masquer images</label>
	<br>
    <button type="submit" class="MonButton" name="Encore">Un autre</button>
</form>

<audio id="myAudio"></audio>

<script>
    function goreload() {
        document.getElementById('monFormulaire').submit();
    }
    function clicVerif(clicDiv, boolWin) {
        if (boolWin === 'GAGNE') {
            clicDiv.style.backgroundColor = 'Lime';
            playSound('success.mp3');
            document.getElementById('sol0').onclick = null;
            document.getElementById('sol1').onclick = null;
            document.getElementById('sol2').onclick = null;
            document.getElementById('sol3').onclick = null;
            setTimeout(goreload, 1000);
        } else {
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