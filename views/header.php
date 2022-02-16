<?php


function my_error_logging_principles(){
    ## Voit asettaa nämä myös php.ini -tiedostossa, mutta koska sandboksissa 
    ## hakemistoon ei ole pääsyä, tässä ne asetetaan ohjelmallisesti.
    ini_set('log_error', '1');
    ## Älä talleta virhelokia samaan hakemistoon sovelluksen kanssa.
    ## sovellus sijaitsee tässä /var/www -hakemiostossa, joka on 
    ## web-palvelimen hallinnassa ja saattaa päätyä julkaistavaksi 
    ## webissä. Koska kirjoitusoikeutta ei ole muihin hakemistoihin, 
    ## logitus tehdään VIRHEELLISESTI web-hakemistoon, jolloin kuka vain voi
    ## ladata sen url:llä https://9kaec.ciroue.com/libraryerrors.log
    ini_set('error_log', 'libraryapplicationerrors.log');  
}

 function getNavigation(){
     return "<div>".
        "<a style=\"margin: 19px;\" href=\"users.php\" class=\"btn btn-secondary\">".
        "Käyttäjät</a>".
        "</div>";
 }

    /**
       Geneerinen virheviesti Bootstrap-tyyleillä muokattuna. 
       Tiedosto otetaan mukaan sivuskipteihin include-lauseella
       siksi käytetään snake-case nimeämistä. Sama sopisi yllä 
       olevaan getNavigation-funktioon.

       Huomaa, että tämä funktio ei palauta mitään, vaan tulostaa 
       suoraan HTML:ää. Sitä pitää siis kutsua siinä kohdassa, 
       jossa tulostus halutaan. Myös getNavigation-funktion voisi 
       toteuttaa samoin.
    **/

 function print_status_message($status_text, $type="primary"){
     //Jos teksti on tyhjä, älä tulosta mitään.
     if ($status_text==null || $status_text==""){
         return;
     }
     //Oletuksena neutraali viesti.
     $bt_class="alert alert-primary";
     if ($type=="error"){
         $bt_class="alert alert-danger";
     }
     else if ($type=="ok"){
         $bt_class="alert alert-success";
     }
     echo '<div class="'.$bt_class.'" style="margin-top: 5vw">'.$status_text.'</div>';
 }

?>