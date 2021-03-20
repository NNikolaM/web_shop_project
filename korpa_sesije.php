<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="stil.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@300&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;700&family=Hanalei+Fill&display=swap" rel="stylesheet">
</head>
<body>
<?php
    // session_start();
    include("funkcije/podaci/config.php");
    include("sesija.php");
    $sesija=$_GET['sesija']??'dodaj';
    if(!isset($_SESSION['provera'])){
        echo ("<script language='JavaScript'>
        window.alert('Niste prijavljeni.');
        window.location.href='registracija.php';
        </script>");
    }else{
        if($sesija=='dodaj'){
            
            echo "<p class='potvrda-p'>PROIZVOD JE DODAT U KORPU</p>";
            $id=$_GET['id_proizvoda'];
            $naziv=$_GET['naziv'];
            $cena=$_GET['cena'];
            $broj=$_GET['broj'];
            $kolicina=$_GET['kolicina'];
            
            function dodaj_proizvod_u_korpu($id, $naziv, $cena, $broj, $kolicina){
                array_push($_SESSION['stavke_korpe'], 
                    ['id'=>$id, 'naziv'=>$naziv, 'cena'=>$cena, 'broj'=>$broj, 'kolicina'=>$kolicina, 'ukupno'=>$cena*$kolicina]);
            }

            function dodaj_kolicinu($id, $broj, $kolicina, $cena){
                for($i=0; $i<count($_SESSION['stavke_korpe']); $i++){
                    if($_SESSION['stavke_korpe'][$i]['id'] === $id
                    && $_SESSION['stavke_korpe'][$i]['broj'] === $broj){
                        $_SESSION['stavke_korpe'][$i]['kolicina'] += $kolicina; 
                        $_SESSION['stavke_korpe'][$i]['ukupno'] += $kolicina*$cena; 
                    }
                }
            }

            function ispitaj($id, $broj){
                for($i=0; $i<count($_SESSION['stavke_korpe']); $i++) {
                    if($_SESSION['stavke_korpe'][$i]['id'] === $id
                    && $_SESSION['stavke_korpe'][$i]['broj'] === $broj){
                        return false;
                    }
                }
                return true;
            }
            if(!isset($_SESSION['stavke_korpe'])){
                $_SESSION['stavke_korpe'] = [];
            }
            $ispitaj=ispitaj($id, $broj);
            if($ispitaj===false){
                dodaj_kolicinu($id,$broj,$kolicina,$cena);
            }else{
                dodaj_proizvod_u_korpu($id, $naziv, $cena, $broj, $kolicina); 
            }

        }else if($sesija=='prikazi'){
            require_once("prikaz.php");
        }else if($sesija=='brisi'){
            function obrisi_proizvod($id){
                for($i=0; $i<count($_SESSION['stavke_korpe']); $i++)
                    if($_SESSION['stavke_korpe'][$i]['id'] === $id){
                        array_splice($_SESSION['stavke_korpe'], $i, 1);
                        return;
                    }
            }
            $id=$_GET['id'];
            obrisi_proizvod($id);
            header("location:korpa_sesije.php?sesija=prikazi");

        }else if($sesija=='potvrdi_porudzbinu'){
            for($i=0; $i<count($_SESSION['stavke_korpe']); $i++){
                $id_proizvoda = $_SESSION['stavke_korpe'][$i]['id'];
                $broj = $_SESSION['stavke_korpe'][$i]['broj'];
                $kolicina = $_SESSION['stavke_korpe'][$i]['kolicina'];
                $dodaj_u_bazu = $conn->query("INSERT INTO `stavke_korpe`(`id_korpe`, `id_proizvoda`, `broj`, `kolicina`)
                VALUES ('$login_sesija_id', '$id_proizvoda', '$broj', '$kolicina');");
                if($dodaj_u_bazu === false){
                    die('GRESKA: ' . $conn->connect_error);
                }
                $smanji_kolicinu = $conn->query("UPDATE `brojevi` SET `kolicina` = `kolicina` - $kolicina
                    WHERE `id_proizvoda` = $id_proizvoda AND `broj` = $broj;");
                if($smanji_kolicinu === false){
                    die('GRESKA kod smanjivanja kolicine');
                }
            }
            $_SESSION['stavke_korpe']=[];
            header("location:korpa_sesije.php?sesija=prikazi");

        }else if($sesija='isprazni'){
            $_SESSION['stavke_korpe']=[];
            require_once("prikaz.php");

        }else{
            echo "GREŠKA";
        }
    }
?>
</body>
</html>