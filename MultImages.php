<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "fonctions.php";

fctAfficheEntete("Mult-Images");
fctAfficheBtnBack();


$content = "";
$motsCles = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Listes'])) {
    $filename = $_POST['Listes'];
    $filepath = "Listes/" . $filename . ".txt";
    echo "<B>Chargement de 50 mots de ".$filename."</B><BR>";
    $motsCles = $filename;
    if (file_exists($filepath)) {
        // Generate 100 random words
        $randomWords = fctRandMots($filepath, 50);
        $content = implode("\n", $randomWords);
    } else {
        $content = "File not found.";
    }    
} else {
    $content = "Mine Naico – Mexique
Red beach – Chine
Lac salé de Uyuni – Bolivie
Montagnes Tianzi – Chine
Champs de Tulipes – Pays-Bas
Tunnel of love – Ukraine
Tunnel de fleurs Wisteria – Japon
Zhangya Danxia – Chine
Mont Roraima – Venezuela";
}
?>

<!-- Bouton pour recommencer -->
<form method="post" id="formChargement" action="">
    Colle une liste de mot ci-dessous,
    <BR>je te renvois des URL des images chez Bing !
    <BR>
    <BR>...ou charge 50 mots à partir d'une de mes listes :
    <BR><select id="Listes" NAME="Listes">
<?php
$listesTxt = fctListesTxt("Listes/");
foreach ($listesTxt as $fichierTxt) {
    $nomFichier = basename($fichierTxt, ".txt");
    echo "<option value=\"$nomFichier\">$nomFichier</option>\n";
}
?>
    </select>
    <button type="submit" class="MonButton" name="charger">charger</button>
    
    </form>
    <form method="post" id="monFormulaire" action="">
