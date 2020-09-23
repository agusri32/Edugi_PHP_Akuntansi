<?php include "koneksi.php"; ?>

	<?php include "header.php"; ?>
	<div>
	<h2>Laporan >> Hitung Laba Rugi </h2> 
	<fieldset style="border:solid 2px #ccc;">
		<legend><strong>Laba Rugi : </strong></legend>	
		<p align="center" class="style1"><font color="#666666">
			Proses ini adalah proses untuk menghasilkan laporan keuangan yaitu menghitung Laba Rugi. </font></p>
			<p align="center"><em><font color="#666666">Proses bisa dilakukan setelah semua data diposting.
			<?php 
			$cek=mysqli_query($link,"select * from tabel_akuntansi_transaksi where keterangan_posting=''");
			$cek_posting=mysqli_num_rows($cek);
			if($cek_posting!==0){
				echo "Saat ini masih ada yang belum diposting.";
			}else{
				?>
				</font></em></p>
				<form action="laporan-hitung-shu.php" method="post" name="postform">
					<div align="center">
						<input type="submit" onclick="return confirm('Apakah Anda Yakin?')" name="hitung_shu" value="PROSES HITUNG"/>
					</div>
				</form>
				<font color="#666666">
				<div align="center"></div>
				<?php
			}
			?>
			</font>
			<div align="center"><font color="#0066FF">
			<?php echo $berhasil;?>		  
			</font>
		  </p>
		</p>
	</div>

	<?php
	if(isset($_POST['hitung_shu'])){
		
		///////////////////////// HITUNG SHU /////////////////////
		$master=mysqli_query($link,"select * from tabel_akuntansi_master");
		while($row=mysqli_fetch_array($master)){
			$nomor_perkiraan=$row['nomor_perkiraan'];
			$sisa_debet=$row['sisa_debet'];
			$sisa_kredit=$row['sisa_kredit'];
			$kelompok=$row['kelompok'];

			//kelompok neraca
			if($kelompok=='aktiva' || $kelompok=='hutang' || $kelompok=='modal'){
				$update=mysqli_query($link,"update tabel_akuntansi_master set nrc_debet='$sisa_debet', nrc_kredit='$sisa_kredit' where nomor_perkiraan='$nomor_perkiraan'");
			}
			
			//kelompok rugi laba
			if($kelompok=='pendapatan' || $kelompok=='biaya'){
				$update=mysqli_query($link,"update tabel_akuntansi_master set rl_debet='$sisa_debet', rl_kredit='$sisa_kredit' where nomor_perkiraan='$nomor_perkiraan'");
			}
		}
		
		/*
		//jika sudah selesai update. no.rek SHU tahun
		if($update){
			$biaya=mysqli_fetch_array(mysqli_query($link,"select sum(rl_debet) as biaya from tabel_akuntansi_master where normal='D' and kelompok='pendapatan' or kelompok='biaya' and nomor_perkiraan<>'$tampil_shu'"));
			$pendapatan=mysqli_fetch_array(mysqli_query($link,"select sum(rl_kredit) as pendapatan from tabel_akuntansi_master where normal='K' and kelompok='pendapatan' or kelompok='biaya'"));
			
			$biaya['biaya'];
			$pendapatan['pendapatan'];
			
			//hitung SHU
			$shu=$pendapatan['pendapatan']-$biaya['biaya'];
		}
	
		//update SHU yang lama dengan SHU yang baru pada tahun berjalan
		$update_shu=mysqli_query($link,"update tabel_akuntansi_master set rl_debet='$shu', nrc_kredit='$shu' where nomor_perkiraan='$tampil_shu'");
		*/
		
		if($update){
			?><center><font color="#0066FF">Neraca dan Rugi Laba Berhasil dihitung</font></center><?php
		}else{
			echo mysql_error();
		}
	
	}else{
		unset($_POST['hitung_shu']);
	}
	?>
	</fieldset>
	</div>
	<?php include "footer.php"; ?>
