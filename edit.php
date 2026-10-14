<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Catatan #<?= $report['id'] ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; margin: 0; padding: 15px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .nav { margin-bottom: 15px; }
        .nav a { text-decoration: none; color: #007bff; font-weight: bold; font-size: 14px; 
        .btn-lihat { background: #007bff; color: white; text-decoration: none; padding: 10px 15px; border-radius: 5px; font-weight: bold; font-size: 14px; }
        .detail-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .detail-table td { padding: 8px; border-bottom: 1px solid #eee; font-size: 14px; vertical-align: top; }
        .detail-table td.label { font-weight: bold; width: 35%; color: #555; }
        .img-preview { max-width: 100%; height: auto; border-radius: 5px; margin-top: 5px; border: 1px solid #ccc; }
        
        /* Section Chat / Feedback */
        .chat-section { margin-top: 30px; border-top: 2px solid #ddd; padding-top: 15px; }
        .chat-box { background: #fafafa; border: 1px solid #e0e0e0; border-radius: 6px; padding: 10px; max-height: 300px; overflow-y: auto; margin-bottom: 15px; }
        .chat-item { background: white; padding: 10px; border-radius: 5px; margin-bottom: 10px; border: 1px solid #eee; }
        .chat-meta { font-size: 12px; color: #888; margin-bottom: 4px; display: flex; justify-content: space-between; }
        .chat-user { font-weight: bold; color: #007bff; }
        .chat-msg { font-size: 14px; color: #333; word-wrap: break-word; }
        .chat-form textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; height: 60px; }
        .btn-reply { background: #28a745; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; margin-top: 5px; font-weight: bold; }
    </style>
</head>
<body>
		<div class="container">
    <div class="nav">
	<a href="masuk.php" class="btn-lihat">Lihat Semua Data</a>
    </div>

		
		
		

	<br/>
	<h3>Edit Things Todo</h3>

	<?php 
	include "config.php";
	$id = $_GET['id'];
	$show = mysqli_query($koneksi,"SELECT * FROM todo WHERE id='$id'");
	$data = mysqli_fetch_array($show);
	?>
	<form action="update.php" method="post">		
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

			<tr>
				<td>Target</td>
				<td><input type="text" name="target" value="<?php echo $data[3] ?>"></td>					
			</tr>

			<tr>
				<td>status</td>
				<td><input type="text" name="status" value="<?php echo $data[4] ?>"></td>					
			</tr>

			<tr>
				<td>Open(o)/close(c)</td>
				<td><input type="text" name="oc" value="<?php echo $data[5] ?>"></td>					
			</tr>



	
			<tr>
				<td></td>
				<td><input type="submit" value="Simpan" class="submitButton" ></td>					
			</tr>				
		</table>
	</form>
	
</body>
</html>