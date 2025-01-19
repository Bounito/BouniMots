<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

$sol=fctRandMots('FrMaj20k.txt',4);

// Créer un tableau de solutions (mot, thème + mot, GAGNE/PERDU)
$tableauDonnees = [
    [$sol[0], $sol[0],"PERDU"],
    [$sol[1], $sol[1],"PERDU"],
    [$sol[2], $sol[2],"PERDU"],
    [$sol[3], $sol[3],"GAGNE"],
];

shuffle($tableauDonnees);

fctAfficheEntete("Quel mot pour cette définition ?");

fctAfficheBtnBack();

fctAfficheScore();

//Retrouver le mot gagnant :
foreach ($tableauDonnees as $ligne) {
    if ($ligne[2] === "GAGNE") {
        //echo "Mot gagnant : " . $ligne[0] . "<br>";
		$definition = $ligne[0];
        break; // Sortir de la boucle une fois que la ligne est trouvée
    }
}
?>
    <script>
        function afficherLettreParLettre(texte, delai) {
            let index = 0;
            const afficherProchaineLettre = () => {
                if (index < texte.length) {
                    document.getElementById("divDefinition").innerHTML += texte.charAt(index);
                    index++;
                    setTimeout(afficherProchaineLettre, delai);
                }
            };
            afficherProchaineLettre();
        }



        // Appeler la fonction de manière asynchrone
        fetchDefinition();
        function fetchDefinition() {
            fetch('fctDefinition.php?mot=<?php echo urlencode($definition); ?>&taille=300')
            .then(response => response.text())
            .then(data => {
                // Mettre à jour la page avec le résultat
                //document.getElementById('divDefinition').innerHTML = '<small>' + data + '</small>';
                document.getElementById('divDefinition').innerHTML = '';
                // Utilisation de la fonction
                const texteComplet = data;
                const delaiEntreLettres = 100; // Délai en millisecondes
                afficherLettreParLettre(texteComplet, delaiEntreLettres);
            })
            .catch(error => console.error('Erreur :', error));
        }
    </script>
    <?php


echo "<DIV id='divDefinition' style='min-height:100px'>";
echo "<IMG src='load.gif'><small>Recherche de la définition<BR>Merci de patienter...</small>";
echo "</DIV>";





echo "<TABLE WIDTH=100%>";
echo "<TR>";
	echo "<TD WIDTH=50%>";
	fctAfficheImage($tableauDonnees[0][1],100);
	echo "<DIV class=\"MonDivBouton\" id='sol0' ;\" onclick=\"clicVerif(this,'".$tableauDonnees[0][2]."');\">";
	echo "<span>".$tableauDonnees[0][0]."</span></DIV>";
	echo "</TD>";
	echo "<TD>";
	fctAfficheImage($tableauDonnees[1][1],100);
	echo "<DIV class=\"MonDivBouton\" id='sol1' ;\" onclick=\"clicVerif(this,'".$tableauDonnees[1][2]."');\">";
	echo "<span>".$tableauDonnees[1][0]."</span></DIV>";
	echo "</TD>";
echo "</TR>";
echo "<TR>";
	echo "<TD>";
	fctAfficheImage($tableauDonnees[2][1],100);
	echo "<DIV class=\"MonDivBouton\" id='sol2' ;\" onclick=\"clicVerif(this,'".$tableauDonnees[2][2]."');\">";
	echo "<span>".$tableauDonnees[2][0]."</span></DIV>";
	echo "</TD>";
	echo "<TD>";
	fctAfficheImage($tableauDonnees[3][1],100);
	echo "<DIV class=\"MonDivBouton\" id='sol3' ;\" onclick=\"clicVerif(this,'".$tableauDonnees[3][2]."');\">";
	echo "<span>".$tableauDonnees[3][0]."</span></DIV>";
	echo "</TD>";
echo "</TR>";
echo "</TABLE>";
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
        if (boolWin === 'GAGNE') {
			if (document.getElementById('scoreHidden').value=='0') {
				document.getElementById('scoreHidden').value = '3';
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
			document.getElementById('scoreHidden').value = - 1;
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