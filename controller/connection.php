<?php
use LDAP\Result;

class database {
    var $host = "localhost";
    var $username = "root";
    var $password = "";
    var $database = "db_appangsuran";
    var $koneksi;

    function __construct()
    {
        $this->koneksi = mysqli_connect($this->host, $this->username, $this->password,$this->database);
		if (mysqli_connect_errno()){
			echo "Koneksi database gagal : " . mysqli_connect_error();
		}      
    }
    function tampil_data(){
        $data = mysqli_query($this->koneksi, "select * from tbl_kontrak");
        while($row = mysqli_fetch_array($data)){
			$hasil[] = $row;
		}
		return $hasil;
    }
    function tampilan_data_angsuran(){
        $data = mysqli_query($this->koneksi, "select * from tbl_jadwal_angsur");
        while($row = mysqli_fetch_array($data)){
			$hasil[] = $row;
		}
		return $hasil;
    }
    function tampil_data_angsuranById($id){
        $data = mysqli_query($this->koneksi, "select * from tbl_jadwal_angsur
                            where kontrak_no = '$id'");
        while($row = mysqli_fetch_array($data)){
            $hasil[] = $row;
        }
        return $hasil;
    }
    function tampil_pembayaran($id_angsur, $id_kontrak){
        $result = [];
        $data = mysqli_query($this->koneksi, "select a.kontrak_no, b.kode_angsuran ,a.client_name, c.jenis_barang, c.nama_barang, b.angsuran_per_bulan,
                                b.angsuran_ke, b.tanggal_jatuh_tempo from tbl_kontrak a
                                INNER JOIN tbl_jadwal_angsur b on b.kontrak_no = a.kontrak_no
                                INNER JOIN tbl_angsuran c on c.kontrak_no = a.kontrak_no
                                WHERE b.kode_angsuran = '$id_angsur' && a.kontrak_no = '$id_kontrak'");
        foreach($data as $value){
            $mapping = array();
            $mapping['kode_kontrak'] = $value['kontrak_no'];
            $mapping['kode_angsuran'] = $value['kode_angsuran'];
            $mapping['name_client'] = $value['client_name'];
            $mapping['jenis_barang'] = $value['jenis_barang'];
            $mapping['nama_barang'] = $value['nama_barang'];
            $mapping['angsuran_per_bulan'] = floatval($value['angsuran_per_bulan']);
            $mapping['angsuran_ke'] = $value['angsuran_ke'];
            $mapping['tgl_jatuh_tempo'] = $value['tanggal_jatuh_tempo'];
        
            $tanggal_jatuh_tempo = $value['tanggal_jatuh_tempo'];
            $tglTempo = strtotime($tanggal_jatuh_tempo);
            $tanggalSekarang = time();
            $selisihMilihDetik = $tglTempo - $tanggalSekarang;
            $selisihPerHari = floor($selisihMilihDetik / (60*60*24));
            $totalAnsuranPerBulan = floatval($value['angsuran_per_bulan']);
            $dendaPerHari  = $totalAnsuranPerBulan * 0.001;
            $totalDenda = 0 ;
            if($selisihPerHari < 0){
                $totalDenda = abs($dendaPerHari * $dendaPerHari);
            } 
            $totalPembayaran = $totalAnsuranPerBulan + $totalDenda;
            $mapping['total_denda'] = $totalDenda;
            $mapping['total_pembayaran'] = $totalPembayaran;
            array_push($result, $mapping);
        }
                
        return json_encode($result);
    }
    function addData($nama, $dp, $jangka_wkt, $nama_barang, $jns_barang, $bunga, $otr, $username, $password){
        $angka_acak = str_pad(rand(1, 99), 1, '0', STR_PAD_LEFT);
        $kode = "AGR000" . $angka_acak;
        $tanggalMulai = date_create(date("Y-m-d"));
        $jangka_waktu = floatval(str_replace(' ', '', str_replace("tahun", "", $jangka_wkt)));
        $bulan = $jangka_waktu * 12;
        $dp = intval(str_replace("%", "", $dp));
        $bunga2 = floatval(str_replace("%", "", $bunga));
        $dp = ( $dp / 100) * $otr ;
        $pokok_utang = $otr - $dp;
        $bunga3 = ( $bunga2 / 100) * $pokok_utang;
        $angsur_per_bulan = ($pokok_utang + $bunga3)/ $bulan;
        $angsuran_ke = 0;
        $tanggal_jatuh_tempo = '';
        $result = '';
        $kode_kontrak = '';
        $kode_angsuran = '';
        $data = $this->tampil_data();
        $status = 'belum lunas';
        $pass = md5($password);
        

        if(is_null($data)){
            $kode_kontrak = $kode;
        }else{
        foreach($data as $datas){
            if($datas['kontrak_no'] != $kode){
                $kode_kontrak = $kode;
            }
        }
        }
        
        $query = "insert into tbl_kontrak (kontrak_no, client_name, status, otr, username, password) values
                ('$kode_kontrak', '$nama', '$status', '$otr', '$username', '$pass')";

        for($i = 0; $i < $bulan; $i++){
            $data1 = $this->tampilan_data_angsuran();
            $angka_acak2 = str_pad(rand(1, 99), 1, '0', STR_PAD_LEFT);
            $angsuran_ke++;
            $kode_angs = "ANG000". $angka_acak2;
            date_modify($tanggalMulai,"1 month");
            $tanggal_jatuh_tempo = date_format($tanggalMulai,"Y-m-d");

            if(is_null($data1)){
                $kode_angsuran = $kode_angs;
            }else{
            foreach($data1 as $datas1){
                if ($datas1['kode_angsuran'] != $kode_angs){
                    $kode_angsuran = $kode_angs;
                }
            }
            } 
            
            $query2 = "insert into tbl_jadwal_angsur (kontrak_no, kode_angsuran, angsuran_ke, angsuran_per_bulan, status, tanggal_jatuh_tempo) 
                        values ('$kode_kontrak', '$kode_angsuran', '$angsuran_ke', '$angsur_per_bulan', 'belom bayar', '$tanggal_jatuh_tempo')";
            
            mysqli_query($this->koneksi, $query2);  
        }
        $query3 = "insert into tbl_bunga (kontrak_no, jangka_waktu, bunga) values
                ('$kode_kontrak', '$jangka_wkt', '$bunga')";
        $query4 = "insert into tbl_angsuran (kontrak_no, nama_barang, jenis_barang) values
                    ('$kode_kontrak', '$nama_barang', '$jns_barang')";
        
        $exec = mysqli_query($this->koneksi, $query);
        $exec3 = mysqli_query($this->koneksi, $query3);
        $exec4 = mysqli_query($this->koneksi, $query4);

        if($exec && $exec3 && $exec4){
            $result = 'sukses';
        }else{
            $result = 'gagal';
        }   

        return $result; 
        
    }
    function editDataPembayaran($kode_angsur){
        $result = '';
        $query = "Update tbl_jadwal_angsur set status = 'sudah bayar' where kode_angsuran = '$kode_angsur'";
        $data = mysqli_query($this->koneksi, $query);
        if($data){
            $result = 'sukses';
        }else{
            $result = 'gagal';
        }
        return $result;
    } 
    function addDataPembayaran($kode_angsur, $total_pembayaran, $tanggal_pembayaran, $denda, $total_denda, $bukti_pembayaran){
        $result = '';
        $query = "insert into tbl_denda (kode_angsuran, denda, total_denda) values
                    ('$kode_angsur', '$denda', '$total_denda')";
        $query2 = "insert into tbl_pembayaran (kode_angsuran, bukti_pembayaran, total_pembayaran, tanggal_pembayaran) values
                    ('$kode_angsur', '$bukti_pembayaran','$total_pembayaran', '$tanggal_pembayaran')";
        
        $data1 = mysqli_query($this->koneksi, $query);
        $data2 = mysqli_query($this->koneksi, $query2);
        if($data1 && $data2){
            $result = "sukses";
        }else{
            $result = "gagal";
        }
        return $result;

    }
    function editDataKontrak($kode_kontrak){
        $query = "update tbl_kontrak set status = 'sudah lunas' where no_kontrak = '$kode_kontrak'";
        mysqli_query($this->koneksi, $query);
    }
    function login($username, $password, $kode_kontrak){
        $pass = md5($password);
        $result = '';
        $exec = mysqli_query($this->koneksi, "select * from tbl_kontrak where username = '$username' and password = '$pass' and kontrak_no = '$kode_kontrak'");
        $x = mysqli_fetch_array($exec);
        $check = mysqli_num_rows($exec);
        if($check > 0){
            $result = 'sukses';
            $_SESSION['kode_kontrak'] = $x['kontrak_no'];
            $_SESSION['status'] = 'sukses';
            $_SESSION['nama'] = $x['client_name'];
        }else{
            $result = 'gagal';
        }
        return $result;
    }
    
}
?>