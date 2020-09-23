<?php include "koneksi.php"; ?>
	
	<?php include "header.php";?>
	<body onLoad=document.perkiraan.elements['nomor_perkiraan'].focus();>
	<h2><left>DATA induk >> DATA induk GL >> CETAK PERKIRAAN</left></h2>
	</form>
	<fieldset style="border:solid 2px #ccc;">
	<legend><strong>Daftar Perkiraan</strong></legend>
	<a href="javascript:;" ><img src="./images/pdf-icon.jpeg" width="18" height="18" border="0" onClick="window.open('view_perkiraan_pdf.php','scrollwindow','top=200,left=300,width=800,height=500');"></a>
	<a href="javascript:;" ><img src="./images/excel-icon.jpeg" width="18" height="18" border="0" onClick="window.open('view_perkiraan_excel.php','scrollwindow','top=200,left=300,width=800,height=500');"></a>
	
	<br>
	<form IdPrk="form1" name="form1" method="post" class="niceform" action="datainduk-gl-perkiraan.php">
	
		<table id='rounded-corner'>
			<thead>
				<tr>
					<th width="150" class=rounded-company>No.Prk</th>
					<th class=hr>Nama Perkiraan</th>
					<th class=hr scope="100">Tipe</th>
					<th class=hr>Induk</th>
					<th class=hr>Level</th>
					<th class=hr scope="100">Kelompok</th>
					<th class=hr scope="100"><div align="right">Awal Debet</div></th>
					<th class=hr scope="100"><div align="right">Awal Kredit</div></th>
					<th scope="100" class="rounded-q4"><div align="center">Normal</div></th>
				</tr>
			</thead>
			
			<tbody>	
			
			<?php 
			$result=mysqli_query($link,"select * from tabel_akuntansi_master order by nomor_perkiraan asc");
			while($tampil2=mysqli_fetch_array($result))
			{
			?>
			<tr>
				<td align="left"><?php echo ucwords($tampil2['nomor_perkiraan']); ?></td>
				<td align="left"><?php echo ucwords($tampil2['nama_perkiraan']); ?></td>
				<td>
				
				<?php
					if($tampil2['tipe']=='general')
					{
						echo "General";
					}
					if($tampil2['tipe']=='detail')
					{
						echo "Detail";
					}
				?>			</td>
				<td align="left"><?php echo $tampil2['induk']; ?></td>
				<td align="left"><?php echo $tampil2['level']; ?></td>
				<td align="left"><?php echo ucwords($tampil2['kelompok']); ?></td>
				<td align="left"><div align="right"><?php echo number_format($tampil2['awal_debet'],2,'.',','); ?></div></td>
				<td align="left"><div align="right"><?php echo number_format($tampil2['awal_kredit'],2,'.',','); ?></div></td>
				<td align="left"><div align="center"><?php echo ucwords($tampil2['normal']);?></div></td>
			</tr>
			<?php
			}
			?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan='5' class='rounded-foot-left'><em style='float:left;'>
					<?php 
					$total=mysqli_fetch_array(mysqli_query($link,"select count(nomor_perkiraan) as jumlah_perkiraan, sum(awal_debet) as jumlah_debet, sum(awal_kredit) as jumlah_kredit from tabel_akuntansi_master"));
					$jumlah_prk=$total['jumlah_perkiraan'];
					$jumlah_debet=$total['jumlah_debet'];
					$jumlah_kredit=$total['jumlah_kredit'];
					echo "Ada ".$jumlah_prk." nomor perkiraan";?></em>
					</td>
					<td align="left"><strong>TOTAL</strong></td>
					<td align="left"><div align="right"><strong><?php echo number_format($jumlah_debet,2,'.',','); ?></strong></div></td>
					<td align="left"><div align="right"><strong><?php echo number_format($jumlah_kredit,2,'.',','); ?></strong></div></td>
					<td class='rounded-foot-right' width=65><div align="center"></div></td>
				</tr>
			</tfoot>	
		</table>
	</form>
	</fieldset>
	<?php include "footer.php";?>

