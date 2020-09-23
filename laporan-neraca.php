<?php include "koneksi.php"; ?>

<?php include "header.php"; ?>
<style type="text/css">
<!--
.style1 {
color: #0066FF;
font-weight: bold;
}
-->
</style>

<div>
<h2>Laporan >> Neraca </h2> 
<fieldset style="border:solid 2px #ccc;">
<legend><strong>AKTIVA </strong></legend>
<br/>	

<!----------------------------AKTIVA--------------------------------->
<table align="left">
<tr>
	<td align="center" colspan="3">
	<table id='rounded-corner' width="960" border="0">
	<thead>
		<tr>
			<th width="100" class=rounded-company ><div align="center">No.Prk</div></th>
			<th class=hr width="250" ><div align="center">Nama Perkiraan</div></th>
			<th width="100" class=hr><div align="right">DEBET</div></th>
			<th class="rounded-q4" width="100" ><div align="right">KREDIT</div></th>
		</tr>
	</thead>
	<tbody>
		
		<tr>
			<td><div align="right"><strong>#</strong></div></td>
			<td colspan="4"><strong>AKTIVA LANCAR</strong></td>			
		</tr>		
		<!----------------------------ASET LANCAR-------------------------------->
		<?php 
		$q_lancar=mysqli_query($link,"select * from tabel_akuntansi_master where tipe='detail' AND nomor_perkiraan between '".$aktivalancar_start."' and '".$aktivalancar_end."' order by nomor_perkiraan asc");
		$sum_lancar=mysqli_fetch_array(mysqli_query($link,"select sum(nrc_debet) as debet, sum(nrc_kredit) as kredit from tabel_akuntansi_master where tipe='detail' AND nomor_perkiraan between '".$aktivalancar_start."' and '".$aktivalancar_end."'"));
		$lancar_debet=$sum_lancar['debet'];
		$lancar_kredit=$sum_lancar['kredit'];
		while($row_lancar=mysqli_fetch_array($q_lancar)){
		?>
		<tr>
			<td><div align="right"><?php echo $row_lancar['nomor_perkiraan'];?></div></td>
			<td><?php echo $row_lancar['nama_perkiraan'];?></td>
			<td align="right"><?php echo number_format($row_lancar['nrc_debet'],2,'.',','); ?></td>
			<td align="right"><?php echo number_format($row_lancar['nrc_kredit'],2,'.',','); ?></td>				
		</tr>
		<?php
		}
		?>
		<tr>
			<td><div align="right"></div></td><td><strong>TOTAL AKTIVA LANCAR</strong></td>
			<td align="right"><strong><?php echo number_format($tot_lancar=$lancar_debet-$lancar_kredit,2,'.',','); ?></strong></td>
			<td align="right"><strong></strong></td>					
		</tr>

		<tr>
			<td><div align="right"><strong>#</strong></div></td>
			<td colspan="4"><strong>AKTIVA TETAP</strong></td>			
		</tr>
		<?php //maksimum sampai nomor perkiraan 413 untuk pentetapan usaha
		$q_tetap=mysqli_query($link,"select * from tabel_akuntansi_master where tipe='detail' AND nomor_perkiraan between '".$aktivatetap_start."' and '".$aktivatetap_end."' order by nomor_perkiraan asc");
		$sum_tetap=mysqli_fetch_array(mysqli_query($link,"select sum(nrc_debet) as debet, sum(nrc_kredit) as kredit from tabel_akuntansi_master where tipe='detail' AND nomor_perkiraan between '".$aktivatetap_start."' and '".$aktivatetap_end."'"));
		$tetap_debet=$sum_tetap['debet'];
		$tetap_kredit=$sum_tetap['kredit'];
		while($row_tetap=mysqli_fetch_array($q_tetap)){
		?>
		<tr>
			<td><div align="right"><?php echo $row_tetap['nomor_perkiraan'];?></div></td>
			<td><?php echo $row_tetap['nama_perkiraan'];?></td>
			<td align="right"><?php echo number_format($row_tetap['nrc_debet'],2,'.',','); ?></td>
			<td align="right"><?php echo number_format($row_tetap['nrc_kredit'],2,'.',','); ?></td>				
		</tr>
		<?php
		}
		?>
		<tr>
			<td><div align="right"></div></td><td><strong>TOTAL AKTIVA TETAP</strong></td>
			<td align="right"><strong><?php echo number_format($tot_tetap=$tetap_debet-$tetap_kredit,2,'.',','); ?></strong></td>
			<td align="right"><strong></strong></td>					
		</tr>

	</tbody>
	<tfoot>
		<tr>
			<td class='rounded-foot-left' colspan="2"><span class="style1">TOTAL AKTIVA</span></td>   
			<td><div align="right"><strong><?php echo number_format($tot_lancar+$tot_tetap,2,'.',','); ?></strong></div></td>
			<td class='rounded-foot-right'><div align="right"><strong></strong></div></td>
		</tr>
	</table>
	</tfoot>
	</td>
