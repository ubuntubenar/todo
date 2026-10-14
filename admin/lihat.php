	<?php 
//	session_start();
	if($_SESSION['status']!="login"){
		header("location:index.php?pesan=belum_login");
	}
	?>


<table style="text-align: center; width: 700px; margin-left: auto; margin-right: auto;" border="1" cellspacing="1" cellpadding="1">

<tbody>
<tr>
  <td style="text-align: center;background-color: green"><span style="color: white;"><strong>Nama</strong></span></td>
  <td style="text-align: center;background-color: green"><span style="color:  white;"><strong>Password</strong></span></td>
   <td style="text-align: center;background-color: green"><span style="color:  white;"><strong>Action</strong></span></td>
</tr>

<?php 

// koneksi ke mysql

  $koneksi = mysqli_connect("localhost","benar","Ben1612!","pvt");
 
// Check connection
	if (mysqli_connect_errno()){
  	echo "Koneksi database gagal : " . mysqli_connect_error();
	}

// ambil data
    $cari = mysqli_query($koneksi, "SELECT * from user");
      $no=1;
      foreach ($cari as $row){

// tampilkan data

	echo "<tr>
            <td>".$row['nama']."</td>
            <td>".$row['password']."</td>
            <td><a href='edit.php?id=$row[id]'>Edit</a><br>
                <a href='delete.php?id=$row[id]'>Delete</a>
            </td>

     </tr>";
        $no++;
    }
?>


</tbody>
</table>
