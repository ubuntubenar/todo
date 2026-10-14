<?php
include 'config.php';
// menyimpan data id kedalam variabel
$id = $_GET['id'];
// query SQL untuk delete data
mysqli_query($koneksi, "DELETE from todo where id='$id'");
// mengalihkan ke halaman index.php
header("location:masuk.php");
?>