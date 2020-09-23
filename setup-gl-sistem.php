<?php include "koneksi.php"; 

if(isset($_POST['update']))
{	
	$penyusutan=$_POST['penyusutan'];
	$pendapatan=$_POST['pendapatan'];
	$pengeluaran=$_POST['pengeluaran'];
	$aktiva_lancar=$_POST['aktiva_lancar'];
	$aktiva_tetap=$_POST['aktiva_tetap'];
	$kewajiban=$_POST['kewajiban'];
	$modal=$_POST['modal'];
	$kas=$_POST['kas'];
	
	$tanggal1=$_POST['tanggal1'];
	$tanggal2=$_POST['tanggal2'];
	
	$id_setup=$_POST['id_setup'];
	$id_periode=$_POST['id_periode'];

	$query_update=mysqli_query($link,"UPDATE tabel_setup_gl_perkiraan SET 
	penyusutan='$penyusutan',
	pendapatan='$pendapatan',
	pengeluaran='$pengeluaran',
	aktiva_lancar='$aktiva_lancar',
	aktiva_tetap='$aktiva_tetap',
	kewajiban='$kewajiban', 
	modal='$modal', 
	kas='$kas'
	WHERE id_setup='$id_setup'");
	
	$query_tgl=mysqli_query($link,"UPDATE tabel_setup_gl_periode SET periode_awal='$tanggal1', periode_akhir='$tanggal2' WHERE id_periode='$id_periode'");
	
	if($query_update){
		?><script language="javascript">document.location.href="setup-gl-sistem.php?status=Berhasil disimpan";</script><?php
	}else{
		echo mysqli_error();
	}
	
}else{
	unset($_POST['update']);
}

$query_setup=mysqli_query($link,"SELECT * FROM tabel_setup_gl_perkiraan");
$row_setup=mysqli_fetch_array($query_setup);
$cek_setup=mysqli_num_rows($query_setup);

$id_setup=$row_setup['id_setup'];
$penyusutan=$row_setup['penyusutan'];
$pendapatan=$row_setup['pendapatan'];
$pengeluaran=$row_setup['pengeluaran'];
$aktiva_lancar=$row_setup['aktiva_lancar'];
$aktiva_tetap=$row_setup['aktiva_tetap'];
$kewajiban=$row_setup['kewajiban'];
$modal=$row_setup['modal'];
$kas=$row_setup['kas'];

$query_periode=mysqli_query($link,"SELECT * FROM tabel_setup_gl_periode");
$row_periode=mysqli_fetch_array($query_periode);
$cek_periode=mysqli_num_rows($query_periode);

$periode_awal=$row_periode['periode_awal'];
$periode_akhir=$row_periode['periode_akhir'];
$id_periode=$row_periode['id_periode'];
?>

<?php include "header.php"; ?>
<div>
	<h2>Setup GL >> Akuntansi</h2> 
	<legend><strong>PERIODE : </strong></legend>
	<form name="postform" action="setup-gl-sistem.php" method="post">
		<p></p>
		<fieldset style="border:solid 2px #ccc;">
			<table>
				<tr>
					<td>Mulai Tanggal</td>
					<td>
						<input type="text" name="tanggal1" id="tanggal1" size="11" value="<?php echo $periode_awal;?>">
						<a href="javascript:;" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.tanggal1);return false;" >
						<img name="popcal" align="absmiddle" src="lib_calender/calender.jpeg" width="34" height="29" border="0" alt=""></a>
					</td>
					<td>S/D Tanggal</td>
					<td>
						<input type="text" name="tanggal2" id="tanggal2" size="11" value="<?php echo $periode_akhir;?>">
						<a href="javascript:;" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.tanggal2);return false;" >
						<img name="popcal" align="absmiddle" src="lib_calender/calender.jpeg" width="34" height="29" border="0" alt=""></a>
					</td>
				</tr>
			</table>	
		</fieldset><br/>
		
		<fieldset style="border:solid 2px #ccc;">
			<legend><strong>PERKIRAAN : </strong></legend>
			<center><?php echo $_GET['status']; ?></center><br/>
			<table id='rounded-corner'>
				<thead>
					<tr>
						<th width="150">No.Perkiraan</th>
						<th>Nama Perkiraan</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td class='rounded-foot-left'><input type="text" name="penyusutan" value="<?php echo $penyusutan;?>"/> </td>
						<td>PENYUSUTAN</td>
					</tr>
					<tr>
						<td class='rounded-foot-left'><input type="text" name="pendapatan" value="<?php echo $pendapatan;?>"/> </td>
						<td>PENDAPATAN</td>
					</tr>
					<tr>
						<td><input type="text" name="pengeluaran" value="<?php echo $pengeluaran;?>"/> </td>
						<td>PENGELUARAN</td>
					</tr>
					<tr>
						<td><input type="text" name="aktiva_lancar" value="<?php echo $aktiva_lancar;?>"/></td>
						<td>AKTIVA LANCAR</td>
					</tr>
					<tr>
						<td><input type="text" name="aktiva_tetap" value="<?php echo $aktiva_tetap;?>"/></td>
						<td>AKTIVA TETAP</td>
					</tr>
					<tr>
						<td><input type="text" name="kewajiban" value="<?php echo $kewajiban;?>"/></td>
						<td>KEWAJIBAN</td>
					</tr>
					<tr>
						<td><input type="text" name="modal" value="<?php echo $modal;?>"/> </td>
						<td>MODAL</td>
					</tr>
					<tr>
						<td><input type="text" name="kas" value="<?php echo $kas;?>"/></td>
						<td>KAS</td>
					</tr>
					<tr>
						<td>
							<input type="hidden" name="id_setup" value="<?php echo $id_setup;?>">
							<input type="hidden" name="id_periode" value="<?php echo $id_periode;?>">
							<input type="submit" name="update" value="UPDATE" onClick="return confirm('Apakah Anda yakin?')"/>
						</td><td></td>
					</tr>
				</tfoot>
			</table>	
		</fieldset>
	</form>
</div>
<iframe width=174 height=189 name="gToday:normal:lib_calender/agenda.js" id="gToday:normal:lib_calender/agenda.js" src="lib_calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
<?php include "footer.php"; ?>