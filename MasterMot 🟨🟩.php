<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";


// Vérifier si le bouton "Recommencer" a été cliqué
if (isset($_POST['recommencer'])) {
    $nbLettres = $_POST['nbLettres'];
    $checkAleatoire = $_POST['checkAleatoire'];
    if ($checkAleatoire=="true")
        $nbLettres = rand(4, 12);
    $_SESSION['nbLettres'] = $nbLettres;
    unset($_SESSION['motMystere']);
    unset($_SESSION['lstMotsSaisis']);
}
else {
    if (isset($_SESSION['nbLettres'])) {
        $nbLettres = $_SESSION['nbLettres'];
    }
    else {
        $nbLettres = 6;
        $_SESSION['nbLettres'] = $nbLettres;
    }
}
$messageImage = "";
$gagne=0;
// ===============================================================  Chargement du fichier
if (!isset($_SESSION['MotsLettres'.$nbLettres])) {
	// Charger la liste de mots depuis le fichier
	$listeMots = file('FrMaj20k.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Appliquez le filtre directement avec une fonction anonyme
    $motsLongueurOk = array_filter($listeMots, function($mot) use ($nbLettres) {
        // Vérifie la longueur du mot et s'il contient uniquement des lettres de A à Z (majuscules ou minuscules)
        return strlen($mot) == $nbLettres && preg_match('/^[a-zA-Z]+$/', $mot);
    });

    $_SESSION['MotsLettres'.$nbLettres] = $motsLongueurOk;
}
else
{
    $motsLongueurOk = $_SESSION['MotsLettres'.$nbLettres];
}

// ===============================================================  choix du mot
if (!isset($_SESSION['motMystere'])) {
    $motMystere = $motsLongueurOk[array_rand($motsLongueurOk)];
    $_SESSION['motMystere'] = $motMystere;
	$gagne=0;
}
else
{
    $motMystere = $_SESSION['motMystere'];
}
$message = "On cherche un mot de ".$nbLettres." lettres";
//echo '<BR>motMystere='.$_SESSION['motMystere'];

if (isset($_SESSION['lstMotsSaisis']))
    $lstMotsSaisis = $_SESSION['lstMotsSaisis'];
else
    $lstMotsSaisis = "";

// -------------------------------------------- Vérifier si une lettre a été soumise sinon 1ere lettre
if (!isset($_POST['lettre'])) {
    $lettre = substr($motMystere,0,1);
    $motEnCours = $lettre;
}
else {
    //Traitement d'une nouvelle saisie
    $lettre = $_POST['lettre'];
    $motEnCours = $_SESSION['motEnCours'];
    //Efface une lettre
    if ($lettre=="#")
        $motEnCours = substr($motMystere,0,1);
    elseif ($lettre=="@")
            $motEnCours = substr($motEnCours,0,-1);   
    else
        $motEnCours = $motEnCours.$lettre;
    //Test si dernier lettre du mot saisi :
    if (strlen($motEnCours)== $nbLettres) {
        //Affiche les lettres en couleurs
        //echo "<BR>Test du <B>motEnCours=".$motEnCours."</B>";
        if (in_array($motEnCours, $motsLongueurOk)) {
            $message = "👍 ".$motEnCours." existe !";
            $messageImage = "<TABLE WIDTH=100%><TR><TD><IMG SRC='".fctBingSrc($motEnCours,200)."'></TD>";
            $messageImage .= "<TD>".fctReturnDefinitionPetitLarousse($motEnCours,100)."</TD></TR></TABLE>";
            $_SESSION['scoreAjout'] = +2;
            
            if (isset($_SESSION['lstMotsSaisis']))
                $lstMotsSaisis = $_SESSION['lstMotsSaisis'];
            if (strlen($lstMotsSaisis)==0)
                $lstMotsSaisis = $motEnCours;
            else
                $lstMotsSaisis = $lstMotsSaisis.";".$motEnCours;
            
            // ============= GAGNE ?
            if ($motEnCours==$motMystere) {
                $message = "🏆 Bravo ! Gagné !";
                $messageImage = "<TABLE WIDTH=100%><TR><TD><IMG SRC='".fctBingSrc($motMystere,200)."'></TD>";
                $messageImage .= "<TD>".fctReturnDefinition($motMystere,100)."</TD></TR></TABLE>";
                $gagne=1;
                $scoreMessage = "+10";
                $_SESSION['scoreAjout'] = +10;
            }

            $motEnCours = substr($motMystere,0,1);
            $_SESSION['lstMotsSaisis'] = $lstMotsSaisis;
        }
        else {
            $message = "😓 ".$motEnCours." inconnu...";
            $motEnCours = substr($motMystere,0,1);
            $_SESSION['lstMotsSaisis'] = $lstMotsSaisis;
        }
    }
    else {
        //affiche une partie du mot en gris
        $message = "On cherche un mot de ".$nbLettres." lettres";
    }
    
}
$_SESSION['motEnCours']=$motEnCours;

// Liste des lettres de l'alphabet
$lettresAlphabet = range('A', 'Z');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Jeu du Pendu</title>
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
            width: 40px;
            height: 40px;
            margin: 5px;
            text-align: center;
            line-height: 40px;
            border: 1px solid #ccc;
            cursor: pointer;
            background-color: White;
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
        .styleMotBienPlace {
            background-color: LightGreen;
        }
        .styleMotMalPlace {
            background-color: Yellow;
        }
        .styleMotGrises {
            background-color: #F0F0F0;
        }
    </style>
</head>
<body>
<script type="text/javascript" src="scripts.js"></script>
    <?php
    fctAfficheBtnBack();
    fctAfficheScore();
    ?>

<CENTER>
<h3>Master Mot</h3>
<H3><?php echo $message; ?></H3>

<form method="post" action="" id="formDevinez">
    <input  type="hidden" type="text" id="lettre" name="lettre" maxlength="1" required size="2">
    <button id="MonBouton" type="hidden" type="submit">Devinez</button>
</form>


<?php
    // Tableau des mots
    //echo $motMystere;
    // Divise la chaîne en un tableau de mots
    $motsArray = explode(";", $lstMotsSaisis);

    $lettresRouges = substr($motMystere,0,1);
    $lettresJaunes = substr($motMystere,0,1);
    $lettresGrises = "";
    // ======================================  Affiche chaque mot sur une nouvelle ligne
    $nbMotsSaisis = 0;

    if (!empty($motsArray[0])) {
        foreach ($motsArray as $motSaisi) {
            echo "<div class='divMots'>";
            $nbMotsSaisis++;
            echo "<div class='styleMot' style='border: 0px'>" . $nbMotsSaisis . "</div>";
            
            $lettres = str_split($motSaisi);
            for ($i = 0; $i < $nbLettres; $i++) {
                $styleLettre = "";
    
                if ($lettres[$i]!="") {
                    $occurrences = substr_count($motMystere, $lettres[$i]);
    
                    //La lettre est présente dans le mot : 1 fois
                    if ($occurrences>=1) {
                        $styleLettre = "styleMotMalPlace";
                        if (strpos($lettresJaunes, $lettres[$i]) === false) {                            
                            $lettresJaunes .= $lettres[$i];
                            //echo "lettresJaunes=".$lettresJaunes;
                        }
                                 
                        if (substr($motMystere,$i,1)==$lettres[$i]) {
                            //echo "BienPlace";
                            $styleLettre = "styleMotBienPlace";
                            if (strpos($lettresRouges, $lettres[$i]) === false) {
                                $lettresRouges .= $lettres[$i];
                                //echo "lettresRouges=".$lettresRouges;
                            }
                        } 
                    }
                    else {
                        $styleLettre = "styleMotGrises";
                        if (strpos($lettresGrises, $lettres[$i]) === false) {
                            $lettresGrises .= $lettres[$i];
                        }   
                    }
                    echo "<div class='styleMot $styleLettre'>" . $lettres[$i];
                    if ($occurrences>1)
                        echo "<span style='font-size: 14px;color:red;'>$occurrences</span>";
                    else
                        echo "<span style='font-size: 14px;'>$occurrences</span>";
                    echo "</div>";
                }
            }
            echo "<div class='styleMot' style=\"border: 0px;background-repeat: no-repeat;background-image: url('".fctBingSrc($motSaisi,35)."');\">&nbsp;</div>";
            echo "</div>";
        }
    }

    // ====================
    
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
            echo "<div class='styleMot styleMotBienPlace'>" . $lettres[0] . "</div>";
            for ($i = 1; $i < $nbLettres; $i++) {
                if (!isset($lettres[$i]))
                    $lettres[$i]="&nbsp;";
                echo "<div class='styleMot'>" . $lettres[$i] . "</div>";
            }
        }
        echo "<div class='styleMot' style='border: 0px'>&nbsp;</div>";
        echo "</div>";
    }
    //echo '<BR>motMystere='.$_SESSION['motMystere'];
    //echo '<BR>lstMotsSaisis='.$_SESSION['lstMotsSaisis'];
    //echo '<BR>motEnCours='.$_SESSION['motEnCours'];

