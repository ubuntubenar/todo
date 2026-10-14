<!DOCTYPE html>
<html>
<head>
	<title>Edit Akses User</title>
</head>
<body>
	<a href="masuk.php">Lihat Semua User</a>

	<br/>
	<h3>Edit Akses User</h3>

	<?php 
	include "config.php";
	$id = $_GET['id'];
	$show = mysqli_query($koneksi,"SELECT * FROM user where id=$id");
	$data = mysqli_fetch_array($show);
	?>
	<form action="update.php" method="post">		
		<table>
			<tr>
				<td>User</td>
				<td>
					<input type="hidden" name="id" value="<?php echo $data[0] ?>">
					<input type="text" name="nama" value="<?php echo $data[1] ?>">
				</td>					
			</tr>
	
			
			<tr>
				<td>Password</td>
				<td><input type="text" name="password" value="<?php echo $data[2] ?>"></td>					
			</tr>

			<tr>
				<td></td>
				<td><input type="submit" value="Simpan" class="submitButton" ></td>					
			</tr>				
		</table>
	</form>
	
</body>
</html>