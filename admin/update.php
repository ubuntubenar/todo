
<!-- cek apakah sudah login -->
	<?php 
	session_start();
	if($_SESSION['status']!="login"){
		header("location:index.php?pesan=belum_login");
	}
	?>

<h4>Selamat datang, <?php echo $_SESSION['nama']; ?>! anda telah login.
<a href="logout.php">LOGOUT </a>&lt;== jika ingin keluar halaman</h4>

<?php 

// koneksi database
include 'config.php';
 
// menangkap data yang di kirim dari form
$id = $_POST['id'];
$nama = $_POST['nama'];
$password = $_POST['password'];

 
// update data ke database
mysqli_query($koneksi,"update user SET nama='$nama', password='$password'");

?>


<h2 style="text-align: center;"><strong>Data User</strong></h2><p>&nbsp;</p>
<p><strong><a title="Untuk tambah data" href="tambahdata.php">Tambah data</a></strong></p>
 
<?php
include 'lihat.php';
?>
