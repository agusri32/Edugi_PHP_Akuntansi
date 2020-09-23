<?php include "koneksi.php"; ?>
	
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
</head>

<body onLoad=document.postform.elements['nomor_perkiraan'].focus();>
<div>
<h2>Laporan >> Buku Besar </h2> 
<fieldset style="border:solid 2px #ccc;">
<legend><strong>PERIODE : </strong></legend>

<form name="postform" action="laporan-buku-besar.php" method="POST">
<p></p>
<table >
	<tr>
		<td>No.Perkiraan</td>
		<td>
			<div id="suggest">
			   <input type="text" onKeyUp="suggest(this.value);" name="nomor_perkiraan"  value="<?php echo $_POST['nomor_perkiraan'];?>" onBlur="fill2();" autosuggest="off" id="kode"/> 
			   <div class="suggestionsBox" id="suggestions" style="display: none;">
			   <div class="suggestionList" id="suggestionsList"> &nbsp; </div>
			   </div>
			</div>
		</td>
		<td colspan="2">
			<input type=text name="nama_perkiraan" value="<?php echo $_POST['nama_perkiraan'];?>" onBlur="fill();" id="nama" readonly>
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
<legend><strong>LAPORAN RINCIAN <?php 
if(isset($_POST['tanggal1'])){
	echo "Tgl ".$_POST['tanggal1']; ?> s/d  <?php echo $_POST['tanggal2'];
}
?>
</strong></legend>
<br/>
<font color="#0066FF"><center><?php echo $_GET['status'];?></center></font>
<?php 
if(isset($_POST['tampilkan'])){
	$no_prk=$_POST['nomor_perkiraan'];
	$tanggal1=$_POST['tanggal1'];
	$tanggal2=$_POST['tanggal2'];
	
	if(empty($no_prk)){
		?><script language="javascript">document.location.href="laporan-buku-besar.php?status=No Perkiraan belum disini"</script><?php
	}else{
		?>
		<table id='rounded-corner'>
		<thead>
			<tr>
				<th width="72" class=rounded-company>Tgl.Transaksi</th>
				<th width="72" class=hr>No.Jurnal</th>
				<th width="150" class=hr>Keterangan</th>
				<th width="50" class=hr>Kelompok</th>
				<th width="89" class=hr><div align="right">Debet</div></th>
				<th width="89" class=hr><div align="right">Kredit</div></th>
				<th scope="col" class="rounded-q4">SALDO</th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td></td>
			<td></td>
			<td>SALDO AWAL</td>
			<td colspan="3"></td>
			<td>
			<?php
			$q_saldo=mysqli_fetch_array(mysqli_query($link,"select awal_debet,awal_kredit from tabel_akuntansi_master where nomor_perkiraan='$no_prk'"));

			if($q_saldo['awal_debet']==0){
				$saldo_awal=$q_saldo['awal_debet'];
				echo number_format($saldo_awal,2,'.',',');
			}else{
				$saldo_awal=$q_saldo['awal_debet'];
				echo number_format($saldo_awal,2,'.',',');
			}
			?>
			</td>
		</tr>	      			
		<?php
		
		$query=mysqli_query($link,"select * from tabel_akuntansi_transaksi where tanggal_transaksi between '$tanggal1' and '$tanggal2' and nomor_perkiraan='$no_prk' order by tanggal_transaksi asc");
		$total=mysqli_fetch_array(mysqli_query($link,"select sum(debet) as tot_debet, sum(kredit) as tot_kredit from tabel_akuntansi_transaksi where tanggal_transaksi between '$tanggal1' and '$tanggal2' and nomor_perkiraan='$no_prk' order by nomor_perkiraan asc"));

		while($row=mysqli_fetch_array($query)){
			$tanggal_transaksi=$row['tanggal_transaksi'];
			$kode_jurnal=$row['kode_jurnal'];
			$tipe=$row['jenis_transaksi'];
			$no_prk=$row['nomor_perkiraan'];
			$keterangan=$row['keterangan_transaksi'];
			$debet=$row['debet'];
			$kredit=$row['kredit'];
			$id_user=$row['id_user'];
			
			$query_prk=mysqli_fetch_array(mysqli_query($link,"select nama_perkiraan, kelompok from tabel_akuntansi_master where nomor_perkiraan='$no_prk'"));
			$nama_prk=$query_prk['nama_perkiraan'];
			$kelompok=$query_prk['kelompok'];
			?>
			<tr>
				<td><?php echo substr($tanggal_transaksi,0,10);?></td>
				<td><?php echo $kode_jurnal;?></td>
				<td><?php echo $keterangan;?></td>
				<td><?php echo $kelompok;?></td>
				<td><div align="right"><?php echo number_format($debet,2,'.',','); ?></div></td>
				<td><div align="right"><?php echo number_format($kredit,2,'.',','); ?></div></td>
				<td>
				<?php
				//untuk mengurangi saldo awal dengan transaksi
				if($kelompok=="aktiva" || $kelompok=="biaya"){
					//echo "ini aktiva dan biaya";
					$saldo_awal=$saldo_awal+$debet-$kredit;
					echo number_format($saldo_awal,2,'.',',');
				}else{
					//echo "selain itu";
					$saldo_awal=$saldo_awal+$kredit-$debet;
					echo number_format($saldo_awal,2,'.',',');
				}
				?>
				</td>
			</tr>
			<?php
		}	
		?>
		</tbody>
			
		<tfoot>				
			<tr>
				<td class='rounded-foot-left'></td>
				<td></td>
				<td colspan="2"><b>TOTAL</b></td>
				<td><div align="right"><b><?php echo number_format( $total['tot_debet'],2,'.',','); ?></b></div></td>
				<td><div align="right"><b><?php echo number_format( $total['tot_kredit'],2,'.',','); ?></b></div></td>
				<td class='rounded-foot-right' width=65></td>
			</tr>
		</tfoot>
		</table>
		
<?php
	} //penutup if	
	
}else{
	unset($_POST['tampilkan']);
}
?>
</fieldset>
<br>
<fieldset style="border:solid 2px #ccc;">
<legend><strong>LAPORAN PERBULAN : </strong></legend>
<br/>

