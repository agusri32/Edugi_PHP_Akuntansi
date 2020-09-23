<?php 
include "koneksi.php"; 
include "core-akuntansi.php";
?>

<?php
if(isset($_POST['posting'])){
	
	/////////////////////////HITUNG MUTASI/////////////////////
	$tanggal_posting=$tanggal.$jam;
	
	//1.dari table transaksi, sinkronisasikan yang belum diposting dengan table master berdasarkan nomor rekening
	$query=mysqli_query($link,"select * from tabel_akuntansi_transaksi where keterangan_posting=''");
	while($row=mysqli_fetch_array($query)){
		$nomor_prk=$row['nomor_perkiraan'];
		$debet=$row['debet'];
		$kredit=$row['kredit'];
		
		//2.proses mutasi debet dan kredit berdasarkan table transaksi diatas
		$query_2=mysqli_query($link,"update tabel_akuntansi_master set mut_debet=mut_debet+$debet, mut_kredit=mut_kredit+$kredit where nomor_perkiraan='$nomor_prk'");
	}

	if($query)
	{
		//3.looping sebanyak table master	
		$query_hitung_sisa=mysqli_query($link,"select * from tabel_akuntansi_master");

		while($row_hit_sisa=mysqli_fetch_array($query_hitung_sisa))
		{
			//4.dapatkan data nominal awal dan mutasi hasil proses sebelumnya
			$awal_debet=$row_hit_sisa['awal_debet'];
			$awal_kredit=$row_hit_sisa['awal_kredit'];
			$mutasi_debet=$row_hit_sisa['mut_debet'];
			$mutasi_kredit=$row_hit_sisa['mut_kredit'];
			
			//5.proses sisa berdasarkan parameter rekening dan posisi normal
			$nomor_perkiraan=$row_hit_sisa['nomor_perkiraan'];
			$normal=$row_hit_sisa['normal'];
			
			//jika normalnya debet
			if($normal=="D"){
				$hitung_sisa_debet=($awal_debet+$mutasi_debet)-$mutasi_kredit;
				
				//filter nomor akun penyusutan
				if(in_array($nomor_perkiraan,$tampil_penyusutan)){ 
				  $status="penyusutan";
				}else{ 
				  $status="normal"; 
				} 

				if($hitung_sisa_debet<0 && $status=="normal"){
					$positif_sisa_kredit=abs($hitung_sisa_debet);
					$update_mutasi=mysqli_query($link,"update tabel_akuntansi_master set sisa_debet=0, sisa_kredit='$positif_sisa_kredit' where nomor_perkiraan='$nomor_perkiraan'");
				}else{
					$update_mutasi=mysqli_query($link,"update tabel_akuntansi_master set sisa_debet='$hitung_sisa_debet', sisa_kredit='0' where nomor_perkiraan='$nomor_perkiraan'");
				}	
			}
			
			//jika normalnya kredit
			if($normal=="K"){
				$hitung_sisa_kredit=($awal_kredit-$mutasi_debet)+$mutasi_kredit;
				
				if($hitung_sisa_kredit<0){
					$positif_sisa_debet=abs($hitung_sisa_kredit);
					$update_mutasi=mysqli_query($link,"update tabel_akuntansi_master set sisa_debet='$positif_sisa_debet', sisa_kredit='0' where nomor_perkiraan='$nomor_perkiraan'");
				}else{
					$update_mutasi=mysqli_query($link,"update tabel_akuntansi_master set sisa_debet=0, sisa_kredit='$hitung_sisa_kredit' where nomor_perkiraan='$nomor_perkiraan'");
				}	
			}
		}
	}else{
		echo mysql_error();
	}
	
	//////////////////////////UBAH STATUS POSTING//////////////////////////////
	$selesai=mysqli_query($link,"update tabel_akuntansi_transaksi set tanggal_posting='$tanggal_posting', keterangan_posting='Post' where keterangan_posting=''");
	
	if($selesai){
		?><script language="javascript">document.location.href="trans-jurnal-posting.php?status=Berhasil Posting Jurnal"</script><?php
	}else{
		echo mysql_error();
	}

}else{
	unset($_POST['posting']);
}
?>

<?php include "header.php"; ?>
<fieldset style="border:solid 2px #ccc;">
	<legend><strong>Transaksi</strong></legend>	
	<table id='rounded-corner'>
	<thead>
		<tr>
		  <th width="200" class=rounded-company>Tgl.Transaksi</th>
		  <th width="200" class=hr>Kode Transaksi</th>
		  <th width="200" class=hr>No.Prk</th>
		  <th width="500" class=hr>Nama Rekening</th>
		  <th width="400" class=hr>Keterangan</th>
		  <th width="70" class=hr><div align="right">Debet</div></th>
		  <th width="70" class=hr><div align="right">Kredit</div></th>
		  <th class="rounded-q4" scope="col">Status</th>
	</tr>
	</thead>
	<tbody>
	<?php
		$query_transaksi=mysqli_query($link,"select * from tabel_akuntansi_transaksi transaksi, tabel_akuntansi_master master where transaksi.nomor_perkiraan=master.nomor_perkiraan order by id_transaksi asc");
		while($row_tran=mysqli_fetch_array($query_transaksi)){
			$debet=$row_tran['debet'];
			$kredit=$row_tran['kredit'];
			?>
			<tr>
			<td><?php echo $row_tran['tanggal_transaksi'];?></td>
			<td><?php echo $row_tran['kode_jurnal'];?></td>
			<td><?php echo ucwords($row_tran['nomor_perkiraan']);?></td>
			<td><?php echo ucwords($row_tran['nama_perkiraan']);?></td>
			<td><?php echo ucwords($row_tran['keterangan_transaksi']);?></td>
			<td align="right"><?php echo number_format($debet,2,'.',','); ?></td>
			<td align="right"><?php echo number_format($kredit,2,'.',','); ?></td>
			<td align="center"><b><font color='#0066FF'><?php echo strtoupper($row_tran['keterangan_posting']);?></font></b></td>
			</tr>
		<?php
		}
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan='6' class='rounded-foot-left'></td>
			<td class='rounded-foot-right'></td>
	  </tr>
	</tfoot>			  				
	</table>
</fieldset>
<p></p>
<fieldset style="border:solid 2px #ccc;">
	<legend><strong>Posting</strong></legend>
	<table border="0" align="center">
	<tr>
		<td width="72" align="center">
		<!---untuk mengakhiri posting dan memberi tanda posting-->
		<?php 
		$cek=mysqli_query($link,"select * from tabel_akuntansi_transaksi where keterangan_posting=''");
		$cek_posting=mysqli_num_rows($cek);
		if($cek_posting!==0){
			?>
			<form action="trans-jurnal-posting.php" method="post" name="postform">
			<input type="submit" onClick="return confirm('Apakah Anda Yakin?')" name="posting" value="POSTING JURNAL"/>
			</form>
		<?php
		}
		?>		  
		</td>
	</tr>
	<tr>
		<td width="601" align="center">
		<font face="verdana" color="#0066FF">
		<?php
		echo $page=$_GET['status'];
		?>
		</font>			
		</td>
	</tr>
	</table>
</fieldset>
<?php include "footer.php"; ?>	