<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

// Fonction pour initialiser le mot caché avec des tirets bas
function initialiserMotCache($mot) {
    return str_repeat('_', strlen($mot));
}

// Fonction pour mettre à jour le mot caché avec la lettre devinée
function mettreAJourMotCache($mot, $motCache, $lettre) {
    for ($i = 0; $i < strlen($mot); $i++) {
        if (strtoupper($mot[$i]) == strtoupper($lettre)) {
            $motCache[$i] = strtoupper($lettre);
        }
    }
    return $motCache;
}

// Choisir un mot au hasard et l'initialiser
if (!isset($_SESSION['mot1']) || isset($_POST['recommencer'])) {
	$_SESSION['mot1'] = fctRandMot('FrMaj20k.txt');
	$_SESSION['mot2'] = fctRandMot('FrMaj20k.txt');
	//echo '<BR>Mot='.$_SESSION['mot'];
	
    $_SESSION['motCache1'] = initialiserMotCache($_SESSION['mot1']);
	$_SESSION['motCache2'] = initialiserMotCache($_SESSION['mot2']);
    $_SESSION['lettresDevinnees'] = [];
    $_SESSION['nombreEssais'] = 0;
}
else
{
	//echo '<BR>Mot1='.$_SESSION['mot1'];
	//echo '<BR>motCache1='.$_SESSION['motCache1'];
	//echo '<BR>Mot2='.$_SESSION['mot2'];
	//echo '<BR>motCache2='.$_SESSION['motCache2'];
}

$gagne1=0;
$gagne2=0;


fctAfficheEntete("Double Pendu");
fctAfficheBtnBack();



// Vérifier si une lettre a été soumise
if (isset($_POST['lettre'])) {

    $lettre = strtoupper($_POST['lettre']);
	if ($lettre=="#")
		$lettre = " ";
    // Vérifier si la lettre a déjà été devinée      ======================================
    if (in_array($lettre, $_SESSION['lettresDevinnees'])) {
        $message1 = "<H1>".implode(' ', str_split($_SESSION['motCache1']))."</H1>";
		$message2 = "<H1>".implode(' ', str_split($_SESSION['motCache2']))."</H1>";
    } else {
        // Mettre à jour le mot caché et les lettres devinées
        $_SESSION['motCache1'] = mettreAJourMotCache($_SESSION['mot1'], $_SESSION['motCache1'], $lettre);
        $_SESSION['motCache2'] = mettreAJourMotCache($_SESSION['mot2'], $_SESSION['motCache2'], $lettre);
        $_SESSION['lettresDevinnees'][] = $lettre;

        // Vérifier si la lettre est dans le mot
        if (strpos(strtoupper($_SESSION['mot1']), strtoupper($lettre)) === false && strpos(strtoupper($_SESSION['mot2']), strtoupper($lettre)) === false) {
            $_SESSION['nombreEssais']++;
        }

        // Vérifier si le mot a été entièrement deviné
        if (strtoupper($_SESSION['motCache1']) == strtoupper($_SESSION['mot1'])) {
            $message1 = "Félicitations ! Vous avez deviné le mot : <b>" . strtoupper($_SESSION['mot1']);
            $message1 .= "</b><BR>";
            $message1 .= fctReturnDefinitionPetitLarousse(strtoupper($_SESSION['mot1']),100);
            $gagne1=1;
        } else {
            $message1 = "<H1>".implode(' ', str_split($_SESSION['motCache1']))."</H1>";
        }
        // Vérifier si le mot a été entièrement deviné
        if (strtoupper($_SESSION['motCache2']) == strtoupper($_SESSION['mot2'])) {
            $message2 = "<BR>Félicitations ! Vous avez deviné le mot : <b>" . strtoupper($_SESSION['mot2']);
            $message2 .= "</b><BR>";
            $message2 .= fctReturnDefinitionPetitLarousse(strtoupper($_SESSION['mot2']),100);
            $gagne2=1;
        } else {
            $message2 = "<H1>".implode(' ', str_split($_SESSION['motCache2']))."</H1>";
        }
   }
	

} else {
    $message1 = "<H1>".implode(' ', str_split($_SESSION['motCache1']))."</H1>";
	$message2 = "<H1>".implode(' ', str_split($_SESSION['motCache2']))."</H1>";
}

