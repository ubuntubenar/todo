</head>


<body>

<!-- cek apakah sudah login -->
<?php 
	session_start();
	if($_SESSION['status']!="login"){
		header("location:index.php?pesan=belum_login");
	}
	?>

<h4>Selamat datang, <?php echo $_SESSION['nama']; ?>! anda telah login. <a href="logout.php">LOGOUT </a>&lt;== jika ingin keluar halaman</h4>


 
	<h2>Tambah User</h2>
	<br/>
		
	<form method="post" action="aksi.php">

<table style="width: 100px;">


<tr>
	<td style="width: 87px;">User </td>
	<td style="width: 320.067px;"><input name="nama" type="text" /></td>
</tr>
<tr>
	<td style="width: 87px;">Password</td>
	<td style="width: 900px;"><input name="password" type="text" /></td>
</tr>

<tr>
<td><input type="submit" value="SIMPAN"></td>
</tr>

</table>

	</form>


<table >
<tr>
<td>User</td>
<td>Password</td>
</tr>
<?php 
    include 'config.php';
    $lihat = mysqli_query($koneksi, "SELECT * from user");
    $no=1;
    foreach ($lihat as $row){
echo "<tr>
            <td>".$row['nama']."</td>
            <td>".$row['password']."</td>
     </tr>";
        $no++;
    }
?>
</table>
</body>
</html>