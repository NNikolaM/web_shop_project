<?php
    include('funkcije/podaci/config.php');
    session_start();

    $provera_korisnika_email = $_SESSION['korisnik'];
    $provera_korisnika_id = $_SESSION['id_korisnika'];

    $email = $conn->query("SELECT `email` FROM `korisnik` WHERE `email` = '$provera_korisnika_email' ");
    $id_korisnika = $conn->query("SELECT `id_korisnika` FROM `korisnik` WHERE `email` = '$provera_korisnika_email' ");

    $red_email = mysqli_fetch_array($email,MYSQLI_ASSOC);
    $red_id = mysqli_fetch_array($id_korisnika,MYSQLI_ASSOC);
    $login_sesija_email = $red_email['email'];
    $login_sesija_id = $red_id['id_korisnika'];

    if(!isset($_SESSION['korisnik'])){
        $login_sesija_email = "";
        $login_sesija_id = "";
        header("location:index.php");
        die();
    }
?>