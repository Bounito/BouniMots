<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Myrapide - Pyramide</title>
	
	    <style>
		body {
			font-family: Arial;
		}
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
        .lettres {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .lettre {
			display: flex; /* Ajout de display: flex; */
			align-items: center; /* Ajout de align-items: center; */
			justify-content: center;
            margin: 2px;
            padding: 10px;
            font-size: 30px;
            cursor: pointer;
			text-transform: uppercase;
			border: 2px solid #000; /* Ajout de la bordure */
			border-radius: 5px; /* Ajout d'arrondi aux coins */
			font-family: Arial, sans-serif; /* Utilisation de la police Arial */
			width: 20px; /* Définir la largeur de chaque case */
			text-align: center; /* Centrer le texte horizontalement */
        }
		.lettre-grise {
			background-color: #ccc; /* Couleur de fond grise */
			color: #888; /* Couleur de texte grise */
			cursor: not-allowed; /* Curseur non autorisé */
		}
        #proposition {
            margin-top: 20px;
            padding: 5px;
            font-size: 16px;
        }

        #resultat {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
        }
		
		.indice {
			display: inline-block;
			margin: 5px;
			padding: 10px;
			border: 2px solid #000;
			border-radius: 5px;
			cursor: pointer;
		}

      .MonLien {
        background-color: #00005f;
        border: none;
        color: white;
        padding: 8px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 2px 2px;
        cursor: pointer;
        border-radius: 20px;
      }


    </style>
	
</head>
<body>
<center>
<H1>Myrapide - Pyramide</H1>
<H4>Sauras-tu trouver le mot mystère ?</H4>
<?php


