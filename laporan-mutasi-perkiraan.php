<?php include "koneksi.php"; ?>

	<?php include "header.php"; ?>
	<div>
		<h2>Laporan >> Mutasi Perkiraan</h2> 
		<fieldset style="border:solid 2px #ccc;">
			<legend><strong>TRIAL BALANCE </strong></legend>
			<br/>
		<table align="left">
		<tr>
			<td valign="top" colspan="3">
				<table>
				<tr>
					<td width="403"><label style="float:left;padding:10px;">Tanggal</label>
					<form method="post" name="postform" action="laporan-neraca-percobaan.php?ref=query">
						<input type="text" name="tanggal" id="tanggal1" size="11"  value="<?php if(empty($_POST['tanggal'])){ echo $tanggal;}else{ echo $_POST['tanggal']; }?>">
						<a href="javascript:;" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.tanggal);return false;" >
						<img name="popcal" align="absmiddle" src="lib_calender/calender.jpeg" width="34" height="29" border="0" alt=""></a>	
						<input type="submit" value="Tampilkan" name="tampilkan"/>
					</form>
					</td></tr>
				</table>		
			</td>	
		</tr>
		
		<tr>
			<td align="center" colspan="3">
			<?php
			if(isset($_POST['tampilkan'])){
				$tanggal=$_POST['tanggal'];
			}else{
				unset($_POST['tampilkan']);
			}
			
			?>
			<table id='rounded-corner'>
			<thead>
				<tr>
					<th rowspan="2" class=rounded-company>No.Prk</th>
					<th rowspan="2" class=hr>Nama Perkiraan</th>
					<th class=hr ><div align="right">Awal</div></th>
					<th class=hr ><div align="right">Awal</div></th>
					<th class=hr ><div align="right">Mutasi</div></th>
					<th class=hr ><div align="right">Mutasi</div></th>
					<th class=hr ><div align="right">Sisa Saldo</div></th>
					<th class="rounded-q4" ><div align="right">Sisa Saldo</div></th>
				</tr>
				<tr>
					<th ><div align="right">Debet</div></th>
					<th ><div align="right">Kredit</div></th><th ><div align="right">Debet</div></th><th > <div align="right">Kredit</div></th><th ><div align="right">Debet</div></th><th ><div align="right">Kredit</div></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$query_mutasi=mysqli_query($link,"select * from tabel_akuntansi_master order by nomor_perkiraan asc");
				$total=mysqli_fetch_array(mysqli_query($link,"select sum(awal_debet) as tot_awal_debet, sum(awal_kredit) as tot_awal_kredit, sum(mut_debet) as tot_mut_debet,  sum(mut_kredit) as tot_mut_kredit,
									sum(sisa_debet) as tot_sisa_debet, sum(sisa_kredit) as tot_sisa_kredit from tabel_akuntansi_master order by nomor_perkiraan asc"));
				
				while($row_mut=mysqli_fetch_array($query_mutasi)){
				
					$awal_debet=$row_mut['awal_debet'];
					$awal_kredit=$row_mut['awal_kredit'];
					$mutasi_debet=$row_mut['mut_debet'];
					$mutasi_kredit=$row_mut['mut_kredit'];
					$sisa_debet=$row_mut['sisa_debet'];
					$sisa_kredit=$row_mut['sisa_kredit'];
				
					?>
					<tr>
						<td><div align="right"><?php echo $row_mut['nomor_perkiraan'];?></div></td>
						<td><?php echo $row_mut['nama_perkiraan'];?></td>
						<td align="right"><?php echo number_format($awal_debet,2,'.',','); ?></td>
						<td align="right"><?php echo number_format($awal_kredit,2,'.',','); ?></td>	
						<td align="right"><?php echo number_format($mutasi_debet,2,'.',','); ?></td>
						<td align="right"><?php echo number_format($mutasi_kredit,2,'.',','); ?></td>	
						<td align="right"><?php echo number_format($sisa_debet,2,'.',','); ?></td>
						<td align="right"><?php echo number_format($sisa_kredit,2,'.',','); ?></td>					
					</tr>
					<?php
				}
				?>
			</tr>
			</tbody>
			<tfoot>
				<tr>
					<td class='rounded-foot-left' colspan="2"><div align="center"><strong>TOTAL</strong></div></td>
					<td><div align="right"><strong><?php echo number_format($total['tot_awal_debet'],2,'.',','); ?></strong></div></td><td><div align="right"><strong><?php echo number_format($total['tot_awal_kredit'],2,'.',','); ?></strong></div></td>
					<td><div align="right"><strong><?php echo number_format($total['tot_mut_debet'],2,'.',','); ?></strong></div></td><td><div align="right"><strong><?php echo number_format($total['tot_mut_kredit'],2,'.',','); ?></strong></div></td>
					<td><div align="right"><strong><?php echo number_format($total['tot_sisa_debet'],2,'.',','); ?></strong></div></td>
					<td class='rounded-foot-right' ><div align="right"><strong><?php echo number_format($total['tot_sisa_kredit'],2,'.',','); ?></strong></div></td>
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