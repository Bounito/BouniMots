<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

fctAfficheEntete("Histoire 📆");

fctAfficheBtnBack();

fctAfficheScore();

echo "\r\n<form method=\"post\" id=\"monFormulaire\" action='".$_SERVER['PHP_SELF']."'>";
    
echo "\r\n<input type='hidden' name='scoreHidden' id='scoreHidden' value='VIDE'>";
echo "\r\n<input type='hidden' name='score' id='score' value='".$_SESSION['score']."'>";


$auHasard = fctRandMot('Histoire.txt');

//$nbInitAleatoire = rand(0, 2024);
$nbInitAleatoire = 1234;
$nbInitFormatte = str_pad($nbInitAleatoire, 4, '0', STR_PAD_LEFT);
$nbInitSiecle = substr($nbInitFormatte,0,2);
$nbInitDizaine = substr($nbInitFormatte,2,2);

$annee = substr($auHasard,0,4);
$description = substr($auHasard,strpos($auHasard,":")+1);
echo "\r\n<div class=\"centered-div\">";
echo "\r\n<BR>En quelle année a eu lieu";
echo "\r\n<H3>$description</H3>";
echo "\r\n</div>";

?>
<DIV id='divSaisie'>

<?php
/*
	<div class="slidecontainer">
	<p>1️⃣ Choix du siecle :</p>
	<small>
	<TABLE BORDER=0 WIDTH=100%>
		<TR><TD WIDTH=20% ALIGN=LEFT>0</TD>
		<TD WIDTH=20% ALIGN=LEFT>500</TD>
		<TD WIDTH=20% ALIGN=LEFT>1000</TD>
		<TD WIDTH=20% ALIGN=LEFT>1500</TD>
		<TD WIDTH=10% ALIGN=RIGHT>2000</TD></TR>
	</TABLE>
	</small>
	<input type="range" list="markersSiecle" min="0" max="20" value="<?php echo $nbInitSiecle;?>" class="slider" id="rangeDecenie">
	<datalist id="markersSiecle">
	<?php
	for ($i=0;$i<=20;$i++) {
		echo "\n<option value='$i' label='$i'></option>";
	}
	?>
	</datalist>
	</div>
	<div class="slidecontainer">
	<p>2️⃣ Votre réponse : </p>
	<small>
	<TABLE BORDER=0 WIDTH=100%>
		<TR><TD WIDTH=20% ALIGN=LEFT id='yearMin'><?php echo $nbInitSiecle;?>00</TD>
		<TD WIDTH=20% ALIGN=CENTER id='yearMoy1'><?php echo $nbInitSiecle;?>25</TD>
		<TD WIDTH=20% ALIGN=CENTER id='yearMoy2'><?php echo $nbInitSiecle;?>50</TD>
		<TD WIDTH=20% ALIGN=CENTER id='yearMoy3'><?php echo $nbInitSiecle;?>75</TD>
		<TD WIDTH=20% ALIGN=RIGHT id='yearMax'><?php echo $nbInitSiecle;?>99</TD></TR>
	</TABLE>
	</small>
	<input type="range" list="markersReponse" min="0" max="99" value="<?php echo $nbInitDizaine;?>" class="slider" id="rangeReponse">
	<datalist id="markersReponse">
	<?php
	for ($i=0;$i<=99;$i++) {
		echo "\n<option value='$i' label='$i'></option>";
	}
	?>
	</datalist>
	</div>
*/
?>
	<p><input type="hidden" id="libReponse" value="<?php echo $nbInitFormatte;?>" min="0" max="2024" style="text-align: center;font-size: 24px;"></p>

<SCRIPT>
libReponse.addEventListener(`focus`, () => libReponse.select());
</SCRIPT>

