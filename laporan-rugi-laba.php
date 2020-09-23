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
	<h2>Laporan >> Laba Rugi</h2> 
<fieldset style="border:solid 2px #ccc;">
		<legend><strong>LAPORAN </strong></legend>
		<br/>
<table align="left" >
<tr>
	<td align="center" colspan="3">
	<table id='rounded-corner' width="960" border="0">
	<thead>
		<tr>
		  <th width="248" class=rounded-company ><div align="center">No.Prk</div></th>
			<th class=hr width="295" ><div align="center">Uraian</div></th>
		  <th class=hr width="105" ><div align="center">Tipe / Normal</div></th>
		  <th width="155" class=hr><div align="right">Pengeluaran</div></th>
		  <th class="rounded-q4" width="135" ><div align="right">Pendapatan</div></th>
		</tr>
	</thead>
	<tbody>
			<!----------------------------PENDAPATAN--------------------------------->
		
			<tr>
				<td><div align="right">#</div></td>
				<td colspan="5">PENDAPATAN</td>			
			</tr>		
			<?php //maksimum sampai nomor perkiraan 413 untuk pendapatan usaha
			$q_dapat=mysqli_query($link,"select * from tabel_akuntansi_master where tipe='detail' AND nomor_perkiraan between '".$pendapatan_start."' and '".$pendapatan_end."' order by nomor_perkiraan asc");
			$sum_dapat=mysqli_fetch_array(mysqli_query($link,"select sum(rl_debet) as debet, sum(rl_kredit) as kredit from tabel_akuntansi_master where tipe='detail' AND nomor_perkiraan between '".$pendapatan_start."' and '".$pendapatan_end."'"));
			$dapat_debet=$sum_dapat['debet'];
			$dapat_kredit=$sum_dapat['kredit'];
			while($row_dapat=mysqli_fetch_array($q_dapat)){
			$no_prk_dapat=$row_dapat['nomor_perkiraan'];
			$sum_detail_dapat=mysqli_fetch_array(mysqli_query($link,"select sum(rl_debet) as detail_debet, sum(rl_kredit) as detail_kredit from tabel_akuntansi_master where tipe='detail' AND nomor_perkiraan like '$no_prk_dapat%' and nomor_perkiraan between '".$pendapatan_start."' and '".$pendapatan_end."'"));
			?>
			<tr>
				<td><div align="right"><?php echo $row_dapat['nomor_perkiraan'];?></div></td>
				<td><?php echo $row_dapat['nama_perkiraan'];?></td>
				<td><div align="center"><?php echo ucwords($row_dapat['tipe'])." / ".strtoupper($row_dapat['normal']);?></div></td>
				<td align="right"><?php echo number_format($sum_detail_dapat['detail_debet'],2,'.',','); ?></td>
				<td align="right"><?php echo number_format($sum_detail_dapat['detail_kredit'],2,'.',','); ?></td>				
			</tr>
			<?php
			}
			?>
			<tr>
				<td><div align="right"></div></td><td><span class="style1">TOTAL PENDAPATAN</span></td>
				<td><div align="center"></div></td>
				<td align="right"></td>
				<td align="right"><strong><?php echo number_format($total_pendapatan=$dapat_kredit,2,'.',','); ?></strong></td>					
			</tr> 

			<!-----------------------------BIAYA--------------------------------->
			<tr>
			  <td>&nbsp;</td>
			  <td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td><div align="right">#</div></td>
				<td colspan="5">BIAYA</td>			
			</tr>
			<?php
			$q_beban=mysqli_query($link,"select * from tabel_akuntansi_master where tipe='detail' AND nomor_perkiraan between '".$pengeluaran_start."' and '".$pengeluaran_end."'");
			$sum_beban=mysqli_fetch_array(mysqli_query($link,"select sum(rl_debet) as debet, sum(rl_kredit) as kredit from tabel_akuntansi_master where tipe='detail' AND nomor_perkiraan between '".$pengeluaran_start."' and '".$pengeluaran_end."'"));
			$debet_beban=$sum_beban['debet'];
			$kredit_beban=$sum_beban['kredit'];
			while($row_beban=mysqli_fetch_array($q_beban)){
			$no_prk_beban=$row_beban['nomor_perkiraan'];
			$sum_detail_beban=mysqli_fetch_array(mysqli_query($link,"select sum(rl_debet) as detail_debet, sum(rl_kredit) as detail_kredit from tabel_akuntansi_master where tipe='detail' AND nomor_perkiraan like '$no_prk_beban%' and nomor_perkiraan between '".$pengeluaran_start."' and '".$pengeluaran_end."'"));
			?>
			<tr>
				<td><div align="right"><?php echo $row_beban['nomor_perkiraan'];?></div></td>
				<td><?php echo $row_beban['nama_perkiraan'];?></td>
				<td><div align="center"><?php echo ucwords($row_beban['tipe'])." / ".strtoupper($row_beban['normal']);?></div></td>
				<td align="right"><?php echo number_format($sum_detail_beban['detail_debet'],2,'.',','); ?></td>
				<td align="right"><?php echo number_format($sum_detail_beban['detail_kredit'],2,'.',','); ?></td>				
			</tr>
			<?php
			}
			?>
			<tr>
				<td><div align="right"></div></td><td><span class="style1">TOTAL BIAYA</span></td>
				<td><div align="center"></div></td>
				<td align="right"></td>
				<td align="right"><strong><?php echo number_format($total_beban_biaya=$debet_beban,2,'.',','); ?></strong></td>					
			</tr>
			<tr>
				<td colspan="5"></td>					
			</tr>
			
			<!-----------------------------SISA HASIL USAHA--------------------------------->
			<tr>
				<td><div align="right"></div></td><td><strong>LABA BERSIH</strong></td>
				<td><div align="center"></div></td>
				<td align="right"></td>
				<td align="right"><strong><?php echo number_format($lababersih=$total_pendapatan-$total_beban_biaya,2,'.',','); ?></strong></td>					
			</tr>
	</tbody>
	</table>
	</td>
</tr>
</table>
</fieldset>
</div>
<!--  PopCalendar(tag name and id must match) Tags should not be enclosed in tags other than the html body tag. -->
<iframe width=174 height=189 name="gToday:normal:lib_calender/agenda.js" id="gToday:normal:lib_calender/agenda.js" src="lib_calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
<?php include "footer.php"; ?>