?>
</div>
<div id="clavier">
<?php
	if ($gagne==0) {
		foreach ($lettresAlphabet as $lettre) {
            $classeLettre ="";
            /*
            if (strpos($lettresJaunes, $lettre) !== false)
                $classeLettre = "styleMotMalPlace";
            if (strpos($lettresRouges, $lettre) !== false)
                $classeLettre = "styleMotBienPlace"; 
            */
            if (strpos($lettresGrises, $lettre) !== false)
                $classeLettre = "styleMotGrises";            
                
			echo "<div class='tuile-lettre $classeLettre' onclick='choisirLettre(\"$lettre\")'>$lettre</div>";
		}
        if ($motEnCours!=substr($motMystere,0,1)) {
            echo "\r\n<BR><div class='MonButton' onclick='choisirLettre(\"@\")'>Effacer</div>";
            echo "\r\n<div class='MonButton' onclick='choisirLettre(\"#\")'>Recommencer</div>";
        }

	}
if ($messageImage=="") {
    $messageImage="<div class='tuile-lettre styleMotBienPlace' style='width: 200px;'>Lettre bien placée</div>";
    $messageImage.="<br><div class='tuile-lettre styleMotMalPlace' style='width: 200px;'>Lettre mal placée</div>";
    $messageImage.="<br><div class='tuile-lettre styleMotGrises' style='width: 200px;'>Lettre pas présente</div>";
}

