<?php 
include "koneksi.php";
include "core-akuntansi.php";
?>

<?php include "header.php"; ?>
<script>
function suggest(inputString){
	if(inputString.length == 0) {
		$('#suggestions').fadeOut();
	} else {
	$('#country').addClass('load');
		$.post("autosuggest.php", {queryString: ""+inputString+""}, function(data){
			if(data.length >0) {
				$('#suggestions').fadeIn();
				$('#suggestionsList').html(data);
				$('#country').removeClass('load');
			}
		});
	}
}

function fill(thisValue) {
	$('#nama').val(thisValue);
	setTimeout("$('#suggestions').fadeOut();", 100);
}

function fill2(thisValue) {
	$('#kode').val(thisValue);
	setTimeout("$('#suggestions').fadeOut();", 100);
}
</script>

<style>
#result {
	height:20px;
	font-size:12px;
	font-family:Arial, Heleveletica, sans-serif;
	color:#333;
	padding:5px;
	margin-bottom:10px;
	background-color:#FFFF99;
}
#country{
	padding:3px;
	border:1px #CCC solid;
	font-size:12px;
}
.suggestionsBox {
	position: absolute;
	left: 0px;
	top:7px;
	margin: 26px 0px 0px 0px;
	width: 210px;
	padding:0px;
	background-color:#999999;
	border-top: 3px solid #999999;
	color: #fff;
}
.suggestionList {
	margin: 0px;
	padding: 0px;
}
.suggestionList ul li {
	list-style:none;
	margin: 0px;
	padding: 6px;
	border-bottom:1px dotted #666;
	cursor: pointer;
}
.suggestionList ul li:hover {
	background-color: #FC3;
	color:#000;
}
ul {
	font-family:Arial, Heleveletica, sans-serif;
	font-size:11px;
	color:#FFF;
	padding:0;
	margin:0;
}
.load{
background-image:url(loader.gif);
background-position:right;
background-repeat:no-repeat;
}
#suggest {
	position:relative;
}
</style>

