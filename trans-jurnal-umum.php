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
//untuk menyimpan transaksi
if(isset($_POST['next'])){

	$kode_jurnal=$_POST['kode_jurnal'];
	$tanggal_transaksi=$_POST['tanggal_transaksi'];
	$keterangan_transaksi=ucwords($_POST['keterangan_transaksi']);
	$nomor_perkiraan=$_POST['nomor_perkiraan'];
	
	//fungsi panggil nama bulan
	$bulan=tampil_bulan($tanggal_transaksi);
	
	if($_POST['posisi']=="d" || $_POST['posisi']=="D"){
		$debet=str_replace(".","",$_POST['jumlah']);
	}else if($_POST['posisi']=="k" || $_POST['posisi']=="K"){
		$kredit=str_replace(".","",$_POST['jumlah']);
	}
	
	if(!empty($debet) && !empty($kredit)){
		?><script language="javascript">document.location.href="trans-jurnal-umum.php?status=Hanya boleh isi salah satu"</script><?php	
	}else{
		$query_transaksi=mysqli_query($link,"insert into tabel_akuntansi_transaksi(kode_jurnal,nomor_perkiraan,tanggal_transaksi, bulan_transaksi, jenis_transaksi, keterangan_transaksi,debet, kredit,id_user)
		values('$kode_jurnal','$nomor_perkiraan','$tanggal_transaksi','$bulan','Jurnal Umum','$keterangan_transaksi','$debet','$kredit','$id_user')");
	}

}else{
	unset($_POST['next']);
}

if($_GET['mode']=='cancel'){
	$id_cancel=$_GET['id'];
	mysqli_query($link,"DELETE FROM tabel_akuntansi_transaksi WHERE id_transaksi='$id_cancel'");
}

if($_GET['mode']=='reset'){
	$id_cancel=$_GET['id'];
	mysqli_query($link,"DELETE FROM tabel_akuntansi_transaksi WHERE kode_jurnal='$id_cancel'");
}

//untuk menyelesaikan transaksi
if(isset($_POST['selesai'])){
	
	$kode_jurnal=$_POST['kode_bukti'];
	$nomor_jurnal=$_POST['nomor_jurnal'];
	$tanggal_selesai=$_POST['tanggal_selesai'];
	$keterangan_invoice=$_POST['keterangan_invoice'];
	$tot_debet=$_POST['tot_debet'];
	$tot_kredit=$_POST['tot_kredit'];
	
	if($tot_debet==$tot_kredit){

		$query_jurnal=mysqli_query($link,"insert into tabel_akuntansi_jurnal_umum(nomor_jurnal,kode_jurnal,kode_invoice,tanggal_selesai) values('$nomor_jurnal','$kode_jurnal','$keterangan_invoice','$tanggal_selesai')");
			
		//fungsi posting otomatis
		//posting_jurnal();
	
		//fungsi hitung SHU otomatis
		//hitung_shu();

		?><script language="javascript">document.location.href="trans-jurnal-umum.php"</script><?php	

	}else{
		?><script language="javascript">document.location.href="trans-jurnal-umum.php?status=Belum Balance"</script><?php	
	}	

}else{
	unset($_POST['selesai']);
}
?>

<?php 
	//jurnal baru. cari nomor paling besar yaitu nomor jurnal terakhir 
	$jurnal_umum=mysqli_fetch_array(mysqli_query($link,"SELECT max(nomor_jurnal) FROM tabel_akuntansi_jurnal_umum ORDER BY tanggal_selesai DESC"));
	$nomor_jurnal=$jurnal_umum[0]+1;
	$kode_jurnal="JU/".$nomor_jurnal;
?>

<form name="postform" action="trans-jurnal-umum.php" method="POST">
<h2>Transaksi >> Transaksi GL &gt;&gt; Jurnal Umum </h2> 
<fieldset style="border:solid 2px #ccc;">
	<legend><strong>FORM 1</strong></legend>
	<table align="left" border=0 cellspacing=0>
	<tr>
		<td valign="top" width=500>
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
					<input type="text" name="kode_jurnal" value="<?php echo $kode_jurnal;?>" readonly>
					</td>
				</tr>
				
				<tr>
					<td class='rounded-foot-left' valign="top">No.Perkiraan</td>
					<td>
						<div id="suggest">
						   <input type="text" onKeyUp="suggest(this.value);" name="nomor_perkiraan" autocomplete="off" onBlur="fill2();" id="kode" size="5"/> 
						   <div class="suggestionsBox" id="suggestions" style="display: none;">
						   <div class="suggestionList" id="suggestionsList"> &nbsp; </div>
						   </div>
						</div>	
						<input type="text" disabled="disabled" onBlur="fill();" id="nama">
					</td>
				</tr>
				
				<tr>
					<td>Keterangan</td>
					<td><input type="text" name="keterangan_transaksi" autocomplete="off" size=20 value="<?php echo $_POST['keterangan_transaksi'];?>"></td>
				</tr>
				
				<tr>
					<td>Jumlah</td>
					<td><input type="text" id="jumlah" name="jumlah" autocomplete="off" value="<?php echo $_POST['jumlah'];?>"></td>
				</tr>  
			
				<?php 
				if($_POST['posisi']=="d"){ 
					$posisi="k";
				}
				
				if($_POST['posisi']=="k"){ 
					$posisi="d";
				}
				?>

				<tr>
					<td>D/K</td>
					<td>
					<input type="text" name="posisi" size="1" value="<?php echo $posisi;?>">
					</td>
				</tr>
				
				<tr>
					<td class='rounded-foot-right' width=45><input type="submit" value="Submit" name="next"></td>
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
	<font color="#FF0000"><?php echo ucwords($_GET['status']);?><blink></blink></font>
	
	<table width=600 id='rounded-corner'>
	<thead>
		<tr>
			<th width="112" class=rounded-company>No.Prk</th>
			<th width="120" class=hr>Nama Perkiraan</th>
			<th width="133" class=hr>Keterangan</th>
			<th width="80" class=hr>Debet</th>
			<th width="82" class=hr>Kredit</th>
		  <th scope="col" class="rounded-q4">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php
		
		$query=mysqli_query($link,"SELECT * FROM tabel_akuntansi_transaksi WHERE kode_jurnal='$kode_jurnal' and id_user='$id_user' ORDER BY tanggal_transaksi asc");
		while($row=mysqli_fetch_array($query)){
			$prk=mysqli_fetch_array(mysqli_query($link,"SELECT nama_perkiraan FROM tabel_akuntansi_master WHERE nomor_perkiraan='$row[nomor_perkiraan]'"));						
		?>
			<tr>
				<td><?php echo $row['nomor_perkiraan']; ?></td>
				<td><?php echo ucwords($prk['nama_perkiraan']); ?></td>
				<td><?php echo ucwords($row['keterangan_transaksi']); ?></td>
				<td align="right"><?php echo number_format($row['debet'],2,'.',','); ?></td>
				<td align="right"><?php echo number_format($row['kredit'],2,'.',','); ?></td>	
				<td><a href='trans-jurnal-umum.php?mode=cancel&id=<?php echo $row['id_transaksi']; ?>' onClick="return confirm('Apakah Anda yakin ?')">cancel</a></td>
			</tr>
		<?php
		}
		
		?>
	</tbody>		  				
	</table><br>
	<a href='trans-jurnal-umum.php?mode=reset&id=<?php echo $kode_jurnal; ?>' onClick="return confirm('Apakah Anda yakin ?')"><font color='blues'>Reset All Jurnal</font></a>
</form>		
<br><br>
</fieldset>

<?php
//untuk lihat balance
$total=mysqli_fetch_array(mysqli_query($link,"SELECT sum(debet) as tot_debet, sum(kredit) as tot_kredit FROM tabel_akuntansi_transaksi WHERE kode_jurnal='$kode_jurnal' AND id_user='$id_user'"));
$tot_debet=$total['tot_debet'];
$tot_kredit=$total['tot_kredit'];

//selisih
$selisih=$tot_debet-$tot_kredit;
if($tot_debet!=0 && $tot_kredit!=0){
	if($tot_debet == $tot_kredit){
		$status="<font color=#0066FF>Balance</font>";
	}else{
		$status="<font color=red><blink>Tidak Balance. Selisih : ".number_format($selisih,0,',','.')."</blink></font>";
	}
}
?>

<fieldset style="border:solid 2px #ccc;">
	<legend><strong>FORM 2</strong></legend>
	<form action="trans-jurnal-umum.php" method="post">
	<table border="0" width="60%">
	<tr>
		<td>Kas</td>
		<td><input type="text" value="<?php echo $tampil_kas; ?> (Kas)" disabled="disabled" size="30"></td>
	</tr>
	<tr>
		<td>Jumlah Debet - Kredit</td>
		<td>
			<input type="text" disabled="disabled"  size="12" value="<?php echo number_format($tot_debet,0,',','.');  ?>"/>&nbsp;
			<input type="text" size="12" disabled="disabled" value="<?php echo number_format($tot_kredit,0,',','.');  ?>"/>			
		</td>
	</tr>
	<tr>
		<td>No.Invoice</td>
		<td><input type="text" name="keterangan_invoice" value="<?php echo $_POST['keterangan_invoice']; ?>" size="30"></td>
	</tr>
	<tr>
		<td>
		<input type="hidden" name="tanggal_selesai" value="<?php echo $tanggal; ?>">
		<input type="hidden" name="kode_bukti" value="<?php echo $kode_jurnal;?>">
		<input type="hidden" name="nomor_jurnal" value="<?php echo $nomor_jurnal;?>">
		<input type="hidden" name="tot_debet" value="<?php echo $tot_debet;?>">
		<input type="hidden" name="tot_kredit" value="<?php echo $tot_kredit;?>">
		<input type="submit" value="Selesai" onClick="return confirm('Apakah Anda yakin ?')" name="selesai">
		</td>
		<td><?php echo $status;?></td>
	</tr>
	</form>	
	</table>
</fieldset>

<!--  PopCalendar(tag name and id must match) Tags should not be enclosed in tags other than the html body tag. -->
<iframe width=174 height=189 name="gToday:normal:lib_calender/agenda.js" id="gToday:normal:lib_calender/agenda.js" src="lib_calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>

<script>
$(document).ready(function(){
	$("#jumlah").change(function(){
		tampil_nominal();
    });	
	
	$("#jumlah").keyup(function(){
		tampil_nominal();
    });
});

function tampil_nominal(){
	var jml=$("#jumlah").val();
	var konversi=kurensi(jml);
	$('#jumlah').val(konversi);
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