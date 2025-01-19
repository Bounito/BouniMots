<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si la clé 'NbMots' existe dans $_POST
    $NbMots = isset($_POST['NbMots']) ? $_POST['NbMots'] : null;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnaGame by Boun</title>
 <style>
body {
	font-family: Arial;
}
button {
	background-color: #007AFF;
	border: none;
	color: white;
	padding: px 10px;
	text-align: center;
	text-decoration: none;
	display: inline-block;
	font-size: 16px;
	margin: 4px 2px;
	cursor: pointer;
	border-radius: 20px;
}
</style>
</head>
<body>

<?php

session_start();

// Lire le fichier de mots (assurez-vous que le chemin est correct)
$cheminFichierMots = 'SubstantifsVerbes.txt';
$mots = file($cheminFichierMots, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Créer un tableau associatif pour stocker les mots triés et leur occurrence
$motsTriesOccurrence = [];

// Remplir le tableau avec les séquences triées et compter les occurrences
foreach ($mots as $mot) {
    $lettres = str_split($mot);
    sort($lettres);
    $motTrie = implode('', $lettres);

    if (!isset($motsTriesOccurrence[$motTrie])) {
        $motsTriesOccurrence[$motTrie] = [];
    }
    $motsTriesOccurrence[$motTrie][] = $mot;
}
//echo "NbMots=".$NbMots;

// Filtrer les groupes d'anagrammes avec au moins 3 éléments
$groupesAnagrammes = array_filter($motsTriesOccurrence, function ($groupe) {
    return count($groupe) >= 4;
});

// Choisir un groupe au hasard
$groupeChoisi = $groupesAnagrammes[array_rand($groupesAnagrammes)];

// Choisir un anagramme au hasard dans le groupe
$anagrammeChoisi = $groupeChoisi[array_rand($groupeChoisi)];

// Stocker l'anagramme choisi dans la session
$_SESSION['anagramme'] = $anagrammeChoisi;
// Obtenir le nombre de mots correspondant à l'anagramme
$nombreDeMots = count($groupeChoisi);
?>

<h2>Anagramme à deviner :</h2>
<p id="anagramme"><?php echo $anagrammeChoisi; ?></p>

<form id="formDevine">
	<?php
	// Créer des champs pour deviner les mots
	$solution = '<BR>'.$groupeChoisi[0];
	for ($i = 1; $i <= $nombreDeMots; $i++) {
		echo "<label>Mot $i :</label>";
		// Initialiser le premier champ avec $anagrammeChoisi
		$valeurInitiale = ($i === 1) ? $anagrammeChoisi : "";
		echo "<input type='text' class='motDevine' value='$valeurInitiale' required onkeyup='this.value=this.value.toUpperCase()'><br>";
		$solution = $solution.'<BR>'.$groupeChoisi[$i];
	}
	?>
    <input type='button' value='Vérifier' onclick='verifierDevine()'>
</form>

<div id="resultat"></div>

<div id="solution" style="display: none;"><?php echo $solution; ?></div>
<BR>
<button onclick="document.getElementById('solution').style.display = 'block';">Afficher Solutions</button>
<BR>
<button onclick="location.reload(true);">Un autre Anagramme !</button>

<form id="formChoixNb" method="post">
	<input type="number" id="NbMots" name="NbMots" min="4" max="10" value="<?php echo $NbMots; ?>"/>
	<input type="submit" value='Nombre de mots'>
</form>

<script>
    function verifierDevine() {
        var motsDevines = document.getElementsByClassName('motDevine');
        var motsDevinesArray = Array.from(motsDevines).map(function (input) {
            return input.value.trim();
        });

        var anagramme = document.getElementById('anagramme').textContent.trim();

        // Envoyer les données au serveur pour vérification
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'AnaGameVerif.php', true);  // Le nom du fichier doit être vérifier_devine.php
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Afficher le résultat
                document.getElementById('resultat').innerHTML = xhr.responseText;
            }
        };
        var data = {
            anagramme: anagramme,
            mots_devines: motsDevinesArray
        };
        xhr.send(JSON.stringify(data));
    }
</script>

<BR><BR><BR>
<SMALL>Bounito 2024 ©<BR><A href="/.">Retour Menu</A></SMALL> 
</body>
</html>
