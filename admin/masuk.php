<!DOCTYPE html>
<html>
<head>
	<title>Login Todo</title>
</head>
<body>
	<!-- cek apakah sudah login -->
	<?php 
	session_start();
	if($_SESSION['status']!="login"){
		header("location:index.php?pesan=belum_login");
	}
	?>

	<h4>Selamat datang, <?php 

    include 'config.php';

echo $_SESSION['namapanjang']; ?>! anda telah login.
	<a href="logout.php">LOGOUT</a><== jika ingin keluar</h4>


<p style="text-align: center;"><em><strong>Administrasi	 User</strong></em></p>
<p style="text-align: center;"><a href="tambahdata.php"><em><strong>Tambah User</strong></em></a></p>


<?php
include 'lihat.php'
?>

</body>
</html>