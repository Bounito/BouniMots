<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";
fctAfficheEntete("Remets l'expression dans le bon ordre");
fctAfficheBtnBack();


fctAfficheScore();

do {
    $lignechoisie = fctRandMot('Phrases/Expressions.txt');
} while (str_word_count($lignechoisie)<3);

$mots = explode(' ', $lignechoisie);
$motsDansOrdre = $mots;
do {
    shuffle($mots);
} while ($motsDansOrdre == $mots);

// Afficher les mots mélangés avec des boutons
foreach ($mots as $mot) {
    echo "<button class=\"mot-bouton\" onclick=\"placerMot(this)\">$mot</button>\n";
}

// Convertir le tableau des mots dans l'ordre en une chaîne pour JavaScript

//echo "<BR>motsCorrects = ".implode(' ', $motsDansOrdre);
//echo "<BR>mots = ".json_encode(implode(' ', $mots));
// Ajouter une zone de solution
echo '<div id="zoneSolution" style="font-size:30px;"></div>';
echo '<div id="message" style="font-size:30px;"></div>';

// Ajouter un script JavaScript
echo '<script>
    var motsPlaces = [];
	var motsCorrects = "' . implode(' ', $motsDansOrdre) . ' ";
    function goreload() {
        document.getElementById("monFormulaire").submit();
    }
    function placerMot(bouton) {
        var texteMot = bouton.innerHTML;
        // Ajouter le mot dans la zone de solution		
        document.getElementById("zoneSolution").innerHTML += texteMot + " ";
        // Désactiver le bouton une fois que le mot est placé
        bouton.disabled = true;
        // Ajouter le mot à la liste des mots placés
        motsPlaces.push(texteMot);
//afficherMessage(motsCorrects)
		if (document.getElementById("zoneSolution").innerHTML.length>=motsCorrects.length) {
            // Vérifier si les mots sont dans le bon ordre
//afficherMessage(document.getElementById("zoneSolution").innerHTML.length+" >= "+motsCorrects.length);
//afficherMessage(document.getElementById("zoneSolution").innerHTML)
//afficherMessage(motsPlaces);
            if (document.getElementById("zoneSolution").innerHTML === motsCorrects) {
                afficherMessage("🏆 Bravo, vous avez réussi !");
				document.getElementById("boutonCache").style.display = "none";
                document.getElementById("scoreHidden").value = "+3";
                document.getElementById("score").value = parseInt(document.getElementById("score").value,10) + parseInt(document.getElementById("scoreHidden").value,10);
                playSound("success.mp3");
                setTimeout(goreload, 1000);
            } else {
                afficherMessage("😪 Dommage, les mots ne sont pas dans le bon ordre. On recommence !");
				// Réactiver tous les boutons et vider la zone de solution
				var boutons = document.getElementsByClassName("mot-bouton");
				for (var i = 0; i < boutons.length; i++) {
					boutons[i].disabled = false;
				}
				document.getElementById("zoneSolution").innerHTML = "";

				// Réinitialiser la liste des mots placés
				motsPlaces = [];
            }
		}
    }

    function afficherMessage(message) {
        document.getElementById("message").innerHTML = message;
    }


</script>';


// Afficher la solution masquée
echo "\r\n<DIV id=\"texteCache\" style=\"display: none; font-size: 30px;\">".$lignechoisie."<BR>";
fctAfficheImage($lignechoisie,300);
echo "\r\n</DIV>";
echo "\r\n<BR><button class=\"MonButton\" id=\"boutonCache\" onclick=\"document.getElementById('texteCache').style.display = 'block'; this.style.display = 'none'; \"><br />&nbsp; Solution &nbsp;<br />&nbsp;</button>";

echo "\r\n<form method=\"post\" id=\"monFormulaire\" action='".$_SERVER['PHP_SELF']."'>";


echo "\r\n<input type='hidden' name='scoreHidden' id='scoreHidden' value='VIDE'>";
echo "\r\n<input type='hidden' name='score' id='score' value='".$_SESSION['score']."'>";

echo "<BR><BR><button class=\"MonButton\" type=\"submit\"><br />Un Autre !<br />&nbsp;</button>";
echo "\r\n</form>";





?>


<?php
fctAffichePiedPage();
?>