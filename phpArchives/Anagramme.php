<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Mots</title>
<style>
body {
	font-family: Arial;
}
</style>
</head>
<body>
<H1>Recherche des tous les anagrammes français</H1>
<?php

// Vérifier si la longueur est valide
for ($longueur = 3; $longueur <= 20; $longueur++) {
    // Lire le fichier de mots (assurez-vous que le chemin est correct)
    $cheminFichierMots = 'SubstantifsVerbes.txt';
    $mots = file($cheminFichierMots, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Filtrer les mots par longueur et absence de majuscules/caractères spéciaux
    $motsFiltres = array_filter($mots, function ($mot) use ($longueur) {
        return mb_strlen($mot) === $longueur;
    });

    // Créer un tableau associatif pour stocker les mots triés et leur occurrence
    $motsTriesOccurrence = [];

    // Remplir le tableau avec les séquences triées et compter les occurrences
    foreach ($motsFiltres as $mot) {
        $lettres = str_split($mot);
        sort($lettres);
        $motTrie = implode('', $lettres);

        if (!isset($motsTriesOccurrence[$motTrie])) {
            $motsTriesOccurrence[$motTrie] = [];
        }

        $motsTriesOccurrence[$motTrie][] = $mot;
    }

    // Filtrer les groupes d'anagrammes avec au moins 5 éléments
    $groupesAnagrammes = array_filter($motsTriesOccurrence, function ($groupe) {
        return count($groupe) >= 3;
    });

    // Afficher le résultat
    echo "<h2>Groupes d'anagrammes pour les mots de $longueur lettres :</h2>";
    if (empty($groupesAnagrammes)) {
        echo '<p>Aucun groupe d\'anagrammes trouvé pour cette longueur.</p>';
    } else {
        foreach ($groupesAnagrammes as $groupe) {
            echo '<ul>';
            foreach ($groupe as $motAnagramme) {
                echo '<li>' . $motAnagramme . '</li>';
            }
            echo '</ul>';
        }
    }
}

?>

<BR><BR><BR>
<SMALL>Bounito 2024 ©<BR><A href="/.">Retour Menu</A></SMALL>
</body>
</html>