Clic sur les rouleaux pour sélectionner une date :
<TABLE BORDER=0 WIDTH=100%>
	<TR> 
		<TD WIDTH=25% id="TD0">
			<TABLE BORDER=0 WIDTH=100% id='table0'>
				<TR onclick="fctRoll(this,'+1',0);" BGCOLOR="WHITE"><TD>0</TD></TR>
				<TR><TD><H1>1</H1></TD></TR>
				<TR onclick="fctRoll(this,'-1',0);"> <TD>2</TD></TR>
			</TABLE>
		</TD>
		<TD WIDTH=25% id="TD1">
			<TABLE BORDER=0 WIDTH=100% id='table1'>
				<TR onclick="fctRoll(this,'+1',1);"><TD>0</TD></TR>
				<TR><TD><H1>1</H1></TD></TR>
				<TR onclick="fctRoll(this,'-1',1);"> <TD>2</TD></TR>
			</TABLE>
		</TD>
		<TD WIDTH=25% id="TD2">
			<TABLE BORDER=0 WIDTH=100% id='table2'>
				<TR onclick="fctRoll(this,'+1',2);"><TD>0</TD></TR>
				<TR><TD><H1>1</H1></TD></TR>
				<TR onclick="fctRoll(this,'-1',2);"> <TD>2</TD></TR>
			</TABLE>
		</TD>
		<TD WIDTH=25% id="TD3">
			<TABLE BORDER=0 WIDTH=100% id='table3'>
				<TR onclick="fctRoll(this,'+1',3);"><TD>0</TD></TR>
				<TR><TD><H1>1</H1></TD></TR>
				<TR onclick="fctRoll(this,'-1',3);"> <TD>2</TD></TR>
			</TABLE>
		</TD>
	</TR>
</TABLE>

<SCRIPT>

let valRoll = [1, 2, 3, 4];
fctRoll('TD0',0,0);
fctRoll('TD1',0,1);
fctRoll('TD2',0,2);
fctRoll('TD3',0,3);

function fctMajVals(valCible,colonne) {
	var valReturn = valCible;
	if (colonne==0) {
		if (valCible>2) {
			valReturn = 0;
		}
		if (valCible<0) {
			valReturn = 2;
		}
	} else {
		if (valCible>9) {
			valReturn = 0;
		}
		if (valCible<0) {
			valReturn = 9;
		}
	}

	return valReturn;
}
function greyScale(value) {
    if (value < 0 || value > 9) {
        throw new Error("Value must be between 0 and 9");
    }
    // Convertir la valeur (0 à 9) en un niveau de gris (255 à 0)
    // Nous utilisons 28 au lieu de 25.5 pour ne pas atteindre le noir complet
    const grey = Math.round(255 - value * 18);
    // Formatter en hexadécimal avec deux chiffres
    const hex = grey.toString(16).padStart(2, '0');
    // Retourner le code couleur hexadécimal
    return `#${hex}${hex}${hex}`;
}
function fctRoll(monTd,valChange,colonne) {
	valRoll[colonne] = valRoll[colonne] + parseInt(valChange);
	valRoll[colonne] = fctMajVals(valRoll[colonne],colonne);
	var val0 = fctMajVals(valRoll[colonne]-2,colonne);
	var val1 = fctMajVals(valRoll[colonne]-1,colonne);
	var val2 = fctMajVals(valRoll[colonne],colonne);
	var val3 = fctMajVals(valRoll[colonne]+1,colonne);
	var val4 = fctMajVals(valRoll[colonne]+2,colonne);
	document.getElementById("table"+colonne).innerHTML = "<TR style='cursor:zoom-out;user-select:none;font-size:10px;' onclick=\"fctRoll(this,'-2',"+colonne+");\" BGCOLOR='"+greyScale(val0)+"'><TD>"+val0+"</TD></TR>";
	document.getElementById("table"+colonne).innerHTML += "<TR style='cursor:zoom-out;user-select:none;font-size:20px;' onclick=\"fctRoll(this,'-1',"+colonne+");\" BGCOLOR='"+greyScale(val1)+"'><TD>"+val1+"</TD></TR>";
	document.getElementById("table"+colonne).innerHTML += "<TR style='cursor:zoom-in;user-select:none;font-size:50px;' onclick=\"fctRoll(this,'+1',"+colonne+");\" BGCOLOR='"+greyScale(val2)+"'><TD>"+val2+"</TD></TR>";
	document.getElementById("table"+colonne).innerHTML += "<TR style='cursor:zoom-in;user-select:none;font-size:20px;' onclick=\"fctRoll(this,'+1',"+colonne+");\" BGCOLOR='"+greyScale(val3)+"'><TD>"+val3+"</TD></TR>";
	document.getElementById("table"+colonne).innerHTML += "<TR style='cursor:zoom-in;user-select:none;font-size:10px;' onclick=\"fctRoll(this,'+2',"+colonne+");\" BGCOLOR='"+greyScale(val4)+"'><TD>"+val4+"</TD></TR>";
	if (valChange>0) {
		//playSound('clic.mp3');
		playSound('swipe-whoosh.mp3');
	} else {
		//playSound('clicReverse.mp3');
		playSound('swing-whoosh2.mp3');
	}
	libReponse.value = valRoll[0].toString()+valRoll[1].toString()+valRoll[2].toString()+valRoll[3].toString();
}
</SCRIPT>

	<BR>
	<button class="MonButton" type="button" onclick='fctClickDate(<?php echo $annee; ?>);' ><br />Valider cette date !<br />&nbsp;</button>
	<BR>
