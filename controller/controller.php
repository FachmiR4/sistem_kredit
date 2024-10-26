<?php 
session_start();
include "connection.php";
$koneksi = new database();
$action = $_GET['action'];

if($action == 'addData'){
    if(empty($_POST['nama']) || empty($_POST['dp']) || empty($_POST['jangka_wkt']) || empty($_POST['nama_barang']) || empty($_POST['jns_barang']) || empty($_POST['bunga']) || empty($_POST['otr'])){
        header("location: ../index.php?sts=gagal");
        return false;
    }
    $exec = $koneksi->addData($_POST['nama'], $_POST['dp'], $_POST['jangka_wkt'], 
                                $_POST['nama_barang'], $_POST['jns_barang'], $_POST['bunga'], $_POST['otr'], $_POST['username'], $_POST['pass']);
    if($exec == 'sukses'){
        header("location: ../index.php?sts=sukses");
    }else{
        header("location: ../index.php?sts=gagal");
    }
}elseif($action == 'addPembayaran'){
    $total_denda = floatval(str_replace([',', 'Rp.'], '' ,$_POST['total_denda']));
    $total_pembayaran = floatval(str_replace([',', 'Rp.'], '' , $_POST['total_pembayaran']));

    $nameFile = $_FILES['bukti_pembayaran']['name'];
    $sizeFile = $_FILES['bukti_pembayaran']['size'];
    $tmpName = $_FILES['bukti_pembayaran']['tmp_name'];
    
    $validImageExtension = ['jpg', 'png', 'jpeg'];
    $imageExtension = explode('.', $nameFile);
    $imageExtension= strtolower(end($imageExtension));
    if(!in_array($imageExtension, $validImageExtension)){
        echo "<script>alert('Invalid Imange')</script>";
    }elseif($sizeFile > 100000){
        echo "<script>alert('size image is too large')</script>";
    }else{
        $newNameFile = uniqid();
        $newNameFile .= '.'.$imageExtension;
        move_uploaded_file( $tmpName, '../img/upload/'.$newNameFile );
        $exec = $koneksi->addDataPembayaran($_POST['kode_angs'], $total_pembayaran, $_POST['tanggal_pembayaran'],$_POST['denda'], $total_denda, $newNameFile);
        $exec2 = $koneksi->editDataPembayaran($_POST['kode_angs']);
        $id = $_SESSION['kode_kontrak'];
        if($exec == 'sukses' && $exec2 == 'sukses'){
            header("location: ../page/info.php?sts=sukses&&id=$id");
        }else{
            header("location: ../page/info.php?sts=gagal&&id=$id");
        }
    } 
}elseif($action = 'login'){
    $exec = $koneksi->login($_POST['username'], $_POST['password'], $_POST['kode_kontrak']);
    $id = $_SESSION['kode_kontrak'];
    if($exec == 'sukses'){
        header("location: ../page/info.php?id=$id");
    }else{
        header("location: ../index.php?login=gagal");
    }
}

?>