<?php 
 
$koneksi = mysqli_connect("http://192.168.40.235","root","123456","pvt");
 
// Check connection
if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}
 
?>