<BR><BR>
    <textarea id="data" name="data" rows="20" cols="40">
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['data'])) {
    echo $_POST['data'];
} else {
    echo $content;
}
?>
    </textarea>
    <BR>
    Mots clés additionnels
    <INPUT ID="motsCles" NAME="motsCles" TYPE=TEXT VALUE='<?php 
    if (isset($_POST['motsCles']))
        echo $_POST['motsCles'];
    else
        echo $motsCles;
    ?>'>
    <BR>
    <TABLE WIDTH=100%>

    <TR><TD ALIGN=RIGHT>
    <label for="Pw">Width : </label>
    </TD><TD ALIGN=LEFT>
    <INPUT ID="Pw" NAME="Pw" TYPE=NUMBER MIN=1 MAX=5000 STEP=1 VALUE=400>
    </TD></TR>

    <TR><TD ALIGN=RIGHT>
    <label for="Ph">Height : </label>
    </TD><TD ALIGN=LEFT>
    <INPUT ID="Ph" NAME="Ph" TYPE=NUMBER MIN=1 MAX=5000 STEP=1 VALUE=400>
    </TD></TR>

    <TR><TD ALIGN=RIGHT>
    <label for="Prs">Resize :</label>
    </TD><TD ALIGN=LEFT>
    <select id="Prs" NAME="Prs">
    <option value="0">Original Size</option>
    <option value="1" SELECTED>Zoom No Distortion</option>
    <option value="2">Extend</option>  
    </select>
    </TD></TR>

    <TR><TD ALIGN=RIGHT>
    <label for="Pc">Crop :</label>
    </TD><TD ALIGN=LEFT>
    <select id="Pc" NAME="Pc">
    <option value="0" SELECTED>No Crop</option>
    <option value="1">Crop</option>
    </select>
    </TD></TR>

    <TR><TD ALIGN=RIGHT>
    <label for="Pp">Compléter avec du blanc :</label>
    </TD><TD ALIGN=LEFT>
    <select id="Pp" NAME="Pp">
    <option value="0" SELECTED>Non</option>
    <option value="1">Blanc dessus et dessous</option>
    <option value="2">Blanc dessus</option>
    <option value="3">Blanc dessous</option>
    </select>
    </TD><TR>

    <TR><TD ALIGN=RIGHT>
    <label for="Pmkt">market code :</label>
    </TD><TD ALIGN=LEFT>
    <select id="Pmkt" NAME="Pmkt">
    <option value="da-DK">da-DK</option>
    <option value="de-AT">de-AT</option>
    <option value="de-CH">de-CH</option>
    <option value="de-DE">de-DE</option>
    <option value="en-AU">en-AU</option>
    <option value="en-CA">en-CA</option>
    <option value="en-GB">en-GB</option>
    <option value="en-ID">en-ID</option>
    <option value="en-IN">en-IN</option>
    <option value="en-MY">en-MY</option>
    <option value="en-NZ">en-NZ</option>
    <option value="en-PH">en-PH</option>
    <option value="en-US">en-US</option>
    <option value="en-ZA">en-ZA</option>
    <option value="es-AR">es-AR</option>
    <option value="es-CL">es-CL</option>
    <option value="es-ES">es-ES</option>
    <option value="es-MX">es-MX</option>
    <option value="es-US">es-US</option>
    <option value="fi-FI">fi-FI</option>
    <option value="fr-BE">fr-BE</option>
    <option value="fr-CA">fr-CA</option>
    <option value="fr-CH">fr-CH</option>
    <option value="fr-FR" SELECTED>fr-FR</option>
    <option value="it-IT">it-IT</option>
    <option value="ja-JP">ja-JP</option>
    <option value="ko-KR">ko-KR</option>
    <option value="nl-BE">nl-BE</option>
    <option value="nl-NL">nl-NL</option>
    <option value="no-NO">no-NO</option>
    <option value="pl-PL">pl-PL</option>
    <option value="pt-BR">pt-BR</option>
    <option value="ru-RU">ru-RU</option>
    <option value="sv-SE">sv-SE</option>
    <option value="tr-TR">tr-TR</option>
    <option value="zh-CN">zh-CN</option>
    <option value="zh-HK">zh-HK</option>
    <option value="zh-TW">zh-TW</option>
    </select>
    </TD><TR>

    <TR><TD ALIGN=RIGHT>
    <label for="Pcc">country code :</label>
    </TD><TD ALIGN=LEFT>
    <select id="Pcc" NAME="Pcc">
    <option value="AR">Argentina</option>
    <option value="AU">Australia</option>
    <option value="AT">Austria</option>
    <option value="BE">Belgium</option>
    <option value="BR">Brazil</option>
    <option value="CA">Canada</option>
    <option value="CL">Chile</option>
    <option value="DK">Denmark</option>
    <option value="FI">Finland</option>
    <option value="FR" SELECTED>France</option>
    <option value="DE">Germany</option>
    <option value="HK">Hong Kong SAR</option>
    <option value="IN">India</option>
    <option value="ID">Indonesia</option>
    <option value="IT">Italy</option>
    <option value="JP">Japan</option>
    <option value="KR">Korea</option>
    <option value="MY">Malaysia</option>
    <option value="MX">Mexico</option>
    <option value="NL">Netherlands</option>
    <option value="NZ">New Zealand</option>
    <option value="NO">Norway</option>
    <option value="CN">People's Republic of China</option>
    <option value="PL">Poland</option>
    <option value="PT">Portugal</option>
    <option value="PH">Republic of the Philippines</option>
    <option value="RU">Russia</option>
    <option value="SA">Saudi Arabia</option>
    <option value="ZA">South Africa</option>
    <option value="ES">Spain</option>
    <option value="SE">Sweden</option>
    <option value="CH">Switzerland</option>
    <option value="TW">Taiwan</option>
    <option value="TR">Türkiye</option>
    <option value="GB">United Kingdom</option>
    <option value="US">United States</option>
    </select>
    </TD><TR>

    <TR><TD ALIGN=RIGHT>
    <label for="Psetlang">language code :</label>
    </TD><TD ALIGN=LEFT>
    <select id="Psetlang" NAME="Psetlang">
    <option value="ar">Arabic</option>
    <option value="eu">Basque</option>
    <option value="bn">Bengali</option>
    <option value="bg">Bulgarian</option>
    <option value="ca">Catalan</option>
    <option value="zh-hans">Chinese (Simplified)</option>
    <option value="zh-hant">Chinese (Traditional)</option>
    <option value="hr">Croatian</option>
    <option value="cs">Czech</option>
    <option value="da">Danish</option>
    <option value="nl">Dutch</option>
    <option value="en">English</option>
    <option value="en-gb">English-United Kingdom</option>
    <option value="et">Estonian</option>
    <option value="fi">Finnish</option>
    <option value="fr" SELECTED>French</option>
    <option value="gl">Galician</option>
    <option value="de">German</option>
    <option value="gu">Gujarati</option>
    <option value="he">Hebrew</option>
    <option value="hi">Hindi</option>
    <option value="hu">Hungarian</option>
    <option value="is">Icelandic</option>
    <option value="it">Italian</option>
    <option value="jp">Japanese</option>
    <option value="kn">Kannada</option>
    <option value="ko">Korean</option>
    <option value="lv">Latvian</option>
    <option value="lt">Lithuanian</option>
    <option value="ms">Malay</option>
    <option value="ml">Malayalam</option>
    <option value="mr">Marathi</option>
    <option value="nb">Norwegian (Bokmål)</option>
    <option value="pl">Polish</option>
    <option value="pt-br">Portuguese (Brazil)</option>
    <option value="pt-pt">Portuguese (Portugal)</option>
    <option value="pa">Punjabi</option>
    <option value="ro">Romanian</option>
    <option value="ru">Russian</option>
    <option value="sr">Serbian (Cyrylic)</option>
    <option value="sk">Slovak</option>
    <option value="sl">Slovenian</option>
    <option value="es">Spanish</option>
    <option value="sv">Swedish</option>
    <option value="ta">Tamil</option>
    <option value="te">Telugu</option>
    <option value="th">Thai</option>
    <option value="tr">Turkish</option>
    <option value="uk">Ukrainian</option>
    <option value="vi">Vietnamese</option>
    </select>
    </TD><TR>

    <TR><TD ALIGN=RIGHT>
    <label for="PsafeSearch">safeSearch ? :</label>
    </TD><TD ALIGN=LEFT>
    <select id="PsafeSearch" NAME="PsafeSearch">
    <option value="Off">Returns content with adult images</option>
    <option value="Moderate" SELECTED>Moderate</option>
    <option value="Strict">Does not return adult images</option>
    </select>
    </TD><TR>

    </TABLE>




    <BR>
    <button type="submit" class="MonButton" name="recommencer">Voir les images !</button>