// Liste des lettres de l'alphabet
$lettresAlphabet = range('A', 'Z');

//Recherche de toutes les lettres "Success"
$motcomplet = $_SESSION['mot1'].$_SESSION['mot2'];
$lettresToutes = str_split($motcomplet);
sort($lettresToutes);
$lettresUniques = array_unique($lettresToutes);
$lettresSuccess = implode("", $lettresUniques);

echo $message1;
echo $message2;


if ($gagne1==1 && $gagne2==1) {
    $_SESSION['scoreAjout'] = 10-$_SESSION['nombreEssais'];
}

fctAfficheScore();


?>

<form method="post" action="" id="formDevinez">
    <input  type="hidden" type="text" id="lettre" name="lettre" maxlength="1" required size="2">
    <button id="MonBouton" type="hidden" style="display: none;" type="submit">Devinez</button>

</form>

<?php 

switch (true) {
    case $_SESSION['nombreEssais']==0:
       $Taille = '0px 0px';
        break;
    case $_SESSION['nombreEssais']==1:
        $Taille = '-210px 0px';
        break;
    case $_SESSION['nombreEssais']==2:
        $Taille = '-425px 0px';
        break;
	case $_SESSION['nombreEssais']==3:
        $Taille = '-640px 0px';
        break;
	case $_SESSION['nombreEssais']==4:
        $Taille = '-850px 0px';
        break;
	case $_SESSION['nombreEssais']==5:
        $Taille = '-1055px 0px';
        break;
	case $_SESSION['nombreEssais']==6:
        $Taille = '-1268px 0px';
        break;
	case $_SESSION['nombreEssais']>=7:
        $Taille = '-1500px 0px';
        break;
}

if ($gagne1==1 && $gagne2==1) {
    $Taille = '-1710px 0px';
}

?>
<img src="Pix.gif" alt="" width="170" height="170" style="background:url(Pendu.png) <?php echo $Taille; ?>;" />
<div id="clavier">
<?php
	if ($gagne1==0 || $gagne2==0)
	{
		foreach ($lettresAlphabet as $lettre) {
			$classeLettre = in_array($lettre, $_SESSION['lettresDevinnees']) ? 'lettre-utilisee' : '';
			echo "<div class='tuile-lettre $classeLettre' onclick='choisirLettre(\"$lettre\")'>$lettre</div>";
		}
	}
?>
</div>

<?php
$scoreFinal = (10-$_SESSION['nombreEssais']);
if ($scoreFinal<0)
    $scoreFinal = 0;

echo "<B>".$_SESSION['nombreEssais']."</B> erreur(s) = <B>".$scoreFinal."</B> points en fin de partie !"; 
?>

<!-- Bouton pour recommencer -->
<form method="post" action="">
    <button type="submit" class="MonButton" name="recommencer">Deux autres mots !</button>
</form>

<script>
    function goreload(monFormulaire) {
        document.getElementById(monFormulaire).submit();
    }
    function choisirLettre(lettre) {
        // Mettre à jour le champ de saisie avec la lettre choisie
        //document.getElementById('lettre').value = lettre;
		document.getElementById('lettre').value = lettre === "#" ? "#" : lettre;
        // Tester si la lettre 'o' est contenue dans la chaîne
        maChaine = '<?php echo $lettresSuccess; ?>';
        if (maChaine.includes(lettre)) {
            playSound('successShort.mp3');
        } else {
            playSound('errorShort.mp3');
        }
        // Soumettre automatiquement le formulaire
        setTimeout(goreload('formDevinez'), 1000);
    }
</script>

<?php
fctAffichePiedPage();
?>