</tr>
</table>
</fieldset>
</div>
<br/>

<div>
<fieldset style="border:solid 2px #ccc;">
<legend><strong>PASIVA</strong></legend>
<br/>
<!----------------------------PASIVA--------------------------------->
<table align="left">
<tr>
	<td align="center" colspan="3">
	<table id='rounded-corner' width="960" border="0">
	<thead>
		<tr>
			<th width="100" class=rounded-company ><div align="center">No.Prk</div></th>
			<th class=hr width="250" ><div align="center">Nama Perkiraan</div></th>
			<th width="100" class=hr><div align="right">DEBET</div></th>
			<th class="rounded-q4" width="100" ><div align="right">KREDIT</div></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><div align="right"><strong>#</strong></div></td>
			<td colspan="4"><strong>KEWAJIBAN</strong></td>			
		</tr>		
		<!----------------------------KEWAJIBAN-------------------------------->
		<?php //maksimum sampai nomor perkiraan 413 untuk penlancaran usaha
		$q_wjb_lancar=mysqli_query($link,"select * from tabel_akuntansi_master where tipe='detail' AND nomor_perkiraan between '".$kewajiban_start."' and '".$kewajiban_end."' order by nomor_perkiraan asc");
		$sum_wjb_lancar=mysqli_fetch_array(mysqli_query($link,"select sum(nrc_debet) as debet, sum(nrc_kredit) as kredit from tabel_akuntansi_master where tipe='detail' AND nomor_perkiraan between '".$kewajiban_start."' and '".$kewajiban_end."'"));
		$wjb_lancar_debet=$sum_wjb_lancar['debet'];
		$wjb_lancar_kredit=$sum_wjb_lancar['kredit'];
		while($row_wjb_lancar=mysqli_fetch_array($q_wjb_lancar)){
		?>
		<tr>
			<td><div align="right"><?php echo $row_wjb_lancar['nomor_perkiraan'];?></div></td>
			<td><?php echo $row_wjb_lancar['nama_perkiraan'];?></td>
			<td align="right"><?php echo number_format($row_wjb_lancar['nrc_debet'],2,'.',','); ?></td>
			<td align="right"><?php echo number_format($row_wjb_lancar['nrc_kredit'],2,'.',','); ?></td>				
		</tr>
		<?php
		}
		?>
		<tr>
			<td><div align="right"></div></td>
			<td><strong>TOTAL KEWAJIBAN</strong></td>
			<td align="right"><strong></strong></td>
			<td align="right"><strong><?php echo number_format($tot_wjb_lancar=$wjb_lancar_kredit-$wjb_lancar_debet,2,'.',','); ?></strong></td>					
		</tr>
		<tr>
			<td><div align="right"><strong>#</strong></div></td>
			<td colspan="4"><strong>MODAL</strong></td>			
		</tr>		
		<!----------------------------KEWAJIBAN-------------------------------->
		<?php //maksimum sampai nomor perkiraan 413 untuk penkekayaanan usaha
		$q_wjb_kekayaan=mysqli_query($link,"select * from tabel_akuntansi_master where tipe='detail' AND nomor_perkiraan between '".$modal_start."' and '".$modal_end."' order by nomor_perkiraan asc");
		$sum_wjb_kekayaan=mysqli_fetch_array(mysqli_query($link,"select sum(nrc_debet) as debet, sum(nrc_kredit) as kredit from tabel_akuntansi_master where tipe='detail' AND nomor_perkiraan between '".$modal_start."' and '".$modal_end."'"));
		$wjb_kekayaan_debet=$sum_wjb_kekayaan['debet'];
		$wjb_kekayaan_kredit=$sum_wjb_kekayaan['kredit'];
		while($row_wjb_kekayaan=mysqli_fetch_array($q_wjb_kekayaan)){
		?>
		<tr>
			<td><div align="right"><?php echo $row_wjb_kekayaan['nomor_perkiraan'];?></div></td>
			<td><?php echo $row_wjb_kekayaan['nama_perkiraan'];?></td>
			<td align="right"><?php echo number_format($row_wjb_kekayaan['nrc_debet'],2,'.',','); ?></td>
			<td align="right"><?php echo number_format($row_wjb_kekayaan['nrc_kredit'],2,'.',','); ?></td>				
		</tr>
		<?php
		}
		
		//cari pendapatan
		$sum_dapat=mysqli_fetch_array(mysqli_query($link,"select sum(rl_debet) as debet, sum(rl_kredit) as kredit from tabel_akuntansi_master where tipe='detail' AND nomor_perkiraan between '".$pendapatan_start."' and '".$pendapatan_end."'"));
		$dapat_debet=$sum_dapat['debet'];
		$dapat_kredit=$sum_dapat['kredit'];
		$dapat_kredit_non = 0;
		$total_pendapatan=$dapat_kredit+$dapat_kredit_non;
		
		//cari beban biaya
		$sum_beban_biaya=mysqli_fetch_array(mysqli_query($link,"select sum(rl_debet) as debet, sum(rl_kredit) as kredit from tabel_akuntansi_master where nomor_perkiraan between '".$pengeluaran_start."' and '".$pengeluaran_end."'"));
		$debet_beban_biaya=$sum_beban_biaya['debet'];
		$kredit_beban_biaya=$sum_beban_biaya['kredit'];
		$total_beban_biaya=$debet_beban_biaya;
		
		//cari laba bersih
		$lababersih=$total_pendapatan-$total_beban_biaya;
		$tot_wjb_kekayaan=$wjb_kekayaan_kredit-$wjb_kekayaan_debet;
		$tot_modal=$tot_wjb_kekayaan+$lababersih;
		?>
		<tr>
			<td><div align="right"></div></td>
			<td>Laba Bersih (Laporan Laba Rugi)</td>
			<td align="right">0.00</td>
			<td align="right"><?php echo number_format($lababersih,2,'.',','); ?></td>		
		</tr>
		<tr>
			<td><div align="right"></div></td>
			<td><strong>TOTAL MODAL</strong></td>
			<td align="right"><strong></strong></td>
			<td align="right"><strong><?php echo number_format($tot_modal,2,'.',','); ?></strong></td>					
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td class='rounded-foot-left' colspan="2"><span class="style1">TOTAL PASIVA</span></td>
			<td><div align="right"><strong></strong></div></td>
			<td class='rounded-foot-right'><div align="right"><strong><?php echo number_format($tot_wjb_lancar+$tot_modal,2,'.',','); ?></strong></div></td>
		</tr>
	  </table>
	</tfoot>
	</td>
</tr>
</table>
</fieldset>
</div>

<!--  PopCalendar(tag name and id must match) Tags should not be enclosed in tags other than the html body tag. -->
<iframe width=174 height=189 name="gToday:normal:lib_calender/agenda.js" id="gToday:normal:lib_calender/agenda.js" src="lib_calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
<?php include "footer.php"; ?>