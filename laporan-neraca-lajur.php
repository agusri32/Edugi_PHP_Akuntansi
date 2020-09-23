<?php include "koneksi.php"; ?>

	<?php include "header.php"; ?>
	<div>
		<h2>Laporan >> Neraca Lajur </h2> 
		<fieldset style="border:solid 2px #ccc;">
			<legend><strong>WORKSHEET </strong></legend>
			<br/>
			
		<table id='rounded-corner'>
		<thead>
			<tr>
				<th rowspan="2" class=rounded-company>No.Prk</th>
				<th rowspan="2" class=hr>Nama Perkiraan</th>
				<th class=hr ><div align="right">Sisa Saldo</div></th>
				<th class=hr ><div align="right">Sisa Saldo</div></th>
				<th class=hr ><div align="right">Rugi/Laba</div></th>
				<th class=hr ><div align="right">Rugi/Laba</div></th>
				<th class=hr ><div align="right">Neraca</div></th>
				<th class="rounded-q4" ><div align="right">Neraca</div></th>
			</tr>
			<tr>
				<th ><div align="right">Debet</div></th>
				<th ><div align="right">Kredit</div></th><th ><div align="right">Debet</div></th><th > <div align="right">Kredit</div></th><th ><div align="right">Debet</div></th><th ><div align="right">Kredit</div></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$query_mutasi=mysqli_query($link,"select * from tabel_akuntansi_master order by nomor_perkiraan asc");
			$total=mysqli_fetch_array(mysqli_query($link,"select sum(sisa_debet) as tot_sisa_debet, sum(sisa_kredit) as tot_sisa_kredit, sum(rl_debet) as tot_rl_debet,  sum(rl_kredit) as tot_rl_kredit,
								sum(nrc_debet) as tot_nrc_debet, sum(nrc_kredit) as tot_nrc_kredit from tabel_akuntansi_master order by nomor_perkiraan asc"));
			
			while($row_mut=mysqli_fetch_array($query_mutasi)){
			
				$sisa_debet=$row_mut['sisa_debet'];
				$sisa_kredit=$row_mut['sisa_kredit'];
				$rl_debet=$row_mut['rl_debet'];
				$rl_kredit=$row_mut['rl_kredit'];
				$nrc_debet=$row_mut['nrc_debet'];
				$nrc_kredit=$row_mut['nrc_kredit'];
			
				?>
				<tr>
					<td><div align="right"><?php echo $row_mut['nomor_perkiraan'];?></div></td>
					<td><?php echo $row_mut['nama_perkiraan'];?></td>
					<td align="right"><?php echo number_format($sisa_debet,2,'.',','); ?></td>
					<td align="right"><?php echo number_format($sisa_kredit,2,'.',','); ?></td>	
					<td align="right"><?php echo number_format($rl_debet,2,'.',','); ?></td>
					<td align="right"><?php echo number_format($rl_kredit,2,'.',','); ?></td>	
					<td align="right"><?php echo number_format($nrc_debet,2,'.',','); ?></td>
					<td align="right"><?php echo number_format($nrc_kredit,2,'.',','); ?></td>					
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
			?>
			<tr>
				<td><div align="right"></div></td>
				<td>Laba Bersih (Laporan Laba Rugi)</td>
				<td align="right"><?php echo number_format("0.00",2,'.',','); ?></td>
				<td align="right"><?php echo number_format("0.00",2,'.',','); ?></td>	
				<td align="right"><?php echo number_format($lababersih,2,'.',','); ?></td>
				<td align="right"><?php echo number_format("0.00",2,'.',','); ?></td>	
				<td align="right"><?php echo number_format("0.00",2,'.',','); ?></td>
				<td align="right"><?php echo number_format($lababersih,2,'.',','); ?></td>					
			</tr>
		</tbody>
		
		<tfoot>
			<tr>
				<td class='rounded-foot-left' colspan="2"><div align="center"><strong>TOTAL</strong></div></td>
				<td><div align="right"><strong><?php echo number_format($total['tot_sisa_debet'],2,'.',','); ?></strong></div></td>
				<td><div align="right"><strong><?php echo number_format($total['tot_sisa_kredit'],2,'.',','); ?></strong></div></td>
				<td><div align="right"><strong><?php echo number_format($total['tot_rl_debet']+$lababersih,2,'.',','); ?></strong></div></td>
				<td><div align="right"><strong><?php echo number_format($total['tot_rl_kredit'],2,'.',','); ?></strong></div></td>
				<td><div align="right"><strong><?php echo number_format($total['tot_nrc_debet'],2,'.',','); ?></strong></div></td>
				<td class='rounded-foot-right' ><div align="right"><strong><?php echo number_format($total['tot_nrc_kredit']+$lababersih,2,'.',','); ?></strong></div></td>
			</tr>
		</tfoot>
		
	</table>
	</fieldset>
	</div>
	<!--  PopCalendar(tag name and id must match) Tags should not be enclosed in tags other than the html body tag. -->
	<iframe width=174 height=189 name="gToday:normal:lib_calender/agenda.js" id="gToday:normal:lib_calender/agenda.js" src="lib_calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
	</iframe>
	<?php include "footer.php"; ?>