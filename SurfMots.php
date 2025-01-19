<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

fctAfficheEntete("Surf sur les mots");
fctAfficheBtnBack();
$mots=fctRandMots('PetitLarousse.txt',1);
$mot = $mots[0];

echo "<H3>$mot</H3>";
fctAfficheImage($mot,300);
echo "<BR><H4>Définitions Larousse</H4>";
echo fctReturnDefinitionPetitLarousse($mot,3000);
echo "<BR><H4>Définitions CNTL</H4>";
fctAfficheDefinition($mot,3000);
echo "<BR><H4>Synonymes</H4>";
fctAfficheSynoAnto($mot,10,"synonymie");
echo "<BR><H4>Antonymie</H4>";
fctAfficheSynoAnto($mot,10,"antonymie");
echo "<BR>";
fctAfficheGoogle($mot);
echo "<BR>";
echo $mot;
echo "<BR>";
?>
<!-- Bouton pour recommencer -->
<form method="post" id="monFormulaire" action="">
    <button type="submit" class="MonButton" name="recommencer">Encore !</button>
</form>

<?php
fctAffichePiedPage();
?>