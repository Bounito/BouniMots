<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Recherche de Mots</title>
    <style>
        body {
            font-family: Arial;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
<div class="centered-div">
<h2>Recherche de Mots contenant</h2>

<form action="" method="post">
    <label for="serieLettres">Série de lettres (3 lettres minimum) :</label>
    <input type="text" id="serieLettres" name="serieLettres" minlength="3" required>
    <button type="submit">Rechercher</button>
</form>
</div>

<div id="resultatsContainer"><?php echo $resultats; ?></div>

<BR>
<BR>
<div class="centered-div">
<SMALL>Bounito 2024 ©<BR><A href="/." class="MonLien">Retour Menu</A></SMALL> 
</div>

<script>
    $(document).ready(function () {
        $('#serieLettres').on('input', function () {
            var serieLettres = $(this).val();

            if (serieLettres.length >= 3) {
                // Faire une requête AJAX pour obtenir les résultats
                $.ajax({
                    url: 'MotsContenant_Verif.php', // Remplacez ceci par le nom de votre script PHP
                    type: 'POST',
                    data: {serieLettres: serieLettres},
                    success: function (resultats) {
                        // Mettre à jour le conteneur des résultats avec les nouveaux résultats
                        $('#resultatsContainer').html(resultats);
                    }
                });
            } else {
                // Si la saisie est inférieure à 3 caractères, effacer les résultats
                $('#resultatsContainer').html('');
            }
        });
    });
</script>

</body>
</html>
