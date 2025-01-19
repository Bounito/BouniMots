<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";



// Vérifier si le bouton "Recommencer" a été cliqué
if (isset($_POST['recommencer'])) {
    unset($_SESSION['groupeChoisi']);
    unset($_SESSION['lstMotsSaisis']);
}

$messageImage = "";
$gagne=0;
// ===============================================================  Chargement du fichier
if (!isset($_SESSION['groupesAnagrammes'])) {
    // Lire le fichier de mots (assurez-vous que le chemin est correct)
    $cheminFichierMots = 'FrMaj20k.txt';
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
    $_SESSION['groupesAnagrammes'] = $groupesAnagrammes;
}
else {
    $groupesAnagrammes = $_SESSION['groupesAnagrammes'];
}

// ===============================================================  choix de l'anagramme
if (!isset($_SESSION['groupeChoisi'])) {
    // Choisir un groupe au hasard
    $groupeChoisi = $groupesAnagrammes[array_rand($groupesAnagrammes)];
    // Obtenir le nombre de mots correspondant à l'anagramme
	$gagne=0;
    $_SESSION['groupeChoisi'] = $groupeChoisi;
}
else
{
    $groupeChoisi = $_SESSION['groupeChoisi'];
}
$nombreDeMots = count($groupeChoisi);
$nbLettres = strlen($groupeChoisi[0]);
$message = "On cherche $nombreDeMots mots composées des mêmes $nbLettres lettres";

//echo '<BR>groupeChoisi=';
//print_r($groupeChoisi);

// -------------------------------------------- Vérifier si une lettre a été soumise sinon 1ere lettre
if (!isset($_POST['lettre'])) {
    $lettre = "";
    $motEnCours = $lettre;
}
else {
    //Traitement d'une nouvelle saisie
    $lettre = $_POST['lettre'];
    if (isset($_SESSION['motEnCours']))
        $motEnCours = $_SESSION['motEnCours'];

    //Efface une lettre
    if ($lettre=="#")
        $motEnCours = "";
    elseif ($lettre=="@")
        $motEnCours = substr($motEnCours,0,-1);   
    else
        $motEnCours = $motEnCours.$lettre;
    //Test si dernier lettre du mot saisi :
    if (strlen($motEnCours)== $nbLettres) {
        //Affiche les lettres en couleurs
        //echo "<BR>Test du <B>motEnCours=".$motEnCours."</B>";
        if (in_array($motEnCours, $groupeChoisi)) {
            
            if (isset($_SESSION['lstMotsSaisis'])) {
                $lstMotsSaisis = $_SESSION['lstMotsSaisis'];
                $oldLstMotsSaisis = $lstMotsSaisis;
                $lstMotsSaisis = $lstMotsSaisis.";".$motEnCours;
            }
            else {
                $oldLstMotsSaisis = "";
                $lstMotsSaisis = $motEnCours;
            }
            $_SESSION['lstMotsSaisis'] = $lstMotsSaisis;

            if (strpos($oldLstMotsSaisis, $motEnCours) !== false) {
                $message = "🤔 ".$motEnCours." déjà saisi !";
                $lstMotsSaisis=$oldLstMotsSaisis;
            }
            else {
                $message = "👍 ".$motEnCours." existe !";
                $messageImage = "<TABLE WIDTH=100%><TR><TD><IMG SRC='".fctBingSrc($motEnCours,200)."'></TD>";
                $messageImage .= "<TD>".fctReturnDefinition($motEnCours,100)."</TD></TR></TABLE>";
                $_SESSION['scoreAjout'] = +2;

                // Parcourir chaque caractère de la chaîne
                $nombrePointsVirgules = 1;
                for ($i = 0; $i < strlen($lstMotsSaisis); $i++) {
                    if ($lstMotsSaisis[$i] == ';') {
                        $nombrePointsVirgules++;
                    }
                }
                // ============= GAGNE ?
                if ($nombrePointsVirgules==$nombreDeMots) {
                    $message = "🏆 Bravo ! Tu as trouvé les ".$nombreDeMots." mots !";
                    $messageImage = "<TABLE WIDTH=100%><TR><TD><IMG SRC='".fctBingSrc($motEnCours,200)."'></TD>";
                    $messageImage .= "<TD>".fctReturnDefinition($motEnCours,100)."</TD></TR></TABLE>";
                    $gagne=1;
                    $_SESSION['scoreAjout'] = +10;
                }
            }


            $motEnCours = "";
            $_SESSION['lstMotsSaisis'] = $lstMotsSaisis;
        }
        else {
            $message = "😓 ".$motEnCours." inconnu...";
            $motEnCours = "";
        }
    }
    else {
        $message = "On cherche $nombreDeMots mots composées des mêmes $nbLettres lettres";
    }
    
}
$_SESSION['motEnCours']=$motEnCours;

// Liste des lettres de l'alphabet
//$lettresAlphabet = range('A', 'Z');

// Convertir le mot en tableau de lettres
$lettresAlphabet = str_split($groupeChoisi[0]);
// Trier le tableau de lettres par ordre alphabétique
sort($lettresAlphabet);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>AnaGame</title>
    <style>

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
        .tuile-lettre {
            display: inline-block;
            width: 35px;
            height: 35px;
            margin: 2px;
            text-align: center;
            line-height: 40px;
            border: 1px solid #ccc;
            cursor: pointer;
            background-color: #FFFFFF;
            font-size: 30px;
        }
        .lettre-utilisee {
            background-color: #eee;
            cursor: not-allowed;
        }
        .divMots {
            display: flex;
            width: 100%;
            justify-content: space-evenly;
        }
        .styleMot {
            display: inline-block;
            width: 35px;
            height: 35px;
            margin: 2px;
            text-align: center;
            line-height: 40px;
            border: 2px solid #ccc;
            cursor: not-allowed;
            font-size: 30px;
        }

    </style>
