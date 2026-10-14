<table style="text-align: center; width: 700px; margin-left: auto; margin-right: auto;" border="1" cellspacing="1" cellpadding="1">

<tbody>
<tr>
<td style="text-align: center;background-color: green"><span style="color: white;"><strong>Tahun</strong></span></td>
<td style="text-align: center;background-color: green"><span style="color:  white;"><strong>Bulan</strong></span></td>
<td style="text-align: center;background-color: green"><span style="color:  white;"><strong>Tanggal</strong></span></td>
<td style="text-align: center;background-color: green"><span style="color:  white;"><strong>Item Produk</strong></span></td>
<td style="text-align: center;background-color: green"><span style="color:  white;"><strong>Isi Komplain</strong></span></td>
<td style="text-align: center;background-color: green"><span style="color:  white;"><strong>Kategori</strong></span></td>
<td style="text-align: center;background-color: green"><span style="color:  white;"><strong>Action</strong></span></td>
</tr>

<?php 

// koneksi ke mysql

  $koneksi = mysqli_connect("localhost","root","Ben1612","datakomplain");
 
// Check connection
	if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
	}

// ambil data
    $cari = mysqli_query($koneksi, "SELECT * from daily order by id DESC");
?>




<?php

// tampilkan data
    $no=1;
    foreach ($cari as $row){
	echo "<tr>
            <td>".$row['tahun']."</td>
            <td>".$row['bulan']."</td>
            <td>".$row['tanggal']."</td>
            <td>".$row['item']."</td>
	    <td>".$row['defect']."</td>
	    <td>".$row['kategori']."</td>
		<td><a href='edit.php?id=$row[id]'>Edit</a>
                <a href='delete.php?id=$row[id]'>Delete</a>
            </td>

     </tr>";
        $no++;
    }
?>


</tbody>
</table>

