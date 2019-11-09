<?php
    require '../baza.class.php';
    require '../sesija.class.php';
    
    // Odjava
    if(isset($_GET['odjava'])){
        
        Sesija::dajKorisnika();
        $dnevnik = new Dnevnik();
        $dnevnik->zapisi("Odjava sa sustava.", $_SESSION['id']);
        
        Sesija::obrisiSesiju();
        header("Location: ../obrasci/prijava.php");
    }
    if(isset($_GET['rezultat'])){
        echo $_GET['rezultat'];
    }
    
    // Provjera korisnika
    Sesija::dajKorisnika();
    //var_dump($_SESSION);
    
    if(!isset($_SESSION['korisnik'])){
        header("Location: ../obrasci/prijava.php");
    }
    else if(intval(($_SESSION['uloga'])) > 2){
        header("Location: ../index.php");
    }
?>
﻿<!DOCTYPE html>
<html lang="hr" >
    <head>
        <title> Pregled kupnja zahtjeva </title>
        <meta charset="utf-8">
        <meta name="author" content="Meikl Marić">
        <meta name="keywords" content="Obrazac, dokument, get">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <link rel="stylesheet" type="text/css" href="../css/mmaric_1100.css" media="(max-width: 1100px)">
        <link rel="stylesheet" type="text/css" href="../css/mmaric_960.css" media="(max-width: 960px)">
        <link rel="stylesheet" type="text/css" href="../css/mmaric_780.css" media="(max-width: 780px)">
        <link rel="stylesheet" type="text/css" href="../css/mmaric_480.css" media="(max-width: 480px)">
        
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="../javascript/mmaric.js"></script>-->    
    </head>
    <body>
    <header id="headerObrazac">
	   <div class = "container">
            <h2> <span class = "zutiTekst"> Pregled kupnja zahtjeva  </span></h2>
            <nav>
                <ul>
                    <li><a href="../index.php"> Početna </a></li>
                    <?php 
                        if(isset($_SESSION['uloga'])){
                            // 1 - administrator
                            if($_SESSION['uloga'] == '1'){
                                echo '<div class="dropdown">
                                            <button class="dropbtn">ADMIN &#9661;</button>
                                            <div class="dropdown-content">
                                              <a href="KategorijaUnos.php"> Unesi kategoriju </a>
                                              <a href="KategorijaPopis.php"> Popis kategorija </a>
                                              <a href="LicencaUnos.php"> Unesi licencu </a>
                                              <a href="LicencaPopis.php"> Popis licenci </a>
                                              <a href="PopisBlokiranih.php"> Popis blokiranih </a>
                                              <a href="PopisKorisnika.php"> Popis korisnika </a>
                                              <a href="LicencaKupnjaZahtjevi.php"> Pregledaj zahtjeve za kupnju</a>
                                              <a href="Dnevnik.php"> Dnevnik </a>
                                              <a href="Konfiguracija.php"> Konfiguracija </a>
                                              <a href="Statistika.php"> Statistika </a>
                                            </div>
                                       </div>';
                            }
                            if($_SESSION['uloga'] == '2'){
                                echo '<div class="dropdown">
                                            <button class="dropbtn">MODERATOR &#9661;</button>
                                            <div class="dropdown-content">
                                              <a href="MojiKupnjaZahtjevi.php"> Moji kupnja zahtjevi </a>
                                              <a href="LicencaKoristenjeZahtjevi.php"> Pregledaj zahtjeve za korištenje</a>
                                              <a href="MojiKorisniciLicenci.php"> Moji korisnici</a>
                                            </div>
                                       </div>';
                            }
                            if($_SESSION['uloga'] == '3'){
                                echo '<div class="dropdown">
                                            <button class="dropbtn">KORISNIK &#9661;</button>
                                            <div class="dropdown-content">
                                              <a href="MojiKoristenjeZahtjevi.php"> Moji zahtjevi za korištenje</a>
                                            </div>
                                       </div>';
                            }
                        }
                        if(isset($_SESSION['korisnik'])){ 
                            echo "<li><a href=" . "?odjava" . "> Odjava </a></li>";
                        } 
                    ?>
                </ul>
            </nav>
        </div>
    </header>
    <section style="text-align:center">
            <div class="odjeljak">
                <h3> Moji zahtjevi za kupnju </h3>
                <?php 
                    if(isset($_GET['rezultat'])){
                            if($_GET['rezultat'] == '1'){
                                echo '<p style="color:green"> Podaci su uspješno uneseni u bazu podataka.</p>';
                                header( "refresh:4;url=MojiKupnjaZahtjevi.php" );
                            }
                        }
                ?>
                
                <table style="margin: auto; max-width: 70%; text-align: center;" border="0">
                 <tr><th>ID</th><th>Korisnik_id</th><th>Licenca_id</th><th>Status_id</th><th>Količina</th><th>Iznos</th><th>Datum od</th>
                 <th>Datum do</th><th>Datum vrijeme promjene statusa</th></tr>
                <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    $upit = "select * from kupnja where korisnik_id = {$_SESSION['id']}";
                    $rezultat = $veza->selectDB($upit);

                    $nizZapisa = [];
                    while($red = mysqli_fetch_array($rezultat)){
                        $element = [];
                        $element['id'] = $red['id'];
                        $element['korisnik_id'] = $red['korisnik_id'];
                        $element['licenca_id'] = $red['licenca_id'];
                        $element['status_id'] = $red['status_id'];
                        $element['kolicina'] = $red['kolicina'];
                        $element['iznos'] = $red['iznos'];
                        $element['datum_od'] = $red['datum_od'];
                        $element['datum_do'] = $red['datum_do'];
                        $element['datum_vrijeme_promjene_statusa'] = $red['datum_vrijeme_promjene_statusa'];
                        $nizZapisa[] = $red;
                    }
                    $veza->zatvoriDB();
                    foreach ($nizZapisa as $value) {
                        echo '<tr>';
                        echo  "<td> {$value['id']} </td>"
                            . "<td> {$value['korisnik_id']} </td>"
                            . "<td> {$value['licenca_id']} </td>"
                            . "<td> {$value['status_id']} </td>"
                            . "<td> {$value['kolicina']} </td>"
                            . "<td> {$value['iznos']} </td>"
                            . "<td style='padding-left: 20px; padding-right: 20px'> {$value['datum_od']} </td>"
                            . "<td style='padding-left: 20px; padding-right: 20px'> {$value['datum_do']} </td>"
                            . "<td> {$value['datum_vrijeme_promjene_statusa']} </td>";  
                        echo '</tr>';
                    }                
                ?>
                </table>
            </div>
    </section>
    </body>
</html>