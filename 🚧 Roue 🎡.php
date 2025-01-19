<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roue SVG dynamique</title>
    <style>
        svg {
            margin: 20px auto;
            display: block;
        }
    </style>
</head>
<body>
    <svg id="svg-wheel" width="400" height="400"></svg>

    <script>
// Sélection de l'élément SVG
const svg = document.getElementById('svg-wheel');

// Paramètres de la roue
const centerX = 200; // Position horizontale du centre de la roue
const centerY = 200; // Position verticale du centre de la roue
const radius = 150; // Rayon de la roue
const numSections = 8; // Nombre de quartiers
const colors = ['#FF5733', '#FFC300', '#DAF7A6', '#FF5733', '#FFC300', '#DAF7A6', '#FF5733', '#FFC300']; // Couleurs des quartiers

// Création des quartiers de la roue
const angle = (2 * Math.PI) / numSections;
const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
for (let i = 0; i < numSections; i++) {
    const startAngle = i * angle;
    const endAngle = (i + 1) * angle;

    // Création de l'arc
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('d', describeArc(centerX, centerY, radius, startAngle, endAngle));
    path.setAttribute('fill', colors[i]);
    group.appendChild(path);
}

// Ajout du groupe à l'élément SVG
svg.appendChild(group);

// Fonction pour décrire un arc de cercle
function describeArc(x, y, radius, startAngle, endAngle) {
    const start = polarToCartesian(x, y, radius, endAngle);
    const end = polarToCartesian(x, y, radius, startAngle);

    const largeArcFlag = endAngle - startAngle <= Math.PI ? '0' : '1';

    const d = [
        'M', start.x, start.y,
        'A', radius, radius, 0, largeArcFlag, 0, end.x, end.y,
        'L', x, y,
        'L', start.x, start.y
    ].join(' ');

    return d;
}

// Fonction pour convertir les coordonnées polaires en coordonnées cartésiennes
function polarToCartesian(centerX, centerY, radius, angleInRadians) {
    const x = centerX + (radius * Math.cos(angleInRadians));
    const y = centerY + (radius * Math.sin(angleInRadians));
    return { x, y };
}


// Paramètres de l'animation
const minNumTurns = 1; // Nombre minimum de tours
const maxNumTurns = 4; // Nombre maximum de tours pour la dernière rotation aléatoire
const minDuration = 15; // Durée minimum de la dernière rotation aléatoire (en secondes)
const maxDuration = 30; // Durée maximum de la dernière rotation aléatoire (en secondes)

// Durée totale de l'animation (3 tours minimum + temps supplémentaire)
const totalDuration = (minNumTurns * 5) + minDuration; // Durée des 3 tours minimum (5 secondes par tour)

// Création de l'animation de rotation avec une fonction de temporisation personnalisée
const rotationAnimation = document.createElementNS('http://www.w3.org/2000/svg', 'animateTransform');
rotationAnimation.setAttribute('attributeName', 'transform');
rotationAnimation.setAttribute('attributeType', 'XML');
rotationAnimation.setAttribute('type', 'rotate');
rotationAnimation.setAttribute('from', '0 ' + centerX + ' ' + centerY);
rotationAnimation.setAttribute('to', (maxNumTurns * 360) + ' ' + centerX + ' ' + centerY); // Rotation maximale (pour garantir un minimum de 3 tours)
rotationAnimation.setAttribute('dur', totalDuration + 's'); // Durée totale de l'animation
rotationAnimation.setAttribute('repeatCount', '1'); // Ne répète pas l'animation
rotationAnimation.setAttribute('keyTimes', '0;1'); // Temps de progression de la rotation
rotationAnimation.setAttribute('keySplines', '0.42,0,0.58,1'); // Fonction de temporisation personnalisée
rotationAnimation.setAttribute('fill', 'freeze'); // Figé à la fin de l'animation
group.appendChild(rotationAnimation);

// Ecoute de la fin de l'animation pour lancer la dernière rotation aléatoire
rotationAnimation.addEventListener('endEvent', () => {
    const randomNumTurns = Math.floor(Math.random() * (maxNumTurns - minNumTurns + 1)) + minNumTurns; // Nombre de tours aléatoire entre minNumTurns et maxNumTurns
    const randomDuration = Math.floor(Math.random() * (maxDuration - minDuration + 1)) + minDuration; // Durée aléatoire entre minDuration et maxDuration
    const totalRotationDegrees = randomNumTurns * 360; // Nombre total de degrés à tourner

    // Création de l'animation de rotation avec une fonction de temporisation personnalisée pour la dernière rotation aléatoire
    const rotationAnimation2 = document.createElementNS('http://www.w3.org/2000/svg', 'animateTransform');
    rotationAnimation2.setAttribute('attributeName', 'transform');
    rotationAnimation2.setAttribute('attributeType', 'XML');
    rotationAnimation2.setAttribute('type', 'rotate');
    rotationAnimation2.setAttribute('from', (maxNumTurns * 360) + ' ' + centerX + ' ' + centerY); // Départ à la fin de la première animation
    rotationAnimation2.setAttribute('to', ((maxNumTurns * 360) + totalRotationDegrees) + ' ' + centerX + ' ' + centerY); // Rotation aléatoire
    rotationAnimation2.setAttribute('dur', randomDuration + 's'); // Durée de la dernière rotation aléatoire
    rotationAnimation2.setAttribute('repeatCount', '1'); // Ne répète pas l'animation
    rotationAnimation2.setAttribute('keyTimes', '0;1'); // Temps de progression de la rotation
    rotationAnimation2.setAttribute('keySplines', '0.42,0,0.58,1'); // Fonction de temporisation personnalisée
    rotationAnimation2.setAttribute('fill', 'freeze'); // Figé à la fin de l'animation
    group.appendChild(rotationAnimation2);

    // Ecoute de la fin de la dernière animation pour afficher le résultat final
    rotationAnimation2.addEventListener('endEvent', () => {
        const finalSectorIndex = Math.floor(Math.random() * numSections); // Sélection aléatoire du secteur final
        console.log("Le secteur sélectionné est le numéro : " + finalSectorIndex);
    });
});



    </script>
</body>
</html>