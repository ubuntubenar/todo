
<?php 
// mengaktifkan session php
session_start();

// menghubungkan dengan koneksi
include 'config.php';

// menangkap data yang dikirim dari form
$nama = $_POST['nama'];
$password = $_POST['password'];

// menyeleksi data admin dengan username dan password yang sesuai
$data = mysqli_query($koneksi,"select * from user where nama='$nama' and password='$password'");



// menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($data);


if($cek > 0){	
	 $qry = mysqli_fetch_array($cek);
	$_SESSION['namapanjang']=$qry['namapanjang'];
	$_SESSION['nama'] = $nama;
	$_SESSION['status'] = "login";
header("location:masuk.php");
}else{
	header("location:index.php?pesan=gagal");
}

?>