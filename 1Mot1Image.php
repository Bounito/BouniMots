<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

fctAfficheHeaderStart("1 Mot 1 Image");
?>

	    <style>
        .lettres {
            display: flex;
            justify-content: center;
            margin-top: 5px;
			flex-wrap: wrap; /* Ajoutez cette ligne pour permettre l'enroulement des éléments sur plusieurs lignes */
        }

        .lettre {
			display: flex; /* Ajout de display: flex; */
			align-items: center; /* Ajout de align-items: center; */
			justify-content: center;
            margin: 2px;
            padding: 5px;
            font-size: 28px;
            cursor: pointer;
			text-transform: uppercase;
			border: 2px solid #000; /* Ajout de la bordure */
			border-radius: 5px; /* Ajout d'arrondi aux coins */
			font-family: Arial, sans-serif; /* Utilisation de la police Arial */
			width: 20px; /* Définir la largeur de chaque case */
			text-align: center; /* Centrer le texte horizontalement */
            background-color: white;
        }
		.lettre-grise {
			background-color: #ccc; /* Couleur de fond grise */
			color: #888; /* Couleur de texte grise */
			cursor: not-allowed; /* Curseur non autorisé */
		}
        #proposition {
            margin-top: 20px;
            padding: 5px;
            font-size: 16px;
        }

        #resultat {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
        }

    </style>
	
<?php
fctAfficheBodyStart("1 Mot 1 Image");
fctAfficheBtnBack();

fctAfficheScore();

$mesthemeRepertoire = ["Listes/", "NomPrenom/", "Phrases/"];
$themeRepertoire = fctAfficheBtnTheme($mesthemeRepertoire);
$theme = basename($themeRepertoire);

$motAuHasard = fctRandMot($themeRepertoire.'.txt');

echo "\r\n<H4>Sauras-tu trouver le mot mystère sur le thème <b>$theme</b> ?</H4>";

fctAfficheImage($motAuHasard,300);

if (isset($_GET['th'])) {
    echo "\r\n<form method=\"post\" id=\"monFormulaire\" action='".$_SERVER['PHP_SELF']."?th=".$_GET['th']."'>";
}
else {
    echo "\r\n<form method=\"post\" id=\"monFormulaire\" action='".$_SERVER['PHP_SELF']."?th=🎲'>";
}

echo "\r\n<input type='hidden' name='scoreHidden' id='scoreHidden' value='VIDE'>";
echo "\r\n<input type='hidden' name='score' id='score' value='".$_SESSION['score']."'>";

echo "\r\n<div id=\"DivAnagram\">";

echo "\r\n<div class=\"lettres\" id=\"lettresContainer\"></div>";

echo "\r\n<input type=\"text\" id=\"proposition\" size=\"10\" placeholder=\"Saisissez votre proposition\">";

echo "\r\n<button class=\"MonButton\" type=\"button\" onclick=\"verifierProposition()\">Vérifier</button>";

echo "\r\n<button class=\"MonButton\" type=\"button\" onclick=\"effacerSaisie()\">Effacer</button>";

echo "\r\n<div id=\"resultat\">Le thème est : ".$theme."</div>";
echo "\r\n</div>";

echo "\r\n<BR>";
echo "\r\n<p id=\"texteCache\" style=\"display: none;\">";

echo "\r\nLe mot était : <B>".$motAuHasard."</B>";
?>
</p>
<!-- Bouton de révélation -->
<button class="MonButton" onclick="reveleTexte(this)">Révéler le mot</button>
<HR>
<!-- Bouton pour recharger la page -->
<button class="MonButton" type="submit"><br />Un autre mot !<br />&nbsp;</button>
<HR>

<?php



echo "\r\n</form>";


?>



<script>
function reveleTexte(button) {
    // Sélectionner l'élément texte
    var texte = document.getElementById("texteCache");

    // Changer le style pour afficher le texte
    texte.style.display = "block";
	button.parentNode.removeChild(button);
}

// Mot mystère
var motMystere = "<?php echo $motAuHasard; ?>";

// Fonction pour mélanger les lettres du mot
function melangerLettres(mot) {
    // Initialiser le mot mélangé avec le mot original
    let motMelange = mot;
    // Continuer à mélanger les lettres jusqu'à ce que le mot mélangé soit différent du mot original
    while (motMelange === mot) {
        motMelange = mot.split('').sort(function(){return 0.5-Math.random()}).join('');
    }
    return motMelange;
}

// Création des boutons avec les lettres du mot mélangées
var lettresMelangees = melangerLettres(motMystere);
var lettresContainer = document.getElementById('lettresContainer');

for (var i = 0; i < lettresMelangees.length; i++) {
    var lettre = document.createElement('div');
    lettre.className = 'lettre';
    lettre.textContent = lettresMelangees[i];
    lettre.onclick = function() {
        ajouterLettre(this.textContent,this);
    };
    lettresContainer.appendChild(lettre);
}

// Fonction pour ajouter une lettre à la proposition
function ajouterLettre(lettre, bouton) {
    var propositionInput = document.getElementById('proposition');
    propositionInput.value += lettre;
	// Appliquer la classe pour griser le bouton
    bouton.classList.add('lettre-grise');
	bouton.onclick = '';
	if (motMystere.length==document.getElementById('proposition').value.length)
	{
		verifierProposition();
	}
}

// Fonction pour vérifier la proposition
function goreload() {
        document.getElementById('monFormulaire').submit();
    }

function verifierProposition() {
    var propositionInput = document.getElementById('proposition');

    var proposition = propositionInput.value.toUpperCase();

    if (proposition === motMystere) {
        document.getElementById('resultat').textContent = '🏆 Félicitations ! '+proposition+' est le mot mystère.';
        document.getElementById('scoreDiv').innerHTML = '<IMG src="load.gif">';
        playSound('success.mp3');
        document.getElementById('scoreHidden').value = '3';
        document.getElementById('score').value = parseInt(document.getElementById('score').value,10) + parseInt(document.getElementById('scoreHidden').value,10);
        setTimeout(goreload, 1000);
    } else {
        document.getElementById('resultat').textContent = '😪 Désolé, '+proposition+' n\'est pas le bon mot... Essayez à nouveau.';
        playSound('error.mp3');
    }
    
}

// Fonction pour effacer la saisie
function effacerSaisie() {
    var propositionInput = document.getElementById('proposition');
    propositionInput.value = '';
	document.getElementById('resultat').textContent = 'Saisissez votre proposition';
	var lettresContainer = document.getElementById('lettresContainer');
    var boutonsLettre = lettresContainer.getElementsByClassName('lettre');

    // Parcourir tous les boutons et réinitialiser leur état
    for (var i = 0; i < boutonsLettre.length; i++) {
        var bouton = boutonsLettre[i];
        bouton.classList.remove('lettre-grise'); // Supprimer la classe de grisage
        bouton.onclick = function() {
            ajouterLettre(this.textContent, this); // Réactiver le clic sur le bouton
        };
    }
}
</script>

<?php
fctAffichePiedPage();
?>