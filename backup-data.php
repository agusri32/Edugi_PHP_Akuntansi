<?php include "koneksi.php"; ?>

	<?php include "header.php"; ?>
	<div>
	<h2>Maintenance >> Backup Database </h2> 
	<fieldset style="border:solid 2px #ccc;">
		<legend><strong>BACKUP : </strong></legend>
			
		<table border="0" align="center">
		<tr>
			<td>
			<p align="center"><em>Tahap ini untuk membackup database TANPA melakukan proses tutup buku. Tanggal Backup : <?php echo $tanggal;?></em></p>			
			</td>
		</tr>
		<tr>
			<td><p></p></td>
		</tr>
		<tr>
			<td width="954" align="center">

			<form action="backup-data.php" method="post" name="postform">
			<div align="center">
			<input type="submit" onclick="return confirm('Apakah Anda Yakin?')" name="backup" value="Proses Backup"/>
			</div>
			</form>
			
			</td>
		</tr>
		</table>	
		</p>
	</div>
	
	<?php
	if(isset($_POST['backup'])){																		
		?><script language="javascript">document.location.href="content/backup/core-backup.php"</script><?php
		
	}else{
		unset($_POST['backup']);
	}
	?>
	</fieldset>
	<?php include "footer.php"; ?>