<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/script.js" type="text/javascript"></script>
<script>
    var selectElement = document.getElementById("inputJangkaWaktu");
    var inputBunga = document.getElementById("inputBunga")
    
    var selectedValue = selectElement.addEventListener("change", selectbytag);
    function selectbytag() {
        var selectedOption = selectElement.options[selectElement.selectedIndex].value;
        var integerValue = parseFloat(selectedOption.replace("tahun", "").trim());
        var bulan = integerValue * 12;
        if(bulan <= 12){
            inputBunga.value = "12%";
        }else if(bulan > 12 && bulan <= 24){
            inputBunga.value = "14%";
        }else if(bulan > 24){
            inputBunga.value = "16.5%";
        }else{
            inputBunga.value = "";
        }   
    }
    var clickTimeout;

// Fungsi untuk menangani klik tunggal (redirect ke link)
// function singleClickFunction(kontrak_no) {
//     clickTimeout = setTimeout(function() {
//         window.location.href = "index.php?id=" + kontrak_no;
//     },1000); // Delay to distinguish single vs double click
// }

// Fungsi untuk menangani klik ganda (tampilkan login popup)
function doubleClickFunction(kontrak_no) {
    var kode_kontrak = kontrak_no;
    document.getElementById('no_kontrak').value = kontrak_no;
    // clearTimeout(clickTimeout); // Membatalkan aksi klik tunggal
    document.getElementById('popupOverlay').style.display = 'block'; // Tampilkan overlay
    document.getElementById('loginPopup').style.display = 'block'; // Tampilkan popup
    
}

// Fungsi untuk menutup popup
function closePopup() {
    document.getElementById('popupOverlay').style.display = 'none'; // Sembunyikan overlay
        document.getElementById('loginPopup').style.display = 'none'; // Sembunyikan popup
}

</script>