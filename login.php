<?php
session_start();

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = "";
    // Récupérer les valeurs du formulaire
    $username = $_POST["username"];
    $password = $_POST["password"];
    $avatar = $_POST["avatar"];
    // Chemin du fichier texte
    $cheminFichier = 'log/user.txt';

    // Lire le contenu du fichier dans un tableau
    $contenuFichier = file($cheminFichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Vérifier si le couple username/password est présent
    $couplePresent = false;
    foreach ($contenuFichier as $ligne) {
        list($usernameFile, $passwordFile, $avatarFile, $scoreFile) = explode(':', $ligne);
        if ($username === $usernameFile && md5($password) === $passwordFile) {
            $couplePresent = true;
            $message = "ça fait plaisir de te retouver ".$username." !";
            $_SESSION['user'] = $username;
            $_SESSION['avatar'] = $avatarFile;
            $_SESSION['score'] = $scoreFile;
            break;
        }
    }

    // Si le couple n'est pas présent, l'ajouter en fin de fichier
    if (!$couplePresent) {
        $nouvelleLigne = $username . ':' . md5($password). ':' .$avatar. ':0';

        $resultatEcriture = file_put_contents($cheminFichier, PHP_EOL.$nouvelleLigne , FILE_APPEND | LOCK_EX);

        if ($resultatEcriture !== false) {
            $message = "Bienvenue $username !";
            $_SESSION['user'] = $username;
            $_SESSION['avatar'] = $avatar;
        } else {
            $message = "Erreur lors de l'écriture dans le fichier.";
            // Vous pouvez également afficher plus de détails sur l'erreur avec error_get_last()
            var_dump(error_get_last());
        }

    }

    echo $message;
} else {
    // Requête non autorisée
    echo "Unauthorized request";
}
?>
