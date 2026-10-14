<!DOCTYPE html>
<html>
<head>
	<title>Copy dan Modifikasi Todo</title>
</head>
<body>
	<a href="masuk.php">Lihat Semua Data</a>

	<br/>
	<h3>Edit Things Todo</h3>

	<?php 
	include "config.php";
	$id = $_GET['id'];
	$show = mysqli_query($koneksi,"SELECT * FROM todo WHERE id='$id'");
	$data = mysqli_fetch_array($show);
	?>
	<form action="upcopy2.php" method="post">		
		<table>
			<tr>
				<td>Topik</td>
				<td>
					<input type="hidden" name="id" value="<?php echo $data[0] ?>">
					<input type="text" name="topik" value="<?php echo $data[1] ?>">
				</td>					
			</tr>
	
			<tr>
				<td>Detail</td>
				
<td style="width: 900px;"><textarea name="detail" rows="20" cols="80"><?php echo $data[2] ?></textarea>
</td>

			</tr>	
			<td>Target</td>
				<td><input type="text" name="target" value="<?php echo $data[3] ?>"></td>					
			</tr>

			<tr>
				<td><input type="hidden" name="status" value="<?php echo $data[4] ?>"></td>					
			</tr>

			<tr>
				<td><input type="hidden" name="oc" value="<?php echo $data[5] ?>"></td>					
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Copy dan Simpan" class="submitButton" ></td>					
			</tr>				
		</table>
	</form>
	
</body>
</html>