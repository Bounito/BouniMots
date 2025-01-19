<?php
// Chemin du fichier texte
$cheminFichier = 'Types de Vetement.txt';

// Lire le contenu du fichier
$listeMots = file($cheminFichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

echo '<H1>Count='.count($listeMots)."</H1>";

// Fonction pour supprimer les mots au pluriel si le singulier existe
function supprimerMotsPlurielAvecSingulier(&$listeMots)
{
    // Créer un tableau pour stocker les mots au singulier
    $motsSinguliers = [];

    // Filtrer les mots ne se terminant pas par "S"
    $listeMots = array_filter($listeMots, function ($mot) use (&$motsSinguliers) {
        $derniereLettre = mb_substr($mot, -1);
        $motSingulier = rtrim($mot, 'S');

        // Vérifier si le singulier existe dans le tableau des singuliers
        if (in_array($motSingulier, $motsSinguliers)) {
            // Supprimer le mot au pluriel
            return false;
        }

        // Ajouter le singulier au tableau des singuliers
        $motsSinguliers[] = $motSingulier;

        // Conserver le mot au pluriel
        return true;
    });
}

// Appeler la fonction pour modifier la liste de mots
supprimerMotsPlurielAvecSingulier($listeMots);


// Afficher la nouvelle liste
foreach ($listeMots as $mot) {
    echo "<BR>".$mot;
}

?>
