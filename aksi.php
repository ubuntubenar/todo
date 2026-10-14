
<!-- cek apakah sudah login -->
	<?php 
	session_start();
	if($_SESSION['status']!="login"){
		header("location:index.php?pesan=belum_login");
	}
	?>

<h4>Selamat datang, <?php echo $_SESSION['user']; ?>! anda telah login.
<a href="logout.php">LOGOUT </a>&lt;== jika ingin keluar halaman</h4>



<?php 

// koneksi database
include 'config.php';
 
// menangkap data yang di kirim dari form
//$id_kasus = $_POST['IDkasus'];
$topik = $_POST['topik'];
$detail = $_POST['detail'];
$target = $_POST['target'];
$status = $_POST['status'];
$oc = $_POST['oc'];

 
// menginput data ke database
mysqli_query($koneksi,"insert into todo (topik,detail,target,status,oc) values('$topik', '$detail', '$target','$status','$oc')");


?>


<h2 style="text-align: center;"><strong>Data Things To Do</strong></h2><p>&nbsp;</p>
<p><strong><a title="Untuk tambah data" href="tambahdata.php">Tambah data</a></strong></p>
 
<?php
include 'lihat.php';
?>


<p><a title="tambah data" href="tambahdata.php">Tambah data</a></p>