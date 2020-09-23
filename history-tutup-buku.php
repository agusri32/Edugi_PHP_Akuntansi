<?php include "koneksi.php"; ?>
	
	<?php include "header.php";?>
	<div>
	<h2>Laporan >> History Tutup Buku </h2> 
	<fieldset style="border:solid 2px #ccc;">
		<legend><strong>HISTORY : </strong></legend>
		<table width="417" id='rounded-corner'>
		<thead>
			<tr>
				<th width="69" class=rounded-company><div align="center">Nomor</div></th>
				<th width="101" class=hr><div align="center">Nama File</div></th>
				<th width="134" class=hr><div align="center">Tanggal Backup</div></th>
				<th width="50" class=hr><div align="center">ID user</div></th>
				<th scope="col" class="rounded-q4"><div align="center">Aksi</div></th>
			</tr>
		</thead>
		<?php
		$query=mysqli_query($link,"select * from tabel_akuntansi_history_tutup_buku order by tanggal_backup desc");
		while($row=mysqli_fetch_array($query)){
			?>
			<tr>
			<td><div align="center"><?php echo $c=$c+1;?></div></td><td><div align="center"><?php echo $row['nama_file'];?></div></td>
			<td><div align="center"><?php echo $row['tanggal_backup'];?></div></td><td><div align="center"><?php echo $row['id_user'];?></div></td>
			<td>
			<div align="center">
			<a  href="download-tutup-buku.php?nama_file=<?php echo $row['nama_file'];?>" title="Download"><img  border="0" src="./images/save.jpeg"/></a> 
			<a href="trust.phphistory-tutup-buku.php?nama_file=<?php echo $row['nama_file'] ?>&mode=delete" onclick="return confirm('Apakah Anda yakin akan menghapus data ini?')" title="Delete"><img border="0" src="./images/delete.png"/></a>
			</span>
			</div>
			</td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td colspan="5">
			<br/>
			<div align="center">
			<font face="verdana" color="#0066FF">
			<?php
			//pecahkan nilai array
			echo $status=$_GET['status'];
			$mode=$_GET['mode'];
			$nama_file=$_GET['nama_file'];
			
			if($mode=='delete'){
				$delete=mysqli_query($link,"delete from tabel_akuntansi_history_tutup_buku where nama_file='$nama_file'");
				unlink("./content/laporan/data/".$nama_file."");
				
				if($delete){
					?><script language="javascript">document.location.href="trust.phphistory-tutup-buku.php?status=Data berhasil di hapus"</script><?php
				}else{
					echo mysql_error();
				}
			}
			?>
			</font>
			</div>
			</td>
		</tr>
		<tfoot>				
		<tr>
			<td class='rounded-foot-left' colspan="4">
			<b>TOTAL FILE BACKUP :
			<?php 
			$total=mysqli_query($link,"select * from tabel_akuntansi_history_tutup_buku"); 
			echo mysqli_num_rows($total);
			?>
			</b>
			</td>
			<td class='rounded-foot-right' width=93></td>
		</tr>
		</tfoot>
	</table>
	</fieldset>
	</div>
	<?php include "footer.php";?>
