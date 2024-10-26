<!doctype html>
<html lang="en">
  <?php include "page/header.php"; ?>
  <?php include "controller/connection.php";
    session_start();
    $koneksi = new database();
    $data = $koneksi->tampil_data();
    $data1 = $koneksi->tampilan_data_angsuran();
    
  ?>
  <body>
    <div class="container form-a">
        <h1 class="title-form">Kredit IMS Finance</h1>
        <?php 
        if(isset($_GET['sts'])){
          $status = $_GET['sts'];
          if($status == 'sukses'){
            echo "<div class='alert alert-success' role='alert'>
                    Data Berhasil di Input!!
                  </div>";
          }
          else{
            echo "<div class='alert alert-danger' role='alert'>
                    Masukan Data Dengan Benar!!
                  </div>";
          }
        }else if(isset($_GET['login'])){
          $status = $_GET['login'];
          if($status == 'gagal'){
            echo "<div class='alert alert-danger' role='alert'>
                    Masukan Username dan Password Dengan Benar!!
                  </div>";
          }
        }
            
        ?> 
        <form class="row g-3" role="form"  action="controller/controller.php?action=addData" method="POST" autocomplete="off">
          <div class="col-md-6">
            <label for="" class="form-label">Client Nama</label>
            <input type="text" class="form-control" name="nama" placeholder="heri" >
          </div>
          <div class="col-md-4">
            <label for="" class="form-label">DP (Down Payment)</label>
            <select id="inputDP" class="form-select" name="dp">
              <option selected>Choose...</option>
              <option>10%</option>
              <option>20%</option>
              <option>30%</option>
              <option>40%</option>
              <option>50%</option>
            </select>
          </div>
          <div class="col-md-2">
            <label for="" class="form-label">Jangka Waktu</label>
            <select id="inputJangkaWaktu" class="form-select" name="jangka_wkt">
              <option selected>Choose...</option>
              <option>1 tahun</option>
              <option>1.5 tahun</option>
              <option>2 tahun</option>
              <option>2.5 tahun</option>
              <option>3 tahun</option>
              <option>3.5 tahun</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="inputCity" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" name="nama_barang">
          </div>
          <div class="col-md-4">
            <label for="" class="form-label">Jenis Barang</label>
            <select id="inputJenisBarang" class="form-select" name="jns_barang">
              <option selected>Choose...</option>
              <option>Kendaraan</option>
              <option>Rumah</option>
              <option>Barang Rumah Tangga</option>
            </select>
          </div>
          <div class="col-md-2">
            <label for="" class="form-label">Bunga</label>
            <input type="text" readonly class="form-control" id="inputBunga" name="bunga" >
          </div>
          <div class="col-md-6">
            <label for="" class="form-label">Username</label>
            <input type="text" class="form-control"  placeholder="Masukan Email Anda" name="username" required>
          </div><div class="col-6">
            <label for="" class="form-label">Password</label>
            <input type="password" class="form-control"  placeholder="*******" name="pass" required>
          </div>
          <div class="col-12">
            <label for="" class="form-label">OTR</label>
            <input type="text" class="form-control" placeholder="Harga Barang" name="otr">
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
      </form>
    </div>
    <div class="container table-d">
      <table class="table">
        <thead>
            <tr>
            <th scope="col">Kontrak_no</th>
            <th scope="col">Client Name</th>
            <th scope="col">Status</th>
            <th scope="col">OTR</th>
            <th scope="col">Info</th>
            </tr>
        </thead>
        <tbody>
            <?php 
              if(is_null($data)){
              }else{
                $no = 0;
                foreach($data as $dataset){
                    $no++;
                    echo "<tr>";
                    echo "<td>".$dataset['kontrak_no']."</td>";
                    echo "<td>".$dataset['client_name']."</td>";
                    echo "<td>".$dataset['status']."</td>";
                    echo "<td>"."Rp.".number_format(($dataset['otr']))."</td>";
                    echo "<td>"."<a class='btn btn-sm btn-primary' href='javascript:void(0);' onclick='doubleClickFunction(\"$dataset[kontrak_no]\")'><i class='fa fa-edit'></i>Masuk</a>";
                    echo "</tr>";
                }
              }
            ?>
        </tbody>
      </table>
    </div>
  </body>
  <?php include('page/card/login.php');?>
  <?php include "page/script.php";?>
</html>