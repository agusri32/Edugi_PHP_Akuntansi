<?php include "koneksi.php"; ?>

<?php include "header.php"; ?>
<div>
<h2>Laporan >> Rekap Jurnal Harian</h2> 
<fieldset style="border:solid 2px #ccc;">
<legend><strong>PERIODE : </strong></legend>

<form name="postform" action="laporan-jurnal-rekap-harian.php" method="POST">
<p></p>
<table>
	<tr>
		<td>ID User</td>
		<td colspan="3">
			<input type="text" size="11" name="id_user" value="<?php echo $_POST['id_user'];?>"/> <i>(Kosong = semua ID user)</i>
		</td>
	</tr>
	<tr>
		<td>Mulai Tanggal</td>
		<?php
		$query_tanggal=mysqli_fetch_array(mysqli_query($link,"select min(tanggal_transaksi) as tanggal_pertama from tabel_akuntansi_transaksi"));
		$tanggal_pertama=$query_tanggal['tanggal_pertama'];
		?>
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
</fieldset><br/>

<fieldset style="border:solid 2px #ccc;">
<legend><strong>LAPORAN <?php 
if(isset($_POST['tanggal1'])){
	echo "Tgl ".$_POST['tanggal1']; ?> s/d  <?php echo $_POST['tanggal2'];
}
?>
</strong></legend><br/>

<table width="603" id='rounded-corner'>
<thead>
	<tr>
		<th width="73" class=rounded-company>Tgl.Transaksi</th>
		<th width="112" class=hr><div align="center">No.Prk</div></th>
		<th width="192" class=hr>Nama Perkiraan</th>
		<th width="98" class=hr><div align="right">Debet</div></th>
		<th scope="col" class=hr><div align="right">Kredit</div></th>
		<th scope="col" class="rounded-q4"><div align="right">ID User</div></th>
	</tr>
