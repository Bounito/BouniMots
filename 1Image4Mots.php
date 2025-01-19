<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

fctAfficheEntete("1 image 4 Mots");

fctAfficheBtnBack();

fctAfficheScore();
echo "<BR>";
fctAfficheProgressBar();
$mesthemeRepertoire = ["Listes/", "NomPrenom/", "Phrases/"];
$themeRepertoire = fctAfficheBtnTheme($mesthemeRepertoire);
$theme = basename($themeRepertoire);

if (isset($_GET['th'])) {
    echo "\r\n<form method=\"post\" id=\"monFormulaire\" action='".$_SERVER['PHP_SELF']."?th=".$_GET['th']."'>";
}
else {
    echo "\r\n<form method=\"post\" id=\"monFormulaire\" action='".$_SERVER['PHP_SELF']."?th=🎲'>";
}
    
echo "\r\n<input type='hidden' name='scoreHidden' id='scoreHidden' value='VIDE'>";
echo "\r\n<input type='hidden' name='score' id='score' value='".$_SESSION['score']."'>";


$motsAuHasard = fctRandMots($themeRepertoire.'.txt',4);

// Remplacer les apostrophes par des espaces dans chaque élément du tableau
$motsAuHasard = array_map(function($mot) {
    return str_replace("'", " ", $mot);
}, $motsAuHasard);

$motChoisi = $motsAuHasard[array_rand($motsAuHasard)];

echo "\r\n<BR>Que représente cette image sur le thème <b>$theme</b> ?<BR>";

$keyBing = "%2B\"".$motChoisi."\" (".$theme.")"; //%2B=+
$keyBing2 = "%2B\"".$motChoisi."\""; //%2B=+
$keyBing = str_replace("&"," ",$keyBing);
$keyBing = str_replace(" ","%20",$keyBing);
$keyBing2 = str_replace("&"," ",$keyBing2);
$keyBing2 = str_replace(" ","%20",$keyBing2);
echo "\r\n<BR><DIV id=\"divImg1\" class=\"divImage\" style=\"display: block;\"><IMG id=\"image1\" class=\"styleImage\" onclick='toggleDiv()' title='".$keyBing."' src='https://th.bing.com/th?w=500&h=500&rs=1&c=0&p=0&mkt=fr-FR&cc=FR&setlang=fr&q=".$keyBing."'></DIV>";
echo "\r\n<BR><DIV id=\"divImg2\" class=\"divImage\" style=\"display: none;\"><IMG id=\"image2\" class=\"styleImage\" onclick='toggleDiv()' title='".$keyBing2."' src='https://th.bing.com/th?w=500&h=500&rs=1&c=0&p=0&mkt=fr-FR&cc=FR&setlang=fr&q=".$keyBing2."'></DIV>";
?>

<script>
    function toggleDiv() {
        var divElement = document.getElementById('divImg1');
        var divElement2 = document.getElementById('divImg2');
        // Changez la visibilité du div
        if (divElement.style.display === 'none' || divElement.style.display === '') {
            // Si le div est masqué, affichez-le
            divElement.style.display = 'block';
            divElement2.style.display = 'none';
        } else {
            // Sinon, masquez-le
            divElement.style.display = 'none';
            divElement2.style.display = 'block';
        }
    }
</script>

<?php
echo "\r\n<TABLE BORDER=0 WIDTH=100%>";
echo "\r\n<TR><TD WIDTH=20%>&nbsp;</TD><TD COLSPAN=3><DIV class=\"MonDivBouton\" id='sol1' onclick=\"clicDivBouton(this,'$motChoisi','$motsAuHasard[0]','monFormulaire',1,4,".$_SESSION['WinStreak'].");\"><span>".$motsAuHasard[0]."</span></DIV></TD><TD WIDTH=20%>&nbsp;</TD></TR>";
echo "\r\n<TR><TD WIDTH=40% COLSPAN=2><DIV class=\"MonDivBouton\" id='sol2' onclick=\"clicDivBouton(this,'$motChoisi','$motsAuHasard[1]','monFormulaire',1,4,".$_SESSION['WinStreak'].");\"><span>".$motsAuHasard[1]."</span></DIV></TD><TD WIDTH=10%>&nbsp;</TD>";
echo "\r\n<TD WIDTH=40% COLSPAN=2><DIV class=\"MonDivBouton\" id='sol3'  onclick=\"clicDivBouton(this,'$motChoisi','$motsAuHasard[2]','monFormulaire',1,4,".$_SESSION['WinStreak'].");\"><span>".$motsAuHasard[2]."</span></DIV></TD></TR>";
echo "\r\n<TR><TD WIDTH=20%>&nbsp;</TD><TD COLSPAN=3><DIV class=\"MonDivBouton\" id='sol4'  onclick=\"clicDivBouton(this,'$motChoisi','$motsAuHasard[3]','monFormulaire',1,4,".$_SESSION['WinStreak'].");\"><span>".$motsAuHasard[3]."</span></DIV></TD><TD WIDTH=10%>&nbsp;</TD></TR>";
echo "\r\n</TABLE>";

echo "\r\n<BR><div id=\"messageDiv\" class=\"centered-div\" style=\"font-size:30px;\">";
echo "\r\n<SMALL>Clic sur l'image pour une alternative !</SMALL>";
echo "\r\n</div>";


?>

</p>
<HR>
<BR>
<!-- Bouton pour recharger la page -->
<button class="MonButton" type="submit"><br />Un autre mot !<br />&nbsp;</button>
<HR>

<?php

fctAffichePiedPage();
?>