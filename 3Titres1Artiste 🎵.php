<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

fctAfficheEntete("3 Titres > 1 Artiste");
fctAfficheBtnBack();

// Vérifier si les données sont déjà enregistrées en session
if (!isset($_SESSION['3Titres1Artiste'])) {
    // Si les données ne sont pas en session, les lire à partir du fichier
    $contenuFichier = file_get_contents('Phrases/MP3.txt');
    // Stocker les données en session pour les appels futurs
    $_SESSION['3Titres1Artiste'] = $contenuFichier;
} else {
    // Si les données sont déjà en session, les récupérer
    $contenuFichier = $_SESSION['3Titres1Artiste'];
}

// Séparer les lignes du fichier
$lignes = explode(PHP_EOL, $contenuFichier);

// Initialiser un tableau pour stocker les artistes avec au moins 3 chansons
$artistesAvecTroisChansons = [];

// Parcourir chaque ligne du fichier
foreach ($lignes as $ligne) {
    // Vérifier si la ligne contient le caractère "-"
    if (strpos($ligne, '-') !== false) {
        // Séparer l'artiste et la chanson par le délimiteur "-"
        list($artiste, $chanson) = explode('-', $ligne, 2);

        // Nettoyer les espaces autour de l'artiste et de la chanson
        $artiste = trim($artiste);
        $chanson = trim($chanson);

        // Ajouter l'artiste au tableau
        if (!isset($artistesAvecTroisChansons[$artiste])) {
            $artistesAvecTroisChansons[$artiste] = [];
        }

        // Ajouter la chanson à l'artiste
        $artistesAvecTroisChansons[$artiste][] = $chanson;
    }
}

// Filtrer les artistes avec au moins 3 chansons
$artistesAvecTroisChansons = array_filter($artistesAvecTroisChansons, function ($chansons) {
    return count($chansons) >= 2;
});

// Choisir un artiste au hasard
$artisteChoisi = array_rand($artistesAvecTroisChansons);


// Mélanger les titres de l'artiste choisi
shuffle($artistesAvecTroisChansons[$artisteChoisi]);

echo "<B>Quel artiste propose ces chansons :</B>\n";
$troisPremiersTitres = array_slice($artistesAvecTroisChansons[$artisteChoisi], 0, 2);
foreach ($troisPremiersTitres as $chanson) {
    echo "<H3>$chanson</H3>\n";
}

// Afficher les titres suivants dans une div masquée
echo '<div id="titresSuivants" style="display: none;">';
$NbChansonsEnPlus =0;
foreach (array_slice($artistesAvecTroisChansons[$artisteChoisi], 2) as $chanson) {
    echo "<H3>$chanson</H3>\n";
	$NbChansonsEnPlus++;
}
echo "</div>";
// Ajouter un bouton pour afficher les titres suivants
if ($NbChansonsEnPlus!=0)
	echo "<button class=\"MonButton\" onclick=\"document.getElementById('titresSuivants').style.display = 'block'; this.style.display = 'none'; \">&#x21E9; Afficher les ".$NbChansonsEnPlus." titres suivants &#x21E9;</button>";

echo "<BR><button class=\"MonButton\" onclick=\"location.reload(true);\"><br />Un Autre !<br />&nbsp;</button>";

echo "<BR><p id=\"texteCache\" style=\"display: none; font-size: 30px;\">".$artisteChoisi."<BR>";
fctAfficheImage($artisteChoisi." (musique)",300);

echo "</p>";
echo "<BR><button class=\"MonButton\" onclick=\"document.getElementById('texteCache').style.display = 'block'; this.style.display = 'none'; \"><br />&#x21E9; Solution &#x21E9;<br />&nbsp;</button>";

    ?>

<?php
fctAffichePiedPage();
?>