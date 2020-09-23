<?php include "koneksi.php"; ?>

<?php include "header.php";?>
<script>
function suggest(inputString){
	if(inputString.length == 0) {
		$('#suggestions').fadeOut();
	} else {
	$('#country').addClass('load');
		$.post("autosuggest-prk.php", {queryString: ""+inputString+""}, function(data){
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

<script type="text/javascript">
function tambah_text() {
	var x = document.getElementById("kelompok");
	var y = document.getElementById("normal");
	getCmb = x.value;

	switch (getCmb) { 
		case 'aktiva': result= 'D'; 
		break; 
		case 'biaya': result= 'D'; 
		break; 
		case 'hutang': result = 'K'; 
		break; 
		case 'modal': result= 'K'; 
		break; 
		case 'pendapatan': result = 'K'; 
		break;
	}	

	y.value = result;
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

</head>
<body onLoad=document.perkiraan.elements['nomor_perkiraan'].focus();>
<h2><left>DATA induk >> DATA induk GL >> PERKIRAAN</left></h2>
<fieldset style="border:solid 2px #ccc;">
	<legend><strong>Account Information</strong></legend>
<?php
//untuk edit
if($_GET['act']=='edit'){
	$id=$_GET['id'];
	$halaman=$_GET['halaman'];
	$query=mysqli_fetch_array(mysqli_query($link,"select * from tabel_akuntansi_master where id_perkiraan='$id'"));
	
	$nomor_perkiraan=$query['nomor_perkiraan'];
	$nama_perkiraan=ucwords($query['nama_perkiraan']);
	$tipe=$query['tipe'];
	$level=$query['level'];
	$induk=$query['induk'];
	$kelompok=$query['kelompok'];
	$saldo_awal=$query['saldo_awal'];
	$normal=$query['normal'];
}

//untuk hapus
if($_REQUEST['act']=='delete')
{
	$query_delete=mysqli_query($link,"delete from tabel_akuntansi_master where id_perkiraan='".$_REQUEST['id']."'");
	
	if($query_delete){
		?><script language="javascript">document.location.href='datainduk-gl-perkiraan.php?status=Berhasil Hapus Perkiraan&halaman=<?php echo $halaman;?>'</script><?php
	}else{
		echo mysql_error();
	}
}

//untuk delete banyak nomor perkiraan
if(isset($_POST['cek'])){
	$cek=$_POST['cek'];
	$jumlahdata= count ($cek);
	for ($i = 0; $i < $jumlahdata; ++$i)
	{
		$hapus=mysqli_query($link,"delete from tabel_akuntansi_master where id_perkiraan='$cek[$i]'");
	}
	
	if($hapus){
		?><script language="javascript">document.location.href='datainduk-gl-perkiraan.php?status=Berhasil Hapus Perkiraan&halaman=<?php echo $halaman;?>'</script><?php
	}else{
		echo mysql_error();
	}
}

//untuk update perkiraan
if(isset($_POST['update']))
{
	$id=$_POST['id'];
	$halaman=$_POST['halaman'];
	
	$nomor_perkiraan=$_POST['nomor_perkiraan'];
	$nama_perkiraan=ucwords($_POST['nama_perkiraan']);
	$tipe=$_POST['tipe'];
	$kelompok=$_POST['kelompok'];
	$saldo_awal=$_POST['saldo_awal'];
	$normal=ucwords($_POST['normal']);
	$level=$_POST['level'];
	$induk=$_POST['induk'];
	
	if($normal=='D' || $normal=='d'){
		$normal="D";
		$awal_debet=$saldo_awal;
		$awal_kredit=0;
	}
	
	if($normal=='K' || $normal=='k'){
		$normal="K";
		$awal_kredit=$saldo_awal;
		$awal_debet=0;
	}
	
	
	$queryEditPerkiraan=mysqli_query($link,"update tabel_akuntansi_master set nomor_perkiraan='$nomor_perkiraan', nama_perkiraan='$nama_perkiraan', tipe='$tipe', induk='$induk', level='$level', kelompok='$kelompok', awal_debet='".str_replace(".","",$awal_debet)."', awal_kredit='".str_replace(".","",$awal_kredit)."', normal='$normal' where id_perkiraan='$id'");
	
	if($queryEditPerkiraan)
	{
		?><script language="javascript">document.location.href='datainduk-gl-perkiraan.php?status=Berhasil Update Perkiraan&halaman=<?php echo $halaman; ?>'</script><?php
	}else{
		echo mysql_error();
	} 
}

//untuk simpan nomor perkiraan
if(isset($_POST['kirim']))
{
	$nomor_perkiraan=$_POST['nomor_perkiraan'];
	$nama_perkiraan=ucwords($_POST['nama_perkiraan']);
	$tipe=$_POST['tipe'];
	$kelompok=$_POST['kelompok'];
	$saldo_awal=str_replace(".","",$_POST['saldo_awal']);
	$normal=ucwords($_POST['normal']);
	$level=$_POST['level'];
	$induk=$_POST['induk'];
	
	/* 
	jika normal debet maka beri keterangan diambil dari sugest atau dalam kondisi tertantu diubah manual dari oleh user   
	masukan saldo awal ke awal debet atau kredit sesuai posisi normal 
	*/
	
	if($normal=='D' || $normal=='d'){
		$normal="D";
		$awal_debet=$saldo_awal;
		$awal_kredit=0;
	}
	
	if($normal=='K' || $normal=='k'){
		$normal="K";
		$awal_kredit=$saldo_awal;
		$awal_debet=0;
	}
	
		$queryAddPerkiraan=mysqli_query($link,"insert into tabel_akuntansi_master(nomor_perkiraan,nama_perkiraan,tipe,induk,level,kelompok,awal_debet,awal_kredit,normal) 
						values ('$nomor_perkiraan', '$nama_perkiraan', '$tipe', '$induk', '$level', '$kelompok', '$awal_debet', '$awal_kredit', '$normal')");
	
	if($queryAddPerkiraan)
	{
		?><script language="javascript">document.location.href='datainduk-gl-perkiraan.php?status=Berhasil Input Perkiraan&halaman=<?php echo $halaman; ?>'</script><?php
	}else{
		echo mysql_error();
	} 
}
?>

<form name="perkiraan" method="post" action="datainduk-gl-perkiraan.php">
<table border="0" width="600">
	<?php	
	if($_GET['act']=='edit'){
		?>
		<input type="hidden" value="<?php echo $id;?>" name="id">
		<input type="hidden" name="update" value="Update"/>
		<input type="hidden" name="halaman" value="<?php echo $halaman;?>"/>
		<?php
	}else{
		?>
		<input type="hidden" name="kirim" value="Kirim"/>
		<?php
	}
	?>
			
	
	<tr>
		<td width="132" valign="top">No.Perkiraan</td>
		<td width="458">
		
		<div id="suggest">
		   <input type="text" onKeyUp="suggest(this.value);" value="<?php echo $nomor_perkiraan;?>" name="nomor_perkiraan"  onBlur="fill2();" id="kode" size="15"/> 
		   <div class="suggestionsBox" id="suggestions" style="display: none;"> <img src="arrow.png" style="position: relative; top: -12px; left: 30px;" alt="upArrow"/>
		   <div class="suggestionList" id="suggestionsList"> &nbsp; </div>
		   </div>
		</div>		
		 
		 </td>	
	</tr>
	<tr>
		<td>Nama Perkiraan</td>
		<td><input type="text" name="nama_perkiraan"  value="<?php echo $nama_perkiraan; ?>" onBlur="fill();" id="nama"  size="30"/></td>
	</tr>
	<tr>
		<td>Tipe</td>
		<td>
			<select name="tipe" id="tipe">
				<option value=general <?php if($query['tipe']=='general'){ echo "selected='selected'"; }?>>GENERAL</option>
				<option value=detail <?php if($query['tipe']=='detail'){ echo "selected='selected'"; }?>>DETAIL</option>
			</select>		
		</td>
	</tr>
	<tr>
		<td>induk</td>
		<td>
			<input type="text" name="induk" value="<?php echo $query['induk'];?>">
		</td>
	</tr>
	<tr>
		<td>Level</td>
		<td>
			<input type="text" name="level" value="<?php echo $query['level'];?>">
		</td>
	</tr>
	<tr>
		<td>kelompok</td>
		<td>
			<select name="kelompok" id="kelompok" onChange="javascript:tambah_text();">
				<option value="aktiva" <?php if($query['kelompok']=='aktiva'){ ?>selected="selected"<?php }?>>AKTIVA</option>
				<option value="hutang" <?php if($query['kelompok']=='hutang'){ ?>selected="selected"<?php }?>>HUTANG</option>
				<option value="modal" <?php if($query['kelompok']=='modal'){ ?>selected="selected"<?php }?>>MODAL</option>
				<option value="pendapatan" <?php if($query['kelompok']=='pendapatan'){ ?>selected="selected"<?php }?>>PENDAPATAN</option>
				<option value="biaya" <?php if($query['kelompok']=='biaya'){ ?>selected="selected"<?php }?>>BIAYA</option>
			</select>		
		</td>
	</tr>
	<tr>
		<td>Saldo Awal</td>
		<td>
		
		<?php
		if($query['normal']=='D'){
			$saldo_awal=$query['awal_debet'];
		}else{
			$saldo_awal=$query['awal_kredit'];
		}
		?>
		
		<input type="text" name="saldo_awal" value="<?php echo $saldo_awal;?>"/>
		
		</td>
	</tr>
	<tr>
		<td>D/K</td>
		<td>
			<input type="text" size="2" name="normal" value="<?php if(empty($normal)){ echo "D"; }else{ echo $normal; }?>" id="normal">					
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<?php	
			if($_GET['act']=='edit'){
				?><input type="submit" name="update" value="Update" onClick="return confirm('Apakah Anda yakin untuk mengubah?')"/><?php
			}else{
				?><input type="submit" name="save" value="Save"/><?php
			}
			?>
			<input type="button" onClick="document.location.href='datainduk-gl-perkiraan.php'" value="Cancel"/>		
		</td>
	</tr>
</table>
</form>
</fieldset>
<p align="left">
<font color="#0066FF">
<?php
	echo $status=htmlentities($_GET['status']);
?>
</font>
</p>

<fieldset style="border:solid 2px #ccc;">
	<legend><strong>Import perkiraan  : </strong></legend>
	<?php
	
	
	if(isset($_POST['upload'])){
		include "./lib_excel/excel_reader.php";

		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
		$baris = $data->rowcount($sheet_index=0);
		
		$sukses = 0;
		$gagal = 0;
		
		for ($i=4; $i<=$baris; $i++)
		{
		  
		  $nomor = $data->val($i, 2);
		  $nama = $data->val($i, 3);
		  $tipe = $data->val($i, 4);
		  $induk = $data->val($i, 5);
		  $level = $data->val($i, 6);
		  $kelompok = $data->val($i, 7);
		  $normal = $data->val($i, 8);
		
		 $tgl_inp=date("Y-m-d H:i:s");
		 if(!empty($nama)){
		 $query=mysqli_query($link,"INSERT INTO tabel_akuntansi_master(nomor_perkiraan, nama_perkiraan, tipe, induk, level, kelompok, normal) VALUES ('$nomor','$nama','$tipe','$induk','$level','$kelompok','$normal')") or die(mysql_error());
		  
		  if ($query) $sukses++;
		  else $gagal++;
		  
		  }
		}
		echo "Proses import data selesai. <a href='?pg=$_GET[pg]'>refresh</a><br><br>";
		echo "<p>Jumlah data yang sukses diimport : ".$sukses."<br>";
		echo "Jumlah data yang gagal diimport : ".$gagal."</p>";
		
	}elseif(isset($_POST['truncate'])){
		
		$trun=mysqli_query($link,"TRUNCATE TABLE tabel_akuntansi_master") or die("gagal 88");
		if($trun){
		echo"Truncate data berhasil &nbsp;<a href='?pg=$_GET[pg]'>refresh</a>";
		}else{
		echo"Truncate data gagal &nbsp;<a href='?pg=$_GET[pg]'>refresh</a>";
		}
	}else{
	?>
	<form name="upload" enctype="multipart/form-data" action="datainduk-gl-perkiraan.php" id="upload" method="post" />
	
		<input type="hidden" name="div" value="<?php echo $_GET['ref'];?>">
		<table width=auto>
		<tr>
			<td >Browse Excel</td><td><input size=50 type="file" name="userfile" > <input type="submit" onClick="return confirm('Apakah Anda yakin?')" name="upload" Value="Upload"> <input type="submit" name="truncate" Value="Truncate" onClick="return confirm('Apakah anda yakin akan mengosongkan tabel master?')"> </td>
		</tr>
		</table>
	</form>
	<?php
	}
	?>
</fieldset>
<br>

<fieldset style="border:solid 2px #ccc;">
	<legend><strong>Daftar Perkiraan</strong></legend>
	<script language="javascript">
	function pilihan(pilih){
		if(pilih!='0'){
			location.replace("datainduk-gl-perkiraan.php?filter="+pilih);
		}else{
			location.replace("datainduk-gl-perkiraan.php");
		}
	}
	</script>
	<p align="left">
	<select onChange="pilihan(this.value)">
	   <option value="Pilih Perkiraan" selected="selected">Pilih Kelompok Perkiraan</option>
	   <option value="1" <?php if(isset($_GET['filter']) && $_GET['filter']==1){ echo "selected";} ?>>1 (Aktiva)</option>
	   <option value="2" <?php if(isset($_GET['filter']) && $_GET['filter']==2){ echo "selected";} ?>>2 (Aktiva)</option>
	   <option value="3" <?php if(isset($_GET['filter']) && $_GET['filter']==3){ echo "selected";} ?>>3 (Aktiva)</option>
	   <option value="4" <?php if(isset($_GET['filter']) && $_GET['filter']==4){ echo "selected";} ?>>4 (HUTANG)</option>
	   <option value="5" <?php if(isset($_GET['filter']) && $_GET['filter']==5){ echo "selected";} ?>>5 (Modal)</option>
	   <option value="6" <?php if(isset($_GET['filter']) && $_GET['filter']==6){ echo "selected";} ?>>6 (Pendapatan)</option>
	   <option value="7" <?php if(isset($_GET['filter']) && $_GET['filter']==7){ echo "selected";} ?>>7 (Biaya)</option>
	   <option value="8" <?php if(isset($_GET['filter']) && $_GET['filter']==8){ echo "selected";} ?>>8 (Biaya)</option>
	   <option value="0" <?php if(isset($_GET['filter']) && $_GET['filter']==0){ echo "selected";} ?>>Tampil Semua</option>
	</select>
	</p>
			
	<form IdPrk="form1" name="form1" method="post" class="niceform" action="datainduk-gl-perkiraan.php">
	<table width="100%" id='rounded-corner'>
	<thead>
		<tr>
			<th width="59" align="left" class=rounded-company><div align="left">Check</div></th>
			<th class=hr><div align="left">No.Prk</div></th>
			<th class=hr><div align="left">Nama Perkiraan</div></th>
			<th class=hr><div align="left">Tipe</div></th>
			<th class=hr><div align="left">Induk</div></th>
			<th class=hr><div align="left">Level</div></th>
			<th class=hr><div align="left">Kelompok</div></th>
			<th class=hr><div align="left">Awal Debet</div></th>
			<th class=hr><div align="left">Awal Kredit</div></th>
			<th class=hr><div align="center">Normal</div></th>
			<th class="rounded-q4"><div align="left">Action</div></th>
		</tr>
		</thead>
		
		<tbody>	
		<?php
			if(isset($_GET['filter'])){
				$filter=$_GET['filter'];
				$kondisi="where nomor_perkiraan like '$filter%' ";
				$limit=50;
			}else{
				$kondisi="";
				$limit=25;
			}
			
			$no=1;
			
			$halaman= $_GET['halaman'];
			if(empty($halaman))
			{
				$offset=0;
				$halaman=1;
			}
			else
			{
				$offset= ($halaman-1) * $limit ;
			}
		
			$cari="select * from tabel_akuntansi_master $kondisi order by nomor_perkiraan asc limit $offset,$limit";
			$cari2=mysqli_query($link,$cari);
			$tot1="select count(id_perkiraan) as tot from tabel_akuntansi_master $kondisi";
			$tot2=mysqli_query($link,$tot1);
			$tot3=mysqli_fetch_array($tot2);
			$total=mysqli_num_rows($cari2);
			
			while($tampil2=mysqli_fetch_array($cari2))
			{
				?>
				<tr>
					<td height="24">
					<div align="left">
					<input type="checkbox" name="cek[]" value="<?php echo $tampil2['id_perkiraan']; ?>"/>
					</div></td>
					<td align="left"><?php echo ucwords($tampil2['nomor_perkiraan']); ?></td>
					<td align="left"><?php echo ucwords($tampil2['nama_perkiraan'])?></td>
					<td><?php echo ucwords($tampil2['tipe']); ?></td>
					<td align="left"><?php echo $tampil2['induk']; ?></td>
					<td align="left"><?php echo $tampil2['level']; ?></td>
					<td align="left"><?php echo ucwords($tampil2['kelompok']); ?></td>
					<td align="left"><div align="right"><?php echo number_format($tampil2['awal_debet'],2,'.',','); ?></div></td>
					<td align="left"><div align="right"><?php echo number_format($tampil2['awal_kredit'],2,'.',','); ?></div></td>
					<td align="center"><?php echo ucwords($tampil2['normal']); ?></td>
					<td align="left" width="100">
					<a href=datainduk-gl-perkiraan.php?id=<?php echo $tampil2['id_perkiraan']; ?>&act=edit&halaman=<?php echo $halaman;?>><img src=images/user_edit.png alt="Edit" title="Edit" border=0/></a><a href=datainduk-gl-perkiraan.php?id=<?php echo $tampil2['id_perkiraan']; ?>&act=delete&halaman=<?php echo $halaman;?> class=ask onClick="return confirm('Apakah Anda yakin akan menghapus : <?php echo"$tampil2[nama_perkiraan]"; ?> ?')"><img src=images/trash.png alt=Delete title=Delete border=0/></a></td>
				</tr>
				<div id="paging">
				<?php 
				$no++; 
			}
			
			$tampil3=mysqli_query($link,"select * from tabel_akuntansi_master $kondisi order by nomor_perkiraan asc");
			$jumbaris=mysqli_num_rows($tampil3);
			$total_halaman=ceil($jumbaris/$limit);
			if(!empty($halaman) && $halaman !=1)
			{
				$previous=$halaman-1;
				echo "<a href=datainduk-gl-perkiraan.php?halaman=$previous>Previous</a>";
				echo " - ";
			}
			else
			{
				echo "Previous - ";
			}
			for ($i=1;$i<=$total_halaman;$i++)
			if($i !=$halaman)
			{
				echo "<a href=datainduk-gl-perkiraan.php?halaman=$i>$i</a>";
				echo " | ";
			}
			else
			{
				echo "$i - ";
			}
			if ($halaman<$total_halaman)
			{
				$next=$halaman + 1;
				echo "<a href=datainduk-gl-perkiraan.php?halaman=$next>Next</a>";
			}
			else 
			{
				echo "Next";
			}
		?>
		</div>
		<br/>
		<tfoot>
		<tr>
			<td colspan="11" class="rounded-foot-left">
				<div align="left">
				<input name="delete" type="submit" id="delete" value="Delete" onClick="return confirm('Apakah Anda yakin akan menghapus data ini ?')"/></div><em style="float:left;"></em>			</td>
		</tr>
		<tr>
			<td colspan="11" class="rounded-foot-right">
		  <div align="right">Total Daftar Perkiraan <strong><?php echo $total;?></strong> dari <strong><?php echo $tot3['tot'];?></strong> Daftar Perkiraan</div>			</td>
		</tr>
		</tfoot>	
		</tbody>
	</table>
</form>
</fieldset>
<script>
$(document).ready(function(){
	$("#saldo_awal").change(function(){
		tampil_nominal();
    });	
	
	$("#saldo_awal").keyup(function(){
		tampil_nominal();
    });
	tampil_nominal();
});

function tampil_nominal(){
	var jml=$("#saldo_awal").val();
	var konversi=kurensi(jml);
	$('#saldo_awal').val(konversi);
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
<?php include "footer.php";?>

