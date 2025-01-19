function playSound(fileName) {
    var audio = document.getElementById('myAudio');
    audio.src = 'sound/' + fileName;
    audio.play();
}

// Fonction JavaScript pour rendre le div visible et effacer le bouton cliqué
function afficherDiv(button,mondiv) {
	var div = document.getElementById(mondiv);
	div.style.display = 'block'; // ou 'inline', 'flex', etc., selon le besoin
	button.parentNode.removeChild(button); //efface le bouton
}

// Fonction JavaScript pour rendre le divTheme
function afficherDivModal(mondiv) {
    var div = document.getElementById(mondiv);
	if (div.style.display == 'block') {
        div.style.display = 'none';
    }        
    else {
        div.style.display = 'block';
    }
    
}
// ==================================== PlayWelcome

function playWelcome() {
    var monIndice = Math.floor(Math.random() * 9) + 1;
    playSound('welcome'+monIndice+'.mp3');
}

// ==================================== Etoiles filantes
function createStar(clicDiv) {
    // Créer une nouvelle étoile
    var star = document.createElement("div");
    star.textContent = "⭐";
    star.classList.add("star");
    // Taille de l'écran
    //console.log('window='+window.innerWidth+'-'+window.innerHeight);
    // Position du parent
	//console.log('offsetLeft='+clicDiv.offsetLeft+' - offsetTop='+clicDiv.offsetTop);
    // Taille de la DIV
    //console.log('offsetWidth='+clicDiv.offsetWidth+' - offsetHeight='+clicDiv.offsetHeight);
    // Centre de la DIV : + ou - 50 pixels
	var posX = (clicDiv.offsetLeft+(clicDiv.offsetWidth/2) + Math.random() * 100 - 50);
	var posY = (clicDiv.offsetTop+(clicDiv.offsetHeight/2) + Math.random() * 100 - 50);
	star.style.left = posX + 'px';
    star.style.top = posY + 'px';
    document.getElementById("starsContainer").appendChild(star);

    // Définir la position de départ et d'arrivée de l'étoile
    var startTop = (posY*100)/window.innerHeight; // en pourcentage
    var startLeft = (posX*100)/window.innerWidth; // en pourcentage
    var endTop = 3; // en pourcentage
    var endLeft = 90; // en pourcentage

    // Définir la durée de l'animation (en millisecondes)
    var duration = 2000; // 2 secondes

    // Calculer la distance à parcourir pour chaque axe
    var distanceTop = endTop - startTop;
    var distanceLeft = endLeft - startLeft;

    // Horodatage du début de l'animation
    var startTime = null;

    // Fonction pour mettre à jour la position de l'étoile
    function updatePosition(timestamp) {
        // Initialiser le temps de départ si ce n'est pas déjà fait
        if (!startTime) {
            startTime = timestamp;
        }

        // Calculer le temps écoulé depuis le début de l'animation
        var elapsedTime = timestamp - startTime;

        // Calculer le pourcentage de complétion de l'animation
        var progress = elapsedTime / duration;

        // Limiter la progression à 1 (100%)
        progress = Math.min(progress, 1);

        // Calculer la nouvelle position de l'étoile
        var newTop = startTop + (distanceTop * progress);
        var newLeft = startLeft + (distanceLeft * progress);

        // Mettre à jour la position de l'étoile
        star.style.top = newTop + "%";
        star.style.left = newLeft + "%";

        // Continuer l'animation jusqu'à ce qu'elle soit terminée
        if (progress < 1) {
            requestAnimationFrame(updatePosition);
        }
    }

    // Démarrer l'animation de l'étoile
    requestAnimationFrame(updatePosition);
}
// ==================================== Etoiles filantes PERDU
function looseStar() {
    // Créer une nouvelle étoile
    var star = document.createElement("div");
    star.textContent = "⭐";
    star.classList.add("star");
    // Taille de l'écran
    //console.log('window='+window.innerWidth+'-'+window.innerHeight);
    // Position du parent
	//console.log('offsetLeft='+clicDiv.offsetLeft+' - offsetTop='+clicDiv.offsetTop);
    // Taille de la DIV
    //console.log('offsetWidth='+clicDiv.offsetWidth+' - offsetHeight='+clicDiv.offsetHeight);
    // Centre de la DIV : + ou - 50 pixels
	var posX = window.innerWidth - 10;
	var posY = 10;
	star.style.left = posX + 'px';
    star.style.top = posY + 'px';
    document.getElementById("starsContainer").appendChild(star);

    // Définir la position de départ et d'arrivée de l'étoile
    var startTop = (posY*100)/window.innerHeight; // en pourcentage
    var startLeft = (posX*100)/window.innerWidth; // en pourcentage
    var endTop = 100; // en pourcentage
    var endLeft = 50 + (Math.random() * 40) - 20; // en pourcentage + Math.random() * 100 - 50

    // Définir la durée de l'animation (en millisecondes)
    var duration = 2000; // 2 secondes

    // Calculer la distance à parcourir pour chaque axe
    var distanceTop = endTop - startTop;
    var distanceLeft = endLeft - startLeft;

    // Horodatage du début de l'animation
    var startTime = null;

    // Fonction pour mettre à jour la position de l'étoile
    function updatePosition(timestamp) {
        // Initialiser le temps de départ si ce n'est pas déjà fait
        if (!startTime) {
            startTime = timestamp;
        }

        // Calculer le temps écoulé depuis le début de l'animation
        var elapsedTime = timestamp - startTime;

        // Calculer le pourcentage de complétion de l'animation
        var progress = elapsedTime / duration;

        // Limiter la progression à 1 (100%)
        progress = Math.min(progress, 1);

        // Calculer la nouvelle position de l'étoile
        var newTop = startTop + (distanceTop * progress);
        var newLeft = startLeft + (distanceLeft * progress);

        // Mettre à jour la position de l'étoile
        star.style.top = newTop + "%";
        star.style.left = newLeft + "%";

        // Continuer l'animation jusqu'à ce qu'elle soit terminée
        if (progress < 1) {
            requestAnimationFrame(updatePosition);
        }
    }

    // Démarrer l'animation de l'étoile
    requestAnimationFrame(updatePosition);
}



