<?php
session_start();

// Récupérer le contenu brut du flux d'entrée
$json_data = file_get_contents("php://input");

if (!empty($json_data)) {
    // Décoder les données JSON
    $data = json_decode($json_data, true);

    if (isset($data['anagramme']) && isset($data['mots_devines'])) {
        $anagramme = $data['anagramme'];
        $motsDevines = $data['mots_devines'];

        // Vérifier si les mots devinés sont corrects
        $resultat = verifierDevinette($anagramme, $motsDevines);

        // Afficher le résultat
        echo $resultat;
    } else {
        // Erreur si les données ne sont pas définies
        echo "Erreur: Données manquantes.";
    }
} else {
    // Erreur si les données JSON sont vides
    echo "Erreur: Données JSON manquantes.";
}

function verifierDevinette($anagramme, $motsDevines) {
    // Convertir l'anagramme en tableau de lettres
    $lettresAnagramme = str_split($anagramme);

    // Vérifier chaque mot deviné
    foreach ($motsDevines as $index => $motDevine) {
        // Convertir le mot deviné en tableau de lettres
        $lettresMotDevine = str_split($motDevine);

        // Trier les lettres pour la comparaison
        sort($lettresAnagramme);
        sort($lettresMotDevine);

        // Vérifier si les lettres triées sont identiques
        if ($lettresAnagramme !== $lettresMotDevine) {
            return "Mot ".($index+1)." incorrect. Essayez à nouveau.";
        }
		        // Vérifier si le mot est identique à un autre mot déjà saisi
        for ($i = 0; $i < $index; $i++) {
            if ($motDevine === $motsDevines[$i]) {
                return "Mot ".($index+1)." identique à un mot précédemment saisi. Choisissez un autre mot.";
            }
        }
    }

    // Tous les mots devinés sont corrects
    return "Félicitations ! Vous avez trouvé tous les mots.";
}
?>
