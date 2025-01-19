<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";
fctAfficheEntete("Play Sounds");
fctAfficheBtnBack();

$directory = 'sound/'; // Remplacez par le chemin de votre dossier
$files = scandir($directory);

// Filtrer les fichiers pour ne récupérer que les fichiers MP3
$mp3Files = array_filter($files, function ($file) {
    return pathinfo($file, PATHINFO_EXTENSION) === 'mp3';
});

// Créer un bouton pour chaque fichier MP3
echo "<TABLE WIDTH=100%>";
foreach ($mp3Files as $mp3File) {
    echo '<TR><TD>';
    fctAfficheImage(basename($mp3File,'.mp3'),100);
    echo '</TD><TD><button class="MonButton" onclick="playSound(\'' . $mp3File . '\')">' . basename($mp3File,'.mp3') . '</button>';
    echo '</TR>';
    
}
echo "</TABLE>";

fctAffichePiedPage();
?>
