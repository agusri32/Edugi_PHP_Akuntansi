<?php include "koneksi.php"; ?>
	
<?php include "header.php";?>
<h2>Laporan >> Tutup Buku</h2> 
<fieldset style="border:solid 2px #ccc;">
	<legend><strong>Tutup Buku </strong></legend>
	<table border="0" width="100%" align="center">
	<tr>
		<td width="100%" align="center">
		<i>Tahap ini untuk menghubungkan periode akuntansi yang sedang dibuat laporannya dengan periode akuntansi yang akan datang.</i>
		<p></p>
	  </td>
	</tr>
	<tr>
		<td width="100%" align="center">
		<?php 
		$cek=mysqli_query($link,"select * from tabel_akuntansi_transaksi where keterangan_posting=''");
		$cek_posting=mysqli_num_rows($cek);
		if($cek_posting!==1){
			?>
			<form action="laporan-tutup-buku.php" method="post" name="postform">
			<input type="submit" onClick="return confirm('Apakah Anda Yakin?')" name="posting" value="TUTUP BUKU"/>
			</form>
		<?php
		}
		?>
	  </td>
	</tr>
	</table>
</fieldset>
<?php include "footer.php";?>

<?php
if(isset($_POST['posting'])){	
	?><script language="javascript">document.location.href="core-tutup-buku.php"</script><?php			
}else{
	unset($_POST['posting']);
}
?>