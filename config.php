<?php 
 
$koneksi = mysqli_connect("localhost","benar","Ben1612!","pvt");
 
// Check connection
if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}
 
?>