</DIV>

<DIV id='divReponse' style='display:none;'>
<?php
	echo "<A href=\"".fctBingSrc($auHasard,1000)."\" target=_blank>";

	fctAfficheImage($auHasard,400);
	echo "</A>";
	echo "<H1>".$annee."&nbsp;&nbsp;";
	fctAfficheGoogle($auHasard);
	echo "</H1>";
?>
</DIV>
<DIV id='divMessage' style='display:none;'>
</DIV>
<script>
var rangeDecenie = document.getElementById("rangeDecenie");
var rangeReponse = document.getElementById("rangeReponse");
var libReponse = document.getElementById("libReponse");
/*
function ajouterZero(nombre) {
    return nombre < 10 ? '0' + nombre : '' + nombre;
}

rangeDecenie.oninput = function() {
	libReponse.value = ajouterZero(rangeDecenie.value)+ajouterZero(rangeReponse.value);
	document.getElementById("yearMin").innerHTML = ajouterZero(rangeDecenie.value)+"00";
	document.getElementById("yearMoy1").innerHTML = ajouterZero(rangeDecenie.value)+"25";
	document.getElementById("yearMoy2").innerHTML = ajouterZero(rangeDecenie.value)+"50";
	document.getElementById("yearMoy3").innerHTML = ajouterZero(rangeDecenie.value)+"75";
	document.getElementById("yearMax").innerHTML = ajouterZero(rangeDecenie.value)+"99";
}

rangeReponse.oninput = function() {
	libReponse.value = ajouterZero(rangeDecenie.value)+ajouterZero(rangeReponse.value);
}
*/
function fctClickDate(dateCible) {
	var reponse = document.getElementById("libReponse").value;
	document.getElementById("divSaisie").style.display = "none";
	document.getElementById("divMessage").style.display = "block";
	document.getElementById("divReponse").style.display = "block";
	var valeurAbsolue = Math.abs(reponse-dateCible);
	var message = "<big>Tu as répondu <b>"+reponse+"</b>...<br>";
	message += "Tu es à <b>"+valeurAbsolue+" années</b> du bon résultat !<br>";
	message += "<div style='color:white;background:linear-gradient(to right, green, red);'>";
	var monWidth = valeurAbsolue;
	if (valeurAbsolue>90) {
		monWidth = 90
	}
	message += "<TABLE BORDER=0 WIDTH=100%><TR><TD WIDTH="+monWidth+"%></TD><TD ALIGN=LEFT><BIG>"+valeurAbsolue+"</BIG></TD></TR></TABLE>";
	message += "</div>";
	message += "<SMALL><TABLE BORDER=0 WIDTH=100%><TR><TD ALIGN=LEFT wIDTH=10%>20pt</TD><TD ALIGN=LEFT wIDTH=10%>10pt</TD><TD ALIGN=LEFT wIDTH=10%>0pt</TD><TD>-1pt</TD><TD ALIGN=RIGHT WIDTH=50%>-2pt</TD></TR></TABLE></SMALL>";

	var scoreCible = 0;
	if (valeurAbsolue==0) {
		message += "😎 Trop fort !<br>";
		scoreCible = 20;
		message += "<b>"+scoreCible+" points</b> !</big>";
	} else if (valeurAbsolue<=20) {
		message += "😀 Bien, score positif !<br>";
		scoreCible = 20-valeurAbsolue;
		message += "<b>"+scoreCible+" points</b> !</big><BR>(20pt -1pt par année d'écart)";
	} else {
		message += "🙄 Tu es loin !<br>";
		console.log(valeurAbsolue / 50);
		console.log(Math.floor(valeurAbsolue / 50));
		console.log(-1*Math.floor(valeurAbsolue / 50));
		scoreCible = -1*Math.floor(valeurAbsolue / 50);
		message += "<b>"+scoreCible+" points</b></big><BR>(-1 pt tous les 50 ans)";
	}



	document.getElementById("divMessage").innerHTML = message;
}
/* =============================================== */
/* ===============Gestion du glisser============== */
/* =============================================== */
const cells = document.querySelectorAll('td');
let isDragging = false;
let startY;
let activeCell;

