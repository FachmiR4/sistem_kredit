<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Info Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<?php 
    include ("../controller/connection.php");

    $koneksi = new database();
    $kode_kontrak = $_GET['id'];
    $data = $koneksi->tampil_data_angsuranById($kode_kontrak);
    $countItem = count($data);
    $totalSdhBayar = 0;
    foreach($data as $item){
      $status = $item['status'];
      if($status == 'sudah bayar'){
        $totalSdhBayar++;
      }
    }
    if($totalSdhBayar == $countItem){
      $data2 = $koneksi->editDataKontrak($kode_kontrak);
    }
    session_start();
    if(empty($_SESSION['nama']) && empty($_SESSION['status'])){
      header("location: ../index.php?login=gagal");
    }else{
?>

<body>
<div class="container">
    <h1 style="text-align: center;">Form Pembayaran Angsuran </h1>
    <?php 
      if(isset($_GET['sts'])){
        $sts = $_GET['sts'];
        if($sts == 'sukses'){
          echo "<div class='alert alert-success' role='alert'>
                      Pembayaran Berhasil
                    </div>";
        }else{
          echo "<div class='alert alert-danger' role='alert'>
                      Pembayaran Gagal
                    </div>";
        }
      }
    ?>
      <a href="card/logout.php" class="btn-back"><svg xmlns="http://www.w3.org/2000/svg" width="52" height="45" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
        <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1"/>
      </svg></a>
      <table class="table table-bordered table-striped">
        <thead>
            <tr>
            <th scope="col">Kode Kontrak</th>
            <th scope="col">Kode Angsuran</th>
            <th scope="col">Angsuran ke</th>
            <th scope="col">Angsuran Perbulan</th>
            <th scope="col">Status</th>
            <th scope="col">Tanggal Jatuh Tempo</th>
            <th scope="col">Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php 
              if(is_null($data)){
              }else{
                foreach($data as $dataset){
                    echo "<tr>";
                    echo "<td>".$dataset['kontrak_no']."</td>";
                    echo "<td>".$dataset['kode_angsuran']."</td>";
                    echo "<td>".$dataset['angsuran_ke']."</td>";
                    echo "<td>"."Rp.".number_format(($dataset['angsuran_per_bulan']))."</td>";
                    echo "<td id='sts-$dataset[angsuran_ke]'>".$dataset['status']."</td>";
                    echo "<td id='tempo-$dataset[angsuran_ke]'>".$dataset['tanggal_jatuh_tempo']."</td>";
                    echo "<td>"."<a class='btn btn-sm btn-primary' id='btn-bayar-$dataset[angsuran_ke]' href='pembayaran.php?id_angsur=$dataset[kode_angsuran]&&id_kontrak=$dataset[kontrak_no]' ><i class='fa fa-edit'></i>Bayar</a>"."</td>";
                    echo "</tr>";
                }
              }
            ?>
        </tbody>
      </table>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  var lenghtNum = document.getElementsByTagName('tr').length;

  for(let i = 1; i < lenghtNum; i++){
    var waktu_tempo = document.getElementById("tempo-"+i).innerHTML;
    var status = document.getElementById("sts-"+i).innerHTML;
    var tanggalTempo = new Date(waktu_tempo);
    var tanggalHariIni = new Date();
    var selisihMilidetik = tanggalTempo - tanggalHariIni;
    var selisihHari = Math.ceil(selisihMilidetik / (1000 * 60 * 60 * 24));
    if(status == "sudah bayar"){
     document.getElementById("btn-bayar-"+i).style.pointerEvents = "none";
     document.getElementById("btn-bayar-"+i).style.color = "grey";
    }else if(status == "belom bayar"){
      if(selisihHari < 0 || selisihHari > 31  ){
        document.getElementById("btn-bayar-"+i).style.pointerEvents = "none";
        document.getElementById("btn-bayar-"+i).style.color = "grey";
      }
    }
    console.log(selisihHari)
  }

  // var buttom = document.getElementById("tempo").attributes;
  // let crtAtr = document.createAttribute("readonly");
  // buttom.setNamedItem(crtAtr);
</script>
<?php }?>