<?php
if(isset($_POST['next'])){
	$tanggal_transaksi=$_POST['tanggal_transaksi'];
	$kode_jurnal=$_POST['kode_jurnal'];
	$nomor_perkiraan=$_POST['nomor_perkiraan'];
	$keterangan=ucwords($_POST['keterangan_transaksi']);
	$jumlah_transaksi=str_replace(".","",$_POST['jumlah_transaksi']);
	
	//fungsi panggil nama bulan
	$bulan=tampil_bulan($tanggal_transaksi);
	
	$input_transaksi=mysqli_query($link,"INSERT INTO tabel_akuntansi_transaksi(kode_jurnal, nomor_perkiraan, tanggal_transaksi, jenis_transaksi, keterangan_transaksi, debet, id_user, bulan_transaksi) 
	values('$kode_jurnal','$nomor_perkiraan','$tanggal_transaksi','Kas Keluar','$keterangan','$jumlah_transaksi','$id_user','$bulan')");

}else{
	unset($_POST['next']);
}

if(isset($_POST['selesai'])){
	$kode_jurnal=$_POST['kode_jurnal'];
	$nomor_jurnal=$_POST['nomor_jurnal'];
	$tanggal_selesai=$_POST['tanggal_selesai'];
	$total_kas=$_POST['total_kas'];
	$keterangan_jurnal=ucwords($_POST['keterangan_jurnal']);
	$keterangan_invoice=strtoupper($_POST['keterangan_invoice']);

	//fungsi panggil nama bulan
	$bulan=tampil_bulan($tanggal_selesai);
	
	//karena kas keluar lawan transaksinya adalah kas, kas yang ada di sisi KREDIT.
	$nomor_kas_induk=$_POST['nomor_kas_induk'];
	
	$query_transaksi=mysqli_query($link,"insert into tabel_akuntansi_transaksi(kode_jurnal,nomor_perkiraan,tanggal_transaksi,bulan_transaksi, jenis_transaksi, keterangan_transaksi, kredit,id_user)
		values('$kode_jurnal','$nomor_kas_induk','$tanggal_selesai','$bulan','Kas Keluar','$keterangan_jurnal','$total_kas','$id_user')");

	if($query_transaksi){
		$query_jurnal=mysqli_query($link,"insert into tabel_akuntansi_jurnal_keluar(nomor_jurnal, kode_jurnal, kode_invoice, tanggal_selesai) values('$nomor_jurnal','$kode_jurnal', '$keterangan_invoice','$tanggal_selesai')");	
		
		//fungsi posting otomatis
		//posting_jurnal();
	
		//fungsi hitung SHU otomatis
		//hitung_shu();
		
		?><script language="javascript">document.location.href="trans-jurnal-kas-keluar.php"</script><?php

	}else{
		echo mysql_error();
	}
}else{
	unset($_POST['selesai']);
}

if($_GET['mode']=='cancel'){
	$id_cancel=$_GET['id'];
	mysqli_query($link,"DELETE FROM tabel_akuntansi_transaksi WHERE id_transaksi='$id_cancel'");
}
	
if($_GET['mode']=='reset'){
	$id_cancel=$_GET['id'];
	mysqli_query($link,"DELETE FROM tabel_akuntansi_transaksi WHERE kode_jurnal='$id_cancel'");
}
	
//jurnal baru. cari nomor paling besar yaitu nomor jurnal terakhir 
$jurnal_keluar=mysqli_fetch_array(mysqli_query($link,"SELECT max(nomor_jurnal) as nomor_jurnal FROM tabel_akuntansi_jurnal_keluar ORDER BY tanggal_selesai DESC"));
$nomor_jurnal=$jurnal_keluar['nomor_jurnal']+1;
$kode_jurnal="JKK/".$nomor_jurnal;	
?>

<form name="postform" action="trans-jurnal-kas-keluar.php" method="POST">
<h2>Transaksi >> Transaksi GL &gt;&gt; Jurnal Kas Keluar</h2> 
<fieldset style="border:solid 2px #ccc;">
<legend><strong>FORM 1</strong></legend>
<table align="left" border=0 cellspacing=0>
<tr>
	<td valign="top">
		<table >
			<tr>
				<td>Tanggal</td>
				<td>
				<input type="text" name="tanggal_transaksi" autocomplete="off" value="<?php if(empty($_POST['tanggal_transaksi'])){ echo $tanggal;}else{ echo $_POST['tanggal_transaksi']; }?>" id="tgl" size="11">
				<a href="javascript:;" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.tgl);return false;" ><img name="popcal" align="absmiddle" src="lib_calender/calender.jpeg" width="34" height="29" border="0" alt=""></a>
				</td>
			</tr>
			<tr>
				<td>No.Jurnal</td>
				<td>
				<input type="text" name="kode_jurnal" value='<?php echo $kode_jurnal; ?>' readonly>
				</td>
			</tr>
		</table>	
		<br>	
	</td>
	<td valign="top">
	
		<div id="suggest">
		   <div class="suggestionsBox" id="suggestions" style="display: none;">
		   <div class="suggestionList" id="suggestionsList"> &nbsp; </div>
		   </div>
		</div>	
	
	</td>
</tr>
</table>

<!--untuk transaksi-->
<table>
<tr>
	<td align="left" colspan="2">
		<table id='rounded-corner'>
			<thead>
				<tr>
					<th width="71" class=rounded-company>No.Prk</th>
					<th width="160" class=hr>Nama Perkiraan</th>
					<th width="200" class=hr>Keterangan</th>
					<th width="100" class=hr>Jumlah</th>
					<th scope="col" class="rounded-q4">Action</th>
				</tr>
			</thead>
			<tfoot>				
				<tr>
					<td class='rounded-foot-left'>
						<div id="suggest">
						   <input type="text" onKeyUp="suggest(this.value);" name="nomor_perkiraan" autocomplete="off" onBlur="fill2();" id="kode" size="5"/> 
						   <div class="suggestionsBox" id="suggestions" style="display: none;">
						   <div class="suggestionList" id="suggestionsList"> &nbsp; </div>
						   </div>
						</div>								
					</td>
					<td><input type=text disabled="disabled" onBlur="fill();" id="nama"></td>
					<td><input type=text value="<?php echo $_POST['keterangan_transaksi']; ?>" autocomplete="off" name="keterangan_transaksi" size=30></td>
					<td><input type=text name="jumlah_transaksi" id="jumlah_transaksi" autocomplete="off" size=15></td>
					<td class='rounded-foot-right' width=30><input type="submit" name="next" value="Next"></td>
				</tr>
			</tfoot>
			
			<tbody>
			<?php
			$query_transaksi=mysqli_query($link,"select * from tabel_akuntansi_transaksi where kode_jurnal='$kode_jurnal'");
			while($row=mysqli_fetch_array($query_transaksi)){
				$id_transaksi=$row['id_transaksi'];
				$kode_prk=$row['nomor_perkiraan'];
				$q_keterangan=$row['keterangan_transaksi'];
				$debet=$row['debet'];
				
				$query_prk=mysqli_fetch_array(mysqli_query($link,"select nama_perkiraan from tabel_akuntansi_master where nomor_perkiraan='$kode_prk'"));
				$nama_prk=$query_prk['nama_perkiraan'];
				?>
				<tr>
				<td><?php echo $kode_prk; ?></td><td><?php echo ucwords($nama_prk); ?></td><td><?php echo ucwords($q_keterangan); ?></td><td align="right"><?php echo number_format($debet,2,'.',','); ?></td>
				<td><a href="trans-jurnal-kas-keluar.php?mode=cancel&id=<?php echo $id_transaksi;?>" onClick="return confirm('Apakah Anda yakin ?')">Cancel</a></td>
				</tr>
			<?php
			}
			?>
			</tbody>
		</table>
		<br/>	
	</td>
</tr>
</table>
<a href='trans-jurnal-kas-keluar.php?mode=reset&id=<?php echo $kode_jurnal; ?>' onClick="return confirm('Apakah Anda yakin ?')"><font color='blues'>Reset All Jurnal</font></a>
</fieldset>	
</form>	

<form method="POST" action="trans-jurnal-kas-keluar.php" method="post">
<?php
$query_sum=mysqli_fetch_array(mysqli_query($link,"select sum(debet) as tot_debet from tabel_akuntansi_transaksi where kode_jurnal='$kode_jurnal' and id_user='$id_user'"));
$tot_debet=$query_sum['tot_debet'];
?>

<input type="hidden" name="tanggal_selesai" size="15" value="<?php if(empty($_POST['tanggal_transaksi'])){ echo $tanggal;}else{ echo $_POST['tanggal_transaksi']; }?>"/>
<input type="hidden" name="kode_jurnal" value="<?php echo $kode_jurnal;?>">
<input type="hidden" name="nomor_jurnal" value="<?php echo $nomor_jurnal;?>">
<input type="hidden" name="total_kas" value="<?php echo $tot_debet;?>">

<fieldset style="border:solid 2px #ccc;">
<legend><strong>FORM 2</strong></legend>
<table border="0" width="100%">
<tr>
	<td width="10%">Kas </td>
	<td width="53%">	
		<input type="hidden" name="nomor_kas_induk" value="<?php echo $tampil_kas;?>">
		<input type="text" value="<?php echo $tampil_kas; ?> (Kas)" disabled="disabled" size="30">
	</td>
	<td width="5%"><strong>Total</strong></td>
	<td width="32%"><input type="text" size="30" disabled="disabled" value="<?php echo number_format($tot_debet,2,'.',','); ?>"></td>
</tr>
<tr>
	<td width="10%">Keterangan</td>
	<td><input type="text" name="keterangan_jurnal" value="<?php echo $_POST['keterangan_transaksi']; ?>" size="30"></td>
	<td width="5%">No.Invoice</td>
	<td><input type="text" name="keterangan_invoice" value="<?php echo $_POST['keterangan_invoice']; ?>" size="30"></td>
</tr>
<tr>
	<td width="10%">
	<input type="submit" value="Selesai" name="selesai" onClick="return confirm('Apakah Anda yakin ?')">
	</td>
</tr>
</table>
</fieldset>
</form>	

<!--  PopCalendar(tag name and id must match) Tags should not be enclosed in tags other than the html body tag. -->
<iframe width=174 height=189 name="gToday:normal:lib_calender/agenda.js" id="gToday:normal:lib_calender/agenda.js" src="lib_calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>

<script>
$(document).ready(function(){
	$("#jumlah_transaksi").change(function(){
		tampil_nominal();
    });	
	
	$("#jumlah_transaksi").keyup(function(){
		tampil_nominal();
    });
});

function tampil_nominal(){
	var jml=$("#jumlah_transaksi").val();
	var konversi=kurensi(jml);
	$('#jumlah_transaksi').val(konversi);
}

function kurensi(nilai) 
{
	bk=nilai.replace(/[^\d]/g,"");
	ck="";
	panjangk=bk.length;
	j=0;
	for (i=panjangk; i > 0; i--) 
	{
		j=j + 1;
		if (((j % 3) == 1) && (j != 1)) 
		{
			ck=bk.substr(i-1,1) + "." + ck;
			xk=bk;
		} 
		else 
		{
			ck=bk.substr(i-1,1) + ck;
			xk=bk;
		}
	}
	return ck;
}
</script>
<?php include "footer.php"; ?>