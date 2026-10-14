
<?php 

// koneksi database
include 'config2.php';
 
// menangkap data yang di kirim dari form
$id = $_POST['id'];
$topik = $_POST['topik'];
$detail = $_POST['detail'];
$target = $_POST['target'];
$status = $_POST['status'];
$oc = $_POST['oc'];

 
// update data ke database
mysqli_query($koneksi,"insert into todo (topik,detail,target,status,oc) values('$topik', '$detail', '$target','$status','$oc')");

?>


<h2 style="text-align: center;"><strong>Things To Do</strong></h2><p>&nbsp;</p>
<p><strong><a title="Untuk tambah data" href="tambahdata.php">Tambah data</a></strong></p>
 
<?php
include 'http://192.168.40.8/todo/lihat.php';
?>

<p><a title="tambah data" href="tambahdata.php">Tambah data</a></p>