</head>
<body>
    <?php
        fctAfficheBtnBack();
        fctAfficheScore();
    ?>

<CENTER>
<h3>AnaGame</h3>
<H3><?php echo $message; ?></H3>




<?php

    
echo "\r\n<form method=\"post\" id=\"formDevinez\" action=\"".$_SERVER['PHP_SELF']."\">";
echo "\r\n<input  type='hidden' type='text' id='lettre' name='lettre' maxlength='1' required size='2'>";
echo "\r\n</form>";







    $nbMotsSaisis = 0;
    // Tableau des mots
    if (isset($_SESSION['lstMotsSaisis'])) {
        $lstMotsSaisis = $_SESSION['lstMotsSaisis'];
        // Divise la chaîne en un tableau de mots
        $motsArray = explode(";", $lstMotsSaisis);
        // ======================================  Affiche chaque mot sur une nouvelle ligne
        foreach ($motsArray as $motSaisi) {
            echo "<div class='divMots'>";
            if ($motSaisi!="") {
                $nbMotsSaisis++;
                echo "<div class='styleMot' style='border: 0px'>" . $nbMotsSaisis . "</div>";

                $lettres = str_split($motSaisi);
                for ($i = 0; $i < $nbLettres; $i++) {
                    if ($lettres[$i]!="")
                        echo "<div class='styleMot'>" . $lettres[$i] . "</div>";
                }
                echo "<div class='styleMot' style=\"border: 0px;background-repeat: no-repeat;background-image: url('".fctBingSrc($motSaisi,35)."');\">&nbsp;</div>";
                echo "</div>";
            }
        }
    }
    // ==================================================================================
    
    if ($gagne==0) {
        echo "<div class='divMots'>";
        $nbMotsSaisis++;
        echo "<div class='styleMot' style='border: 0px'>" . $nbMotsSaisis . "</div>";
        if (strlen($motEnCours)== $nbLettres) {
            //Affiche les lettres en couleurs
            echo "<BR><B>motEnCours=".$motEnCours."</B>";
        }
        else {
            //affiche une partie du mot en gris
            $lettres = str_split($motEnCours);
            for ($i = 0; $i < $nbLettres; $i++) {
                if (!isset($lettres[$i]))
                    $lettres[$i]="&nbsp;";
                echo "<div class='styleMot'>" . $lettres[$i] . "</div>";
            }
        }
        echo "<div class='styleMot' style='border: 0px'>&nbsp;</div>";
        echo "</div>";
    }

    //echo '<BR>lstMotsSaisis='.$_SESSION['lstMotsSaisis'];
    //echo '<BR>motEnCours='.$_SESSION['motEnCours'];

?>
</div>
<div id="clavier">
<?php
	if ($gagne==0) {
        echo "<BR><div class='divMots'>";
        echo "<div class='styleMot' style='border: 0px'>&nbsp;</div>";
		foreach ($lettresAlphabet as $lettre) {
			echo "<div class='tuile-lettre' onclick='choisirLettre(\"$lettre\")'>$lettre</div>";
		}
        echo "<div class='styleMot' style='border: 0px'>&nbsp;</div>";
        echo "</div>";
        if ((strlen($motEnCours)>0) && (strlen($motEnCours)<$nbLettres)) {
            echo "\r\n<BR><div class='MonButton' onclick='choisirLettre(\"@\")'>Effacer</div>";
            echo "\r\n<div class='MonButton' onclick='choisirLettre(\"#\")'>Recommencer</div>";
        }
	}
if ($messageImage=="") {
    $messageImage="Bon courage !";
}

?>
<HR></div>


<DIV style='height: 200px;'>
<?php echo $messageImage; ?>
</DIV>



<?php
// Afficher solution dans une div masquée
echo '<div id="divSolution" style="display: none;">';
foreach ($groupeChoisi as $mot) {
    echo "<H3>" . $mot."</H3>";
}
echo "</div>";
echo "<button class=\"MonButton\" onclick=\"document.getElementById('divSolution').style.display = 'block'; this.style.display = 'none'; \">&#x21E9; Afficher les ".$nombreDeMots." mots solution &#x21E9;</button>";





//echo "<br><br>";
//print_r($_SESSION['groupesAnagrammes']);
?>




<?php echo "<br>" . count($_SESSION['groupesAnagrammes'])." anagrammes disponibles";?>
<!-- Bouton pour recommencer -->
<form method="post" action="">
    <button type="submit" name="recommencer"><BR>Un autre AnaGame !<BR>&nbsp;</button>
</form>

<script>
document.getElementById('MonBouton').style.display = 'none';
    function choisirLettre(lettre) {
        // Mettre à jour le champ de saisie avec la lettre choisie
        document.getElementById('lettre').value = lettre;

        // Soumettre automatiquement le formulaire
        document.getElementById('formDevinez').submit();
    }
</script>
<BR>
<BR>
<SMALL>Bounito 2024 ©<BR><A href="/." class="MonLien">Retour Menu</A></SMALL> 
<CENTER>
<BR>
<BR><BR>
<BR>

</body>
</html>