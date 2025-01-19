<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

$motsAuHasard=fctRandMots('PetitLarousse.txt',5);

fctAfficheEntete("5 mots du PetitLarousse");
fctAfficheBtnBack();
// Afficher les 5 mots choisis au hasard
echo "<TABLE class=\"MaTable\">";
foreach ($motsAuHasard as $mot) {
    echo "<TR>";
    echo "<TD>";
        fctAfficheGoogle($mot);
    echo "</TD>";
    echo "<TD><A href='".fctBingSrc($mot,1000)."' target=_blank>";
        fctAfficheImage($mot,100);
    echo "</A></TD>";
    echo "<TD><B>$mot</B></TD>";

    echo "<TD WIDTH=80%>";
    ?>
    <script>
        // Appeler la fonction de manière asynchrone
        fetchDefinition();
        function fetchDefinition() {
            fetch('fctDefinition.php?mot=<?php echo urlencode($mot); ?>&taille=100')
            .then(response => response.text())
            .then(data => {
                // Mettre à jour la page avec le résultat
                document.getElementById('div<?php echo urlencode($mot); ?>').innerHTML = '<small>' + data + '</small>';
            })
            .catch(error => console.error('Erreur :', error));
        }
    </script>
    <?php
    echo "<div id='div".urlencode($mot)."'><IMG src='load.gif'><small>Recherche de définition</small></div>";
    echo "</TD>";
    echo "<TR>";
}
echo "</TABLE>";
?>



<!-- Bouton pour recommencer -->
<form method="post" id="monFormulaire" action="">
    <button type="submit" class="MonButton" name="recommencer">Encore !</button>
</form>

<?php
fctAffichePiedPage();
?>