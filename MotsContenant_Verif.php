<?php
session_start();
$resultats = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serieLettres = mb_strtoupper(trim($_POST['serieLettres']));
    $resultats = '';

    if (preg_match('/^[A-ZÀ-ÖØ-Ý]{3,}$/u', $serieLettres)) {
        $resultats .= "<HR>";
        $cheminFichierMots = 'liste_francais.txt';
        if (!isset($_SESSION['liste_francais'])) {
            $mots = file($cheminFichierMots, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $_SESSION['liste_francais'] = $mots;
        }
        else
            $mots = $_SESSION['liste_francais'];

        foreach ($mots as &$mot) {
            $mot = mb_convert_encoding($mot, 'UTF-8', 'ISO-8859-1');
        }

        $motsTrouves = array_filter($mots, function ($mot) use ($serieLettres) {
            return mb_stripos($mot, $serieLettres) !== false;
        });

        if (empty($motsTrouves)) {
            $resultats .= '<p>Aucun mot trouvé contenant ' . $serieLettres . ' parmi ' . number_format(count($mots), 0, ',', ' ') . ' mots</p>';
        } else {
            $resultats .= count($motsTrouves) . ' mots trouvés parmi ' . number_format(count($mots), 0, ',', ' ') . ' contenant ' . $serieLettres;
            $resultats .= '<ul>';
            foreach ($motsTrouves as $mot) {
                $resultats .= "<li>" . str_replace(mb_strtolower($serieLettres), '<B>' . mb_strtolower($serieLettres) . '</B>', $mot) . "</li>";
            }
            $resultats .= '</ul>';
        }

        $resultats .= "<HR>";
        $cheminFichierMots = 'DEM-1_1.txt';
        if (!isset($_SESSION['DEM-1_1'])) {
            $mots = file($cheminFichierMots, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $_SESSION['DEM-1_1'] = $mots;
        }
        else
            $mots = $_SESSION['DEM-1_1'];

        $motsTrouves = array_filter($mots, function ($mot) use ($serieLettres) {
            return mb_stripos($mot, $serieLettres) !== false;
        });

        if (empty($motsTrouves)) {
            $resultats .= '<p>Aucun mot trouvé contenant ' . $serieLettres . ' parmi ' . number_format(count($mots), 0, ',', ' ') . ' mots</p>';
        } else {
            $resultats .= count($motsTrouves) . ' mots trouvés parmi ' . number_format(count($mots), 0, ',', ' ') . ' contenant ' . $serieLettres;
            $resultats .= '<ul>';
            foreach ($motsTrouves as $mot) {
                $resultats .= "<li>" . str_replace(mb_strtolower($serieLettres), '<B>' . mb_strtolower($serieLettres) . '</B>', $mot) . "</li>";
            }
            $resultats .= '</ul>';
        }
    } else {
        $resultats .= '<p>La série de lettres spécifiée n\'est pas valide.</p>';
    }
}

echo $resultats;
?>
