<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<?php 
include "../controller/connection.php";
$id_angsur = $_GET['id_angsur'];
$id_kontrak = $_GET['id_kontrak'];
$koneksi = new database();
$data = $koneksi->tampil_pembayaran($id_angsur, $id_kontrak);
$datas = json_decode($data, true);
session_start();
if(empty($_SESSION['nama']) && empty($_SESSION['status'])){
      header("location: ../index.php?login=gagal");
}else{
?>
<div class="container">
    <div class="mb-3 row">
        <h1 class="invoice">Invoice Pembayaran</h1>
        <form class="row g-3" action="../controller/controller.php?action=addPembayaran" method="post" enctype="multipart/form-data">
            <?php
                
                foreach($datas as $item){
                    $tanggalPembayaran = date("Y-m-d");
                    $rangeAngsuran = number_format($item["angsuran_per_bulan"]);
                    $rangePembayaran = number_format($item["total_pembayaran"]);
                    $rangeDenda = number_format($item["total_denda"]);
                    echo "<input type='text' hidden class='form-control-plaintext' name='kode_angs'  value='$item[kode_angsuran]'>".
                        "<input type='text' hidden class='form-control-plaintext' name='tanggal_pembayaran'  value='$tanggalPembayaran'>";
                    echo "<label for='' class='col-sm-2 col-form-label'>Kode Kontarak:</label>".
                        "<div class='col-sm-10'>".
                        "<input type='text' readonly class='form-control-plaintext'  name='kode' value='$item[kode_kontrak]'>".
                        "</div>";
                    echo "<label for='' class='col-sm-2 col-form-label'>Client Name:</label>".
                        "<div class='col-sm-4'>".
                        "<input type='text' readonly class='form-control-plaintext' name='nama'  value=$item[name_client]>".
                        "</div>";
                    echo "<label for='' class='col-sm-2 col-form-label'>Angsuran Ke</label>".
                        "<div class='col-sm-4'>".
                        "<input type='text' readonly class='form-control-plaintext' name='angsur_ke' value=$item[angsuran_ke]>".
                        "</div>";
                    echo "<label for='' class='col-sm-2 col-form-label'>Nama Barang:</label>".
                        "<div class='col-sm-4'>
                            <input type='text' readonly class='form-control-plaintext' name='nama_barang' value='$item[nama_barang]'>
                        </div>";
                    echo "<label for='' class='col-sm-2 col-form-label'>Jenis:</label>".
                        "<div class='col-sm-4'>
                            <input type='text' readonly class='form-control-plaintext' name='jns_barang' value='$item[jenis_barang]'>
                        </div>";
                    echo "<label for='' class='col-sm-2 col-form-label'>Angusran Per-bulan:</label>".
                        "<div class='col-sm-4'>
                            <input type='text' readonly class='form-control-plaintext' name='angsur_per_bulan' value='Rp.$rangeAngsuran '>
                        </div>";
                    echo "<label for='' class='col-sm-2 col-form-label'>Denda Per-hari:</label>".
                        "<div class='col-sm-4'>
                            <input type='text' readonly name='denda' class='form-control-plaintext' name='denda_per_hari'  value='0.1%'>
                        </div>";
                    echo "<label for='' class='col-sm-2 col-form-label'>Total Pembayaran</label>".
                        "<div class='col-sm-4'>
                            <input type='text' readonly name='total_pembayaran' class='form-control-plaintext ttl-1'  value='Rp.$rangePembayaran'>
                        </div>";
                    echo "<label for='' class='col-sm-2 col-form-label'>Total Denda</label>".
                        "<div class='col-sm-4'>
                            <input type='text' readonly name='total_denda' class='form-control-plaintext ttl-2'  value='Rp.$rangeDenda'>
                        </div>";        
                } 
            ?>
            <div class="container text-center">
                <h4>Via Transfer</h4>
                <div class="row" style="background-color:#FF7F11; color: white;">
                    <div class="col">
                     BCA : 37212911 
                    </div>
                    <div class="col">
                     DANA : 08996821263
                    </div>
                    <div class="col">
                     Indomaret : B41271F121H012
                    </div>
                    <div class="col">
                     Alfamart :  A1213Y21I12P91
                    </div>
                </div>
            </div>
            <div class="col-12">
                <label for="" class="form-label">Masukan Bukti Pembayaran</label>
                <input class="form-control form-control-lg" name='bukti_pembayaran' accept=".jpg, .jpeg, .png" type="file">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Pembayaran</button>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php } ?>