</thead>
<?php 
if(isset($_POST['tampilkan'])){
	
	$tanggal1=$_POST['tanggal1'];
	$tanggal2=$_POST['tanggal2'];
	$id_user=$_POST['id_user'];
	
	if(empty($id_user)){
		$q_user="";
	}else{
		$q_user=" id_user='$id_user' and ";
	}
	
	
	$query_tanggal=mysqli_query($link,"select distinct tanggal_transaksi from tabel_akuntansi_transaksi where ".$q_user." tanggal_transaksi between '$tanggal1' and '$tanggal2' order by tanggal_transaksi asc");
	$jml_semua=mysqli_fetch_array(mysqli_query($link,"select sum(debet) as semua_debet, sum(kredit) as semua_kredit from tabel_akuntansi_transaksi where ".$q_user." tanggal_transaksi between '$tanggal1' and '$tanggal2'"));

	//tampilkan berdasarkan tanggal tertentu.
	while($row_tanggal=mysqli_fetch_array($query_tanggal)){
		$tanggal_transaksi=$row_tanggal['tanggal_transaksi'];
		
		$query=mysqli_query($link,"select distinct nomor_perkiraan,id_user from tabel_akuntansi_transaksi where ".$q_user." tanggal_transaksi='$tanggal_transaksi' order by nomor_perkiraan asc");
		$jumlah=mysqli_fetch_array(mysqli_query($link,"select sum(debet) as tot_debet, sum(kredit) as tot_kredit from tabel_akuntansi_transaksi where ".$q_user." tanggal_transaksi='$tanggal_transaksi'"));
		while($row=mysqli_fetch_array($query)){
			$nomor_perkiraan=$row['nomor_perkiraan'];
			$q_sum=mysqli_fetch_array(mysqli_query($link,"select sum(debet) as debet, sum(kredit) as kredit from tabel_akuntansi_transaksi where tanggal_transaksi='$tanggal_transaksi' and nomor_perkiraan='$nomor_perkiraan'"));
			$q_prk=mysqli_fetch_array(mysqli_query($link,"select nama_perkiraan from tabel_akuntansi_master where nomor_perkiraan='$nomor_perkiraan'"));
			
			?>
			<tr>
				<td><?php echo $tanggal_transaksi;?></td>
				<td><div align="center"><?php echo $row['nomor_perkiraan'];?></div></td>
				<td><?php echo $q_prk['nama_perkiraan'];?></td>
				<td><div align="right"><?php echo number_format($q_sum['debet'],2,'.',','); ?></div></td>
				<td><div align="right"><?php echo number_format($q_sum['kredit'],2,'.',','); ?></div></td>
				<td><div align="right"><?php echo $row['id_user'];?></div></td>
			</tr>
			<?php
		}	
		?>
		<tr>
			<td colspan="6"></td>
		</tr>
		<?php
	}
	
}else{
	unset($_POST['tampilkan']);
	
	$query_tanggal=mysqli_query($link,"select distinct tanggal_transaksi,id_user from tabel_akuntansi_transaksi");
	$jml_semua=mysqli_fetch_array(mysqli_query($link,"select sum(debet) as semua_debet, sum(kredit) as semua_kredit from tabel_akuntansi_transaksi"));
	
	while($row_tanggal=mysqli_fetch_array($query_tanggal)){
		$tanggal_transaksi=$row_tanggal['tanggal_transaksi'];
		
		$query=mysqli_query($link,"select distinct nomor_perkiraan from tabel_akuntansi_transaksi where tanggal_transaksi='$tanggal_transaksi' order by nomor_perkiraan asc");
		$jumlah=mysqli_fetch_array(mysqli_query($link,"select sum(debet) as tot_debet, sum(kredit) as tot_kredit from tabel_akuntansi_transaksi where tanggal_transaksi='$tanggal_transaksi'"));
		while($row=mysqli_fetch_array($query)){
			$nomor_perkiraan=$row['nomor_perkiraan'];
			$q_sum=mysqli_fetch_array(mysqli_query($link,"select sum(debet) as debet, sum(kredit) as kredit from tabel_akuntansi_transaksi where tanggal_transaksi='$tanggal_transaksi' and nomor_perkiraan='$nomor_perkiraan'"));
			$q_prk=mysqli_fetch_array(mysqli_query($link,"select nama_perkiraan from tabel_akuntansi_master where nomor_perkiraan='$nomor_perkiraan'"));
			
			?>
			<tr>
				<td><?php echo $tanggal_transaksi;?></td>
				<td><div align="center"><?php echo $row['nomor_perkiraan'];?></div></td>
				<td><?php echo $q_prk['nama_perkiraan'];?></td>
				<td><div align="right"><?php echo number_format($q_sum['debet'],2,'.',','); ?></div></td>
				<td><div align="right"><?php echo number_format($q_sum['kredit'],2,'.',','); ?></div></td>
				<td><div align="right"><?php echo $row['id_user'];?></div></td>
			</tr>
			<?php
		}	
		?>
		<tr>
			<td colspan="6"></td>
		</tr>
		<?php
	}
}
?>
<tfoot>				
	<tr>
		<td colspan="3" class='rounded-foot-left'><strong>TOTAL</strong></td>
		<td><div align="right"><strong><?php echo number_format( $jml_semua['semua_debet'],2,'.',','); ?></strong></div></td>
		<td class='rounded-foot-right' width=104><div align="right"><strong><?php echo number_format( $jml_semua['semua_kredit'],2,'.',','); ?></strong></div></td>
		<td></td>
	</tr>
</table>

</fieldset>
</div>
<!--  PopCalendar(tag name and id must match) Tags should not be enclosed in tags other than the html body tag. -->
<iframe width=174 height=189 name="gToday:normal:lib_calender/agenda.js" id="gToday:normal:lib_calender/agenda.js" src="lib_calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
<?php include "footer.php"; ?>