function obtenirSynonymes($type,$mot) {

	//echo '<BR>Mot='.$mot;

    // Construire l'URL
    $url = "https://www.cnrtl.fr/$type/$mot";
	//echo '<BR><A href="'.$url.'" target=_blank>Lien</A>';

    // Initialiser cURL
    $ch = curl_init($url);

    // Configurer les options cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Exécuter la requête cURL
    $resultat = curl_exec($ch);

    // Vérifier s'il y a eu des erreurs
    if (curl_errno($ch)) {
        echo 'Erreur cURL : ' . curl_error($ch);
        return;
    }
    // Fermer la session cURL
    curl_close($ch);
    // Convertir le HTML en texte brut
    $MaChaine = strip_tags($resultat);
	// Supprimer les sauts de ligne
	$MaChaine = str_replace(["\r\n", "\r", "\n"], ' ', $MaChaine);
	// Remplacer les tabulations par des espaces
	$MaChaine = str_replace('\t', ' ', $MaChaine);
	// Supprimer les espaces insécables
	$MaChaine = str_replace("\xC2\xA0", ' ', $MaChaine); // Caractère UTF-8 pour l'espace insécable
	// Remplacer les différents types d'espaces par un simple espace
	$MaChaine = preg_replace('/[\pZ\pC]+/u', ' ', $MaChaine);
	// Supprimer les doubles espaces
	$MaChaine = preg_replace('/\s+/', ' ', $MaChaine);
	// Supprimer les espaces en début et fin de chaîne
	$MaChaine = trim($MaChaine);
	//echo "<BR><BR>MaChaine=".$MaChaine."<BR><BR>";
	//echo "<BR><BR>MaChaine=".'"'.$mot.'"'."<BR><BR>";
	$introuvable = strpos($MaChaine,"Cette forme est introuvable !");
	if ($introuvable == false) {
				
		$MaChaine = substr($MaChaine, 2*strlen($mot)+1861); // Suppression de l'entete
		$mot_norm = substr($MaChaine, 0,strpos($MaChaine, ','));
//echo "<BR>mot_norm=".$mot_norm."<BR>";

		$MaChaine = substr($MaChaine, strpos($MaChaine, ',')+2);
//echo "<BR><BR>MaChaine=".$MaChaine."<BR><BR>";		

		// Trouver la position de la première majuscule
		$positionMajuscule = strcspn($MaChaine, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
		// Extraire les premiers caractères jusqu'à la première majuscule
		$mot_type = substr($MaChaine, 0, $positionMajuscule);

		if ($type=="synonymie")
			echo "Nous cherchons un <B>".$mot_type."</B><BR>";
		
		// Rechercher la position de la première occurrence de guillemets
		$positionPremierGuillemet = strpos($MaChaine, '"');
		if ($positionPremierGuillemet !== false) {
			// Rechercher la position de la deuxième occurrence de guillemets à partir de la position suivant le premier
			$positionDeuxiemeGuillemet = strpos($MaChaine, '"', $positionPremierGuillemet + 1);
			
			// Trouver la position du dernier espace avant le premier guillemet
			$positionEspace = strrpos(substr($MaChaine, 0, $positionPremierGuillemet), ' ');
			//echo "La position de lespace est : $positionEspace";
			// Extraire le mot
			if ($positionEspace !== false) {
				$motAvantGuillemet = substr($MaChaine, $positionEspace + 1, $positionPremierGuillemet - $positionEspace - 1);
				//echo "Le mot avant le premier guillemet est : $motAvantGuillemet";
			} else {
				echo "Aucun mot trouvé avant le premier guillemet.";
			}
			
			if ($positionDeuxiemeGuillemet !== false) {
				// Extraire la sous-chaîne à partir de la deuxième occurrence
				$MaChaine = substr($MaChaine, $positionDeuxiemeGuillemet + 1);
				//echo "<BR><BR>Sous-chaîne à partir de la deuxième occurrence : $MaChaine";					
				
				$MaChaine = substr($MaChaine, 0, -132); //Suppression de la fin fixe

				//echo "<BR><BR><B>$type</B> :<BR>$MaChaine";
				
				$indices = explode(' ',$MaChaine);
								
				// Afficher les indices avec des boutons
				foreach ($indices as $index => $indice) {
					if (strlen($indice)<=2)
						break;					
					if ($index>=5)
						break;
					if ($type=="synonymie")
					{
						echo "<div class=\"indice\" onclick=\"reveleIndice(this,$index)\">Indice Synonyme " . ($index + 1) . "</div>";
						echo "<div id=\"indice$index\" style=\"display:none;\">Synonyme " . ($index + 1) . " : <B>$indice</B></div>";
					}
					else
					{
						$index = $index + 10;
						echo "<div class=\"indice\" onclick=\"reveleIndice(this,$index)\">Indice Antonyme " . ($index + 1 - 10) . "</div>";
						echo "<div id=\"indice$index\" style=\"display:none;\">Antonyme " . ($index + 1 - 10) . " : <B>$indice</B></div>";
					}
					
				}
				
				
			} else {
				echo "Deuxième guillemet non trouvé.";
			}
		} else {
			echo "Premier guillemet non trouvé.";
		}	
		
	}
	else
		echo "<BR><BR><B>$type</B> :<BR>Pas de données pour ce mot...";
}

// Chemin vers le fichier texte
$cheminFichier = 'FrMaj20k.txt';

// Lire le contenu du fichier dans un tableau
$mots = file($cheminFichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$motAuHasard = $mots[array_rand($mots)];


echo "<div class=\"lettres\" id=\"lettresContainer\"></div>";

echo "<input type=\"text\" id=\"proposition\" placeholder=\"Saisissez votre proposition\">";

echo "<button onclick=\"verifierProposition()\">Vérifier</button>";

echo "<button onclick=\"effacerSaisie()\">Effacer</button>";

echo "<div id=\"resultat\">Saisissez votre proposition</div>";

obtenirSynonymes('synonymie',$motAuHasard);
echo "<BR>";
obtenirSynonymes('antonymie',$motAuHasard);

echo "<HR><p id=\"texteCache\" style=\"display: none;\">";

echo "Le mot était : <B>".$motAuHasard."</B>";
?>
</p>
<!-- Bouton de révélation -->
<button onclick="reveleTexte(this)">Révéler le mot</button>

<BR>
<!-- Bouton pour recharger la page -->
<button onclick="rechargerPage()">Un autre mot !</button>

<!-- Fin du contenu de la page -->

<script>
function reveleTexte(button) {
    // Sélectionner l'élément texte
    var texte = document.getElementById("texteCache");

    // Changer le style pour afficher le texte
    texte.style.display = "block";
	button.parentNode.removeChild(button);
}

// Mot mystère
var motMystere = "<?php echo $motAuHasard; ?>";

// Fonction pour mélanger les lettres du mot
function melangerLettres(mot) {
    return mot.split('').sort(function(){return 0.5-Math.random()}).join('');
}

// Création des boutons avec les lettres du mot mélangées
var lettresMelangees = melangerLettres(motMystere);
var lettresContainer = document.getElementById('lettresContainer');

for (var i = 0; i < lettresMelangees.length; i++) {
    var lettre = document.createElement('div');
    lettre.className = 'lettre';
    lettre.textContent = lettresMelangees[i];
    lettre.onclick = function() {
        ajouterLettre(this.textContent,this);
    };
    lettresContainer.appendChild(lettre);
}

// Fonction pour ajouter une lettre à la proposition
function ajouterLettre(lettre, bouton) {
    var propositionInput = document.getElementById('proposition');
    propositionInput.value += lettre;
	// Appliquer la classe pour griser le bouton
    bouton.classList.add('lettre-grise');
	bouton.onclick = '';
	if (motMystere.length==document.getElementById('proposition').value.length)
	{
		verifierProposition();
	}
}

// Fonction pour vérifier la proposition
function verifierProposition() {
    var propositionInput = document.getElementById('proposition');

    var proposition = propositionInput.value.toUpperCase();

    if (proposition === motMystere) {
        document.getElementById('resultat').textContent = '🏆 Félicitations ! '+proposition+' est le mot mystère.';
    } else {
        document.getElementById('resultat').textContent = '😪 Désolé, '+proposition+' n\'est pas le bon mot... Essayez à nouveau.';
    }
}

// Fonction pour effacer la saisie
function effacerSaisie() {
    var propositionInput = document.getElementById('proposition');
    propositionInput.value = '';
	document.getElementById('resultat').textContent = 'Saisissez votre proposition';
	var lettresContainer = document.getElementById('lettresContainer');
    var boutonsLettre = lettresContainer.getElementsByClassName('lettre');

    // Parcourir tous les boutons et réinitialiser leur état
    for (var i = 0; i < boutonsLettre.length; i++) {
        var bouton = boutonsLettre[i];
        bouton.classList.remove('lettre-grise'); // Supprimer la classe de grisage
        bouton.onclick = function() {
            ajouterLettre(this.textContent, this); // Réactiver le clic sur le bouton
        };
    }
}

function reveleIndice(button, index) {
    // Cacher tous les indices
    <?php foreach ($indices as $index => $indice) { ?>
        document.getElementById("indice<?php echo $index; ?>").style.display = "none";
    <?php } ?>
    // Révéler l'indice sélectionné
    document.getElementById("indice" + index).style.display = "block";
	// Supprimer le bouton du DOM
    button.parentNode.removeChild(button);
}

function rechargerPage() {
    location.reload(true); // Utilisez "true" pour forcer le rechargement depuis le serveur
}
</script>

<BR><BR><BR>
<HR>
Merci à <A href="https://www.cnrtl.fr" target=_blank>cnrtl.fr</A> !<BR>

<BR>
<SMALL>Bounito 2024 ©<BR><A href="/." class="MonLien">Retour Menu</A></SMALL> 
</center>
</body>
</html>