function handleStart(event) {
  isDragging = true;
  // Pour les événements tactiles, utilisez touches[0]
  startY = event.type.includes('touch') ? event.touches[0].clientY : event.clientY;
  activeCell = this;
  
  console.log("Début de glissement sur " + this.id);
}

function handleMove(event) {
  if (isDragging) {
    const currentY = event.type.includes('touch') ? event.touches[0].clientY : event.clientY;
    const currentMove = currentY - startY;
    //console.log("Déplacement courant en Y sur " + activeCell.id + ": " + currentMove);
/*	colonne = activeCell.id.match(/\d+$/); // Extrait les chiffres à la fin de l'ID
	if (currentMove<0) {
		if (currentMove<-10) {
			fctRoll(activeCell.id,2,colonne);
		} else {
			fctRoll(activeCell.id,1,colonne);
		}
	} else {
		if (currentMove>10) {
			fctRoll(activeCell.id,-2,colonne);
		} else {
			fctRoll(activeCell.id,-1,colonne);
		}
	}*/
  }
}

function handleEnd(event) {
  if (isDragging) {
    const endY = event.type.includes('touch') ? event.changedTouches[0].clientY : event.clientY;
    const endMove = endY - startY;
	const myCell = activeCell.id

    console.log("Déplacement total en Y sur " + activeCell.id + ": " + endMove);

    if (endMove < 0) {
      console.log("Glissé vers le haut sur " + activeCell.id);
    } else if (endMove > 0) {
      console.log("Glissé vers le bas sur " + activeCell.id);
    }

	colonne = activeCell.id.match(/\d+$/); // Extrait les chiffres à la fin de l'ID
	console.log("colonne=" + colonne);
	if (endMove>0) {
		rollUpDown = -1;
	} else {
		rollUpDown = 1;
	}
	console.log("rollUpDown=" + rollUpDown);

	function processMove(i, totalMoves) {
		if (i < totalMoves) {
			let delay = (Math.exp(i / totalMoves * Math.log(1001)) - 1); // Math.log(1001) car exp(log(1001)) = 1001 pour atteindre juste 1000
			setTimeout(() => {
				console.log(i + "/" + totalMoves + " Time=" + delay.toFixed(2)); // Affichage du délai arrondi
				fctRoll(activeCell, rollUpDown, colonne);
				processMove(i + 1, totalMoves); // Appel récursif pour la prochaine itération
			}, delay);
		}
	}
	processMove(0, Math.abs(endMove));


    isDragging = false;
    activeCell = null;
  }
}

cells.forEach(cell => {
  // Événements de souris
  cell.addEventListener('mousedown', handleStart);
  document.addEventListener('mousemove', handleMove);
  document.addEventListener('mouseup', handleEnd);

  // Événements tactiles
  cell.addEventListener('touchstart', handleStart);
  document.addEventListener('touchmove', handleMove);
  document.addEventListener('touchend', handleEnd);
});

/* =============================================== */
</script>


<HR>

<BR>

<!-- Bouton pour recharger la page -->
<button class="MonButton" type="bouton" onclick="location.reload();"><br />Une autre date !<br />&nbsp;</button>
<HR>


<?php
fctAffichePiedPage();
?>