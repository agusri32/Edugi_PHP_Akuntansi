<?php include "koneksi.php"; ?>

<?php include "header.php"; ?>
<div>
<h2>Laporan >> Laporan Jurnal Kas Keluar</h2> 
<fieldset style="border:solid 2px #ccc;">
<legend><strong>PERIODE : </strong></legend>

<form name="postform" action="laporan-jurnal-kas-keluar.php" method="POST">
<p></p>
<table >
	<tr>
		<td>ID User</td>
		<td colspan="3">
			<input type="text" size="11" name="id_user" value="<?php echo $_POST['id_user'];?>"/> <i>(Kosong = semua ID user)</i>
		</td>
	</tr>
	<tr>
		<?php
		$query_tanggal=mysqli_fetch_array(mysqli_query($link,"select min(tanggal_transaksi) as tanggal_pertama from tabel_akuntansi_transaksi"));
		$tanggal_pertama=$query_tanggal['tanggal_pertama'];
		?>

		<td>Mulai Tanggal</td>
		<td>
			<input type="text" name="tanggal1" id="tanggal1" size="11"  value="<?php if(empty($_POST['tanggal_transaksi'])){ echo substr($tanggal_pertama,0,10);}else{ echo $_POST['tanggal_transaksi']; }?>">
			<a href="javascript:;" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.tanggal1);return false;" >
			<img name="popcal" align="absmiddle" src="lib_calender/calender.jpeg" width="34" height="29" border="0" alt="">
			</a>
		</td>
	
		<td>S/D Tanggal</td>
		<td>
			<input type="text" name="tanggal2" id="tanggal2" size="11"  value="<?php if(empty($_POST['tanggal_transaksi'])){ echo $tanggal;}else{ echo $_POST['tanggal_transaksi']; }?>">
			<a href="javascript:;" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.tanggal2);return false;" >
			<img name="popcal" align="absmiddle" src="lib_calender/calender.jpeg" width="34" height="29" border="0" alt="">
			</a>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<input type="submit" name="tampilkan" value="Tampilkan"/>
		</td>
	</tr>
</table>
</form>	
</fieldset>
<br/>

<fieldset style="border:solid 2px #ccc;">
<legend><strong>LAPORAN <?php 
if(isset($_POST['tanggal1'])){
	echo "Tgl ".$_POST['tanggal1']; ?> s/d  <?php echo $_POST['tanggal2'];
}
?>
</strong></legend><br/>

<?php 
if(isset($_POST['tampilkan'])){
	$id_user=$_POST['id_user'];
	$tanggal1=$_POST['tanggal1'];
	$tanggal2=$_POST['tanggal2'];
	
	if(empty($id_user)){
		$q_user="";
	}else{
		$q_user=" id_user='$id_user' and ";
	}
	
	$query=mysqli_query($link,"select * from tabel_akuntansi_transaksi where ".$q_user." jenis_transaksi='Kas Keluar' and tanggal_transaksi between '$tanggal1' and '$tanggal2' order by tanggal_transaksi asc");
	$total=mysqli_fetch_array(mysqli_query($link,"select sum(debet) as tot_debet, sum(kredit) as tot_kredit from tabel_akuntansi_transaksi where ".$q_user." jenis_transaksi='Kas Keluar' and tanggal_transaksi between '$tanggal1' and '$tanggal2' order by nomor_perkiraan asc"));

}else{
	unset($_POST['tampilkan']);
	
	$query=mysqli_query($link,"select * from tabel_akuntansi_transaksi where jenis_transaksi='Kas Keluar' order by tanggal_transaksi asc");
	$total=mysqli_fetch_array(mysqli_query($link,"select sum(debet) as tot_debet, sum(kredit) as tot_kredit from tabel_akuntansi_transaksi where jenis_transaksi='Kas Keluar' order by nomor_perkiraan asc"));
}
?>
<table id='rounded-corner'>
<thead>
	<tr>
		<th width="71" class=rounded-company>Tgl.Transaksi</th>
		<th width="106" class=hr>No.Jurnal</th>
		<th width="118" class=hr>Tipe</th>
		<th width="94" class=hr>No.Prk</th>
		<th width="157" class=hr>Nama Perkiraan</th>
		<th width="185" class=hr>Keterangan</th>
		<th width="100" class=hr><div align="right">Debet</div></th>
		<th width="89" class=hr><div align="right">Kredit</div></th>
		<th scope="col" class="rounded-q4">ID User</th>
	</tr>
</thead>

<tbody>
<?php
while($row=mysqli_fetch_array($query)){
	$tanggal_transaksi=$row['tanggal_transaksi'];
	$kode_jurnal=$row['kode_jurnal'];
	$tipe=$row['jenis_transaksi'];
	$no_prk=$row['nomor_perkiraan'];
	$keterangan=$row['keterangan_transaksi'];
	$debet=$row['debet'];
	$kredit=$row['kredit'];
	$id_user=$row['id_user'];
	
	$query_prk=mysqli_fetch_array(mysqli_query($link,"select nama_perkiraan from tabel_akuntansi_master where nomor_perkiraan='$no_prk'"));
	$nama_prk=$query_prk['nama_perkiraan'];
	
	?>
	
	<tr>
		<td><?php echo $tanggal_transaksi;?></td>
		<td><?php echo $kode_jurnal;?></td>
		<td><?php echo $tipe;?></td>
		<td><?php echo $no_prk;?></td>
		<td><?php echo $nama_prk;?></td>
		<td><?php echo $keterangan;?></td>
		<td><div align="right"><?php echo number_format($debet,2,'.',','); ?></div></td>
		<td><div align="right"><?php echo number_format($kredit,2,'.',','); ?></div></td>
		<td><?php echo $id_user;?></td>
	</tr>
	<?php
}	
?>
</tbody>
	
<tfoot>				
	<tr>
		<td class='rounded-foot-left'></td>
		<td colspan="5"><b>TOTAL</b></td>
		<td><div align="right"><strong><?php echo number_format($total['tot_debet'],2,'.',','); ?></strong></div></td>
		<td><div align="right"><strong><?php echo number_format($total['tot_kredit'],2,'.',','); ?></strong></div></td>
		<td class='rounded-foot-right' width=65></td>
	</tr>
</tfoot>
</table>

</fieldset>
</div>
<!--  PopCalendar(tag name and id must match) Tags should not be enclosed in tags other than the html body tag. -->
<iframe width=174 height=189 name="gToday:normal:lib_calender/agenda.js" id="gToday:normal:lib_calender/agenda.js" src="lib_calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
<?php include "footer.php"; ?>