?>
<HR></div>


<DIV style='height: 200px;'>
<?php echo $messageImage; ?>
</DIV>



<BR>
<?php
echo '<div id="divAide" style="display: none;">';
$NbMots = 0;
foreach ($motsLongueurOk as $mot) {
    if (strtoupper(substr($mot, 0, 1)) === strtoupper(substr($motMystere, 0, 1))) {
        echo "<br>" . $mot;
        $NbMots++;
    }
}
echo "</div>";
echo "<button class=\"MonButton\" onclick=\"document.getElementById('divAide').style.display = 'block'; this.style.display = 'none'; \">&#x21E9; Afficher les $NbMots mots possibles &#x21E9;</button>";

echo "<BR>";
echo '<div id="divSolution" style="display: none;">';
echo "<h3>" . $motMystere."</h3>";
echo "</div>";
echo "<button class=\"MonButton\" onclick=\"document.getElementById('divSolution').style.display = 'block'; this.style.display = 'none'; \">&#x21E9; Afficher le mot mystère &#x21E9;</button>";



?>



<!-- Bouton pour recommencer -->
<form method="post" action="">
    <label for="nbLettres">Nombre de lettres (4-12):</label>
    <input type="number" id="nbLettres" name="nbLettres" min="4" max="12" value="<?php echo $nbLettres; ?>"/>
    <BR>
    <input type="checkbox" id="checkAleatoire" name="checkAleatoire" value="true" CHECKED />
    <label for="checkAleatoire">Choix aléatoire du nombre de lettres</label>
    <BR>
    <button type="submit" name="recommencer"><BR>Un autre mot !<BR>&nbsp;</button>
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


</body>
</html>