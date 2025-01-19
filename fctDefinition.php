<?php
// Inclure votre fichier contenant la fonction fctReturnDefinitionPetitLarousse
include 'fonctions.php';

// Vérifier si le paramètre "mot" est présent dans la requête
if (isset($_GET['mot'])) {
    $mot = $_GET['mot'];
    $taille = $_GET['taille'];
    // Appeler la fonction fctReturnDefinitionPetitLarousse pour obtenir la définition
    $definition = fctReturnDefinitionPetitLarousse($mot, $taille); // Supposons que 100 est une limite de longueur
    
    // Envoyer la réponse au format texte
    echo $definition;
} else {
    // Si le paramètre "mot" n'est pas fourni, renvoyer une erreur
    http_response_code(400); // Code d'erreur "Bad Request"
    echo "Erreur : Le paramètre 'mot' est requis.";
}
?>