// ==================================== Formulaires Quiz

function goreloadForm(monFormulaire) {
    document.getElementById(monFormulaire).submit();
}

// Min Max : nombre de choix
function clicDivBouton(clicDiv, motGagnant, motClic,monFormulaire,choixMin,choixMax,winStreak) {
    stopProgress();
    
    if (motGagnant === motClic) {
        clicDiv.style.backgroundColor = 'Lime';
        clicDiv.style.border = '5px solid Lime';
        if (document.getElementById(motClic) !== null) {
            document.getElementById(motClic).innerHTML = motClic;
            document.getElementById(motClic).style.padding = '5px 10px';
            document.getElementById(motClic).style.backgroundColor = 'rgba(0, 255, 0, 0.4)';
            clicDiv.style.position= 'relative';


            // Sélectionner tous les éléments avec la classe spécifiée
            var elements = document.getElementsByClassName('flex-text');
            // Parcourir la collection d'éléments
            for (var i = 0; i < elements.length; i++) {
                var monElement = elements[i];
                if (monElement.id!==motGagnant) {
                    monElement.innerHTML = monElement.id;
                    monElement.style.padding = '5px 10px';
                    monElement.style.backgroundColor = 'rgba(255, 69, 0, 0.4)';
                }
            }
            // Sélectionner tous les éléments avec la classe spécifiée
            var elementsBouton = document.getElementsByClassName('flex-bouton');
            // Parcourir la collection d'éléments
            for (var i = 0; i < elementsBouton.length; i++) {
                var monElementBouton = elementsBouton[i];
                if (monElementBouton.id!==motGagnant) {
                    monElementBouton.style.position= 'relative';
                }
            }
        }

        if (document.getElementById('imgCachee') !== null) {
            document.getElementById('imgCachee').style.display = 'block';
        }
        if (document.getElementById('scoreHidden').value=='VIDE') {
            document.getElementById('scoreHidden').value = scoreProgress;
            document.getElementById('score').value = parseInt(document.getElementById('score').value,10) + parseInt(document.getElementById('scoreHidden').value,10);
            document.getElementById('messageDiv').innerHTML = '🏆 Bravo !';
            //alert(winStreak % 5);
            
            if (parseInt(document.getElementById('scoreHidden').value,10)>0) {

                if (winStreak==0) {
                    var winStreakMessage = '';
                    var imgHTML = '';
                    playSound('success'+(winStreak % 5)+'.mp3');
                }
                else {
                    var winStreakMessage = '<BR>'+(winStreak+1)+' à la suite !';
                    var imgHTML = '<BR>';
                    for (let i = 0; i <= (winStreak % 5); i++) {
                        var monHeight=20+(10*i);
                        imgHTML += '<IMG src=\"img/WS'+i+'.png\" height='+monHeight+'>';
                    }
                    if ((winStreak % 5)==4) {
                        playSound('brass-fanfare.mp3');
                        //50 étoiles !
                        for (var i = 0; i < 50; i++) {
                            createStar(clicDiv);
                        }
                    }
                    else {
                        playSound('success'+(winStreak % 5)+'.mp3');
                    }
                }

                // Etoiles filantes
                var totalStars = parseInt(document.getElementById('scoreHidden').value,10);
                //console.log('totalStars='+totalStars);
                for (var i = 0; i < totalStars; i++) {
                    createStar(clicDiv);
                }

                document.getElementById('divFinalScore').innerHTML = '<H3>Bravo 🏆</H3><span style="color: LimeGreen;">'+document.getElementById('scoreHidden').value+' points de gagné !</span><BR>'+imgHTML+winStreakMessage;
            }
        }
        else {
            document.getElementById('messageDiv').innerHTML = '🏆 Bravo ! mais trop tard...';

            document.getElementById('divFinalScore').innerHTML = '<H3>Bien, mais trop tard 😓</H3><span style="color: OrangeRed;">'+document.getElementById('scoreHidden').value+' points !</span>';
            // Etoiles filantes PERDU
            var totalStars = -1 * parseInt(document.getElementById('scoreHidden').value,10);
            //console.log('totalStars='+totalStars);
            for (var i = 0; i < totalStars; i++) {
                looseStar();
            }

            playSound('pick.mp3');
        }
        document.getElementById('divFinalScore').style.display = 'block';
        document.getElementById('scoreDiv').innerHTML = '<IMG src="load.gif">';
        for (let i = choixMin; i <= choixMax; i++) {
            document.getElementById('sol' + i).onclick = null;
        }

        //setTimeout(goreloadForm(monFormulaire), 5000);
        setTimeout(function() {
            goreloadForm(monFormulaire);
        }, 2500);
    } else { // ==============================================Clic sur mauvaise case
        clicDiv.style.backgroundColor = 'OrangeRed';
        clicDiv.style.border = '5px solid OrangeRed';
        if (document.getElementById(motClic) !== null) {
            document.getElementById(motClic).innerHTML = motClic;
            document.getElementById(motClic).style.padding = '5px 10px';
            clicDiv.style.position= 'relative';
        }
        clicDiv.onclick = null; // Désactiver le clic pour la div actuelle

        if (document.getElementById('scoreHidden').value=='VIDE') {
            document.getElementById('scoreHidden').value = '-1';
        }
        else {
            document.getElementById('scoreHidden').value = parseInt(document.getElementById('scoreHidden').value,10) - 1;
        }
        switch (parseInt(document.getElementById('scoreHidden').value,10)) {
            case -1:
                document.getElementById('messageDiv').innerHTML = '😪 Et non... ';
                break;
            case -2:
                document.getElementById('messageDiv').innerHTML = '😨 encore raté... ';
                break;
            case -3:
                document.getElementById('messageDiv').innerHTML = '🥵 la loose... ';
                break;
            default:
                document.getElementById('messageDiv').innerHTML = '👹 aie...';
        }
        document.getElementById('messageDiv').innerHTML += '<BR>tu as cliqué sur : <B>'+motClic+'</B><BR><BR>(score '+document.getElementById('scoreHidden').value+')';
        document.getElementById('divFinalScore').innerHTML = document.getElementById('messageDiv').innerHTML;
        
        var progress = ((parseInt(document.getElementById('scoreHidden').value,10)*-1)*(100/(choixMax-choixMin)));
        document.getElementById("myBar").style.width = progress + "%";
        document.getElementById("myBar").style.backgroundColor = 'OrangeRed';
        document.getElementById("myBar").innerText = document.getElementById('scoreHidden').value+" points";

        if ((winStreak>0) && (document.getElementById('scoreHidden').value==-1)) {
            document.getElementById('divFinalScore').innerHTML += '<BR><BR>🔥 Winstreak<BR>à zéro 🚿 !';
            playSound('violin-lose.mp3');
        }
        else {
            playSound('error.mp3');
        }
        document.getElementById('divFinalScore').style.display = 'block';
        // Attendre une seconde avant de masquer à nouveau l'élément
        setTimeout(function() {
            document.getElementById('divFinalScore').style.display = 'none';
        }, 2000); // 1000 millisecondes = 1 seconde

        if (document.getElementById('scoreHidden').value == (-1*(choixMax-choixMin))) {

            for (let i = choixMin; i <= choixMax; i++) {
                if (document.getElementById('sol'+i).onclick != null) {
                    document.getElementById('sol'+i).style.backgroundColor = 'Lime';
                    document.getElementById('sol'+i).style.border = '5px solid Lime';
                    document.getElementById('sol'+i).style.position= 'relative';                   
                }
            }
            if (document.getElementById(motGagnant) !== null) {
                document.getElementById(motGagnant).innerHTML = motGagnant;
                document.getElementById(motGagnant).style.padding = '5px 10px';
                document.getElementById(motGagnant).style.backgroundColor = 'rgba(0, 255, 0, 0.4)';
            }
            document.getElementById('divFinalScore').innerHTML += '<H3>Ok, question suivante...</H3>';
            document.getElementById('scoreDiv').innerHTML = '<IMG src="load.gif">';

            // Etoiles filantes PERDU
            var totalStars = -1 * parseInt(document.getElementById('scoreHidden').value,10);
            //console.log('totalStars='+totalStars);
            for (var i = 0; i < totalStars; i++) {
                looseStar();
            }

            setTimeout(function() {
                goreloadForm(monFormulaire);
            }, 2500);
        }

        document.getElementById('score').value = parseInt(document.getElementById('score').value,10) + parseInt(document.getElementById('scoreHidden').value,10);



    }
}