</form>
<HR>
<?php




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du textarea
    $data = !empty($_POST['data']) ? $_POST['data'] : $content;

    // Vérification si des données ont été saisies
    if ($data) {
        // Diviser les données en lignes
        $lines = explode("\n", $data);
        $index = 0;
        // Parcourir chaque ligne
        foreach ($lines as $line) {
            // Supprimer les espaces inutiles
            $line = trim($line);
            if ($line!="") {
                $index++;
                // Afficher ou traiter chaque ligne
                
                echo "   <BIG>". $index . " : " . htmlspecialchars($line) ."</BIG>      ";
                fctAfficheGoogle($line);
                $line = str_replace("&"," ",$line);
                $line = str_replace(" ","%20",$line);
                //Ajout des mots clés
                if (isset($_POST['motsCles'])) {
                    $line = $line."%20".$_POST['motsCles'];
                } else {
                    $line = $line."%20".$motsCles;
                }
                

                $url = "https://th.bing.com/th?";
                $urlZoom = "https://th.bing.com/th?";
                // Filtres
                if (isset($_POST['Pw'])) {
                    $url .= "w=" . $_POST['Pw'] . "&";
                    $urlZoom .= "w=1920&";
                }
                if (isset($_POST['Ph'])) {
                    $url .= "h=" . $_POST['Ph'] . "&";
                    $urlZoom .= "h=1080&";
                }
                if (isset($_POST['Prs'])) {
                    $url .= "rs=" . $_POST['Prs'] . "&";
                    $urlZoom .= "rs=" . $_POST['Prs'] . "&";
                }
                if (isset($_POST['Pc'])) {
                    $url .= "c=" . $_POST['Pc'] . "&";
                    $urlZoom .= "c=" . $_POST['Pc'] . "&";
                }
                if (isset($_POST['Pp'])) {
                    $url .= "p=" . $_POST['Pp'] . "&";
                    $urlZoom .= "p=" . $_POST['Pp'] . "&";
                }
                if (isset($_POST['Pmkt'])) {
                    $url .= "mkt=" . $_POST['Pmkt'] . "&";
                    $urlZoom .= "mkt=" . $_POST['Pmkt'] . "&";
                }
                if (isset($_POST['Pcc'])) {
                    $url .= "cc=" . $_POST['Pcc'] . "&";
                    $urlZoom .= "cc=" . $_POST['Pcc'] . "&";
                }
                if (isset($_POST['Psetlang'])) {
                    $url .= "setlang=" . $_POST['Psetlang'] . "&";
                    $urlZoom .= "setlang=" . $_POST['Psetlang'] . "&";
                }
                if (isset($_POST['PsafeSearch'])) {
                    $url .= "safeSearch=" . $_POST['PsafeSearch'] . "&";
                    $urlZoom .= "safeSearch=" . $_POST['PsafeSearch'] . "&";
                }
                                
                $url .= "q=" . $line;
                $urlZoom .= "q=" . $line;
                //echo $url;  // Or process the $url further as needed
                echo "<BR>";
                echo "<A HREF='".$urlZoom."' target=_blank>";
                echo "<IMG title='$line' src='".$url."'>";
                echo "</A>";
                echo "<HR>";
            }
        }
    } else {
        echo "Veuillez entrer des données dans le champ textarea.";
    }

}







fctAffichePiedPage();
?>