<font color="#0066FF"><center><?php echo $_GET['status'];?></center></font>
<?php 
if(isset($_POST['tampilkan'])){
	$no_prk=$_POST['nomor_perkiraan'];
	$tanggal1=$_POST['tanggal1'];
	$tanggal2=$_POST['tanggal2'];
	
	
	if(empty($no_prk)){
		?><script language="javascript">document.location.href="laporan-buku-besar.php?status=No Perkiraan belum disini"</script><?php
	}else{
		?>
		<table width="377" id='rounded-corner'>
		<thead>
		<tr>
			<th width="141" class=rounded-company>Bulan</th>
			<th width="107" class=hr><div align="right">Debet</div></th>
			<th width="113" scope="col" class="rounded-q4"><div align="right">Kredit</div></th>
		</tr>
		</thead>
		<tbody>
		
		<?php
		$query_tanggal_bulan=mysqli_query($link,"select distinct(bulan_transaksi) from tabel_akuntansi_transaksi where tanggal_transaksi between '$tanggal1' and '$tanggal2' order by tanggal_transaksi asc");
		$total_bulan=mysqli_fetch_array(mysqli_query($link,"select sum(debet) as tot_debet, sum(kredit) as tot_kredit from tabel_akuntansi_transaksi where tanggal_transaksi='$tanggal_buku_besar' and nomor_perkiraan='$no_prk' order by tanggal_transaksi asc"));

		while($row_query=mysqli_fetch_array($query_tanggal_bulan)){
			$bulan_transaksi=$row_query['bulan_transaksi'];
			
			$query_per_bulan=mysqli_query($link,"select * from tabel_akuntansi_transaksi where bulan_transaksi='$bulan_transaksi' and nomor_perkiraan='$no_prk' order by tanggal_transaksi asc");
			$total_per_bulan=mysqli_fetch_array(mysqli_query($link,"select sum(debet) as tot_debet, sum(kredit) as tot_kredit from tabel_akuntansi_transaksi where bulan_transaksi='$bulan_transaksi' and nomor_perkiraan='$no_prk'"));

			while($row_bulan=mysqli_fetch_array($query_per_bulan)){
			
				$tanggal_transaksi_bulan=$row_bulan['tanggal_transaksi'];
				$kode_jurnal_bulan=$row_bulan['kode_jurnal'];
				$tipe_bulan=$row_bulan['jenis_transaksi'];
				$no_prk_bulan=$row_bulan['nomor_perkiraan'];
				$keterangan_bulan=$row_bulan['keterangan_transaksi'];
				$debet_bulan=$row_bulan['debet'];
				$kredit_bulan=$row_bulan['kredit'];
				$id_user_bulan=$row_bulan['id_user'];
				
				$query_prk_bulan=mysqli_fetch_array(mysqli_query($link,"select nama_perkiraan, kelompok from tabel_akuntansi_master where nomor_perkiraan='$no_prk'"));
					$nama_prk_bulan=$query_prk_bulan['nama_perkiraan'];
					$kelompok_bulan=$query_prk_bulan['kelompok'];
				/*
				?>
				<tr>
					<td><?php echo substr($tanggal_transaksi_bulan,0,10);?></td>
					<td><?php echo $kode_jurnal_bulan;?></td>
					<td><?php echo $keterangan_bulan;?></td>
					<td><?php echo $kelompok_bulan;?></td>
					<td><div align="right"><?php echo number_format($debet_bulan,2,'.',','); ?></div></td>
					<td><div align="right"><?php echo number_format($kredit_bulan,2,'.',','); ?></div></td>
				</tr>
				<?php
				*/
			}
			?>
			<tr>
				<td class='rounded-foot-left' width="141">    
				
				BULAN 
				
				<?php
				$str_tanggal=$tanggal_transaksi_bulan;
				$bulan=substr($str_tanggal,5,2);
				
				switch($bulan){
					case "01";
						$bulan="Januari";
						break;
						
					case "02";
						$bulan="Februari";
						break;
						
					case "03";
						$bulan="Maret";
						break;
						
					case "04";
						$bulan="April";
						break;
						
					case "05";
						$bulan="Mei";
						break;
						
					case "06";
						$bulan="Juni";
						break;
						
					case "07";
						$bulan="Juli";
						break;
						
					case "08";
						$bulan="Agustus";
						break;
						
					case "09";
						$bulan="September";
						break;
						
					case "10";
						$bulan="Oktober";
						break;
						
					case "11";
						$bulan="November";
						break;
						
					case "12";
						$bulan="Desember";
						break;
				}	
				
				echo strtoupper($bulan);
				?>
			  </td>
			  <td><div align="right"><?php echo number_format( $total_per_bulan['tot_debet'],2,'.',','); ?></div></td>
			  <td class='rounded-foot-right'><div align="right"><?php echo number_format( $total_per_bulan['tot_kredit'],2,'.',','); ?></div></td>
		  </tr>
		<?php
		}
		?>
		</tbody>	
		<tfoot>				
			<tr>
				<td class='rounded-foot-left'><b>TOTAL</b></td>
				<td><div align="right"><b><?php echo number_format( $total['tot_debet'],2,'.',','); ?></b></div></td>
				<td class='rounded-foot-right' width=113><div align="right"><b><?php echo number_format( $total['tot_kredit'],2,'.',','); ?></b></div></td>
		  </tr>
		</tfoot>
		</table>
		
<?php
	} //penutup if	
	
}else{
	unset($_POST['tampilkan']);
}
?>
</fieldset>

</div>
<!--  PopCalendar(tag name and id must match) Tags should not be enclosed in tags other than the html body tag. -->
<iframe width=174 height=189 name="gToday:normal:lib_calender/agenda.js" id="gToday:normal:lib_calender/agenda.js" src="lib_calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
<?php include "footer.php"; ?>
