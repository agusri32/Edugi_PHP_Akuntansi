<?php
function tampil_bulan($tanggal_transaksi){
	$bulan=substr($tanggal_transaksi,5,2);
	switch($bulan){
		case "01";
			$bulan="januari";
			break;
			
		case "02";
			$bulan="februari";
			break;
			
		case "03";
			$bulan="maret";
			break;
			
		case "04";
			$bulan="april";
			break;
			
		case "05";
			$bulan="mei";
			break;
			
		case "06";
			$bulan="juni";
			break;
			
		case "07";
			$bulan="juli";
			break;
			
		case "08";
			$bulan="agustus";
			break;
			
		case "09";
			$bulan="september";
			break;
			
		case "10";
			$bulan="oktober";
			break;
			
		case "11";
			$bulan="november";
			break;
			
		case "12";
			$bulan="desember";
			break;
	}
	return $bulan;	
}

function posting_jurnal(){
	/////////////////////////HITUNG MUTASI/////////////////////
	$tanggal_posting=$tanggal.$jam;
	
	$query=mysqli_query($link,"select * from tabel_akuntansi_transaksi where keterangan_posting=''");
	while($row=mysqli_fetch_array($query)){
		$nomor_prk=$row['nomor_perkiraan'];
		$debet=$row['debet'];
		$kredit=$row['kredit'];
		$query_2=mysqli_query($link,"update tabel_akuntansi_master set mut_debet=mut_debet+$debet, mut_kredit=mut_kredit+$kredit where nomor_perkiraan='$nomor_prk'");
	}

	if($query){
	
		$query_hitung_sisa=mysqli_query($link,"select  * from tabel_akuntansi_master");

		while($row_hit_sisa=mysqli_fetch_array($query_hitung_sisa)){
			$normal=$row_hit_sisa['normal'];
			$awal_debet=$row_hit_sisa['awal_debet'];
			$awal_kredit=$row_hit_sisa['awal_kredit'];
			$mutasi_debet=$row_hit_sisa['mut_debet'];
			$mutasi_kredit=$row_hit_sisa['mut_kredit'];
			$nomor_perkiraan=$row_hit_sisa['nomor_perkiraan'];
		
			if($normal=="D"){
				$hitung_sisa_debet=($awal_debet+$mutasi_debet)-$mutasi_kredit;

				if($hitung_sisa_debet<0){
					$positif_sisa_kredit=abs($hitung_sisa_debet);
					$update_mutasi=mysqli_query($link,"update tabel_akuntansi_master set sisa_debet=0, sisa_kredit='$positif_sisa_kredit' where nomor_perkiraan='$nomor_perkiraan'");
				}else{
					$update_mutasi=mysqli_query($link,"update tabel_akuntansi_master set sisa_debet='$hitung_sisa_debet', sisa_kredit='0' where nomor_perkiraan='$nomor_perkiraan'");
				}	
			}
			
			if($normal=="K"){
				$hitung_sisa_kredit=($awal_kredit-$mutasi_debet)+$mutasi_kredit;
				
				if($hitung_sisa_kredit<0){
					$positif_sisa_debet=abs($hitung_sisa_kredit);
					$update_mutasi=mysqli_query($link,"update tabel_akuntansi_master set sisa_debet='$positif_sisa_debet', sisa_kredit='0' where nomor_perkiraan='$nomor_perkiraan'");
				}else{
					$update_mutasi=mysqli_query($link,"update tabel_akuntansi_master set sisa_debet=0, sisa_kredit='$hitung_sisa_kredit' where nomor_perkiraan='$nomor_perkiraan'");
				}	
			}
		}
	}else{
		echo mysql_error();
	}
	
	//////////////////////////UBAH STATUS POSTING//////////////////////////////
	$selesai=mysqli_query($link,"update tabel_akuntansi_transaksi set tanggal_posting='$tanggal_posting', keterangan_posting='Post' where keterangan_posting=''");
}

function hitung_shu(){
	///////////////////////// HITUNG SHU /////////////////////
	$master=mysqli_query($link,"select * from tabel_akuntansi_master");
	while($row=mysqli_fetch_array($master)){
		$nomor_perkiraan=$row['nomor_perkiraan'];
		$sisa_debet=$row['sisa_debet'];
		$sisa_kredit=$row['sisa_kredit'];
		$kelompok=$row['kelompok'];
		

		//kelompok neraca
		if($kelompok=='aktiva' || $kelompok=='kewajiban' || $kelompok=='modal'){
			$update=mysqli_query($link,"update tabel_akuntansi_master set nrc_debet='$sisa_debet', nrc_kredit='$sisa_kredit' where nomor_perkiraan='$nomor_perkiraan'");
		}
		
		//kelompok rugi laba
		if($kelompok=='pendapatan' || $kelompok=='biaya'){
			$update=mysqli_query($link,"update tabel_akuntansi_master set rl_debet='$sisa_debet', rl_kredit='$sisa_kredit' where nomor_perkiraan='$nomor_perkiraan'");
		}
	}
	
	//jika sudah selesai update. no.rek SHU tahun berjalan=3225
	if($update){
		$biaya=mysqli_fetch_array(mysqli_query($link,"select sum(rl_debet) as biaya from tabel_akuntansi_master where normal='debet' and kelompok='pendapatan' or kelompok='biaya' and nomor_perkiraan<>'$tampil_shu'"));
		$pendapatan=mysqli_fetch_array(mysqli_query($link,"select sum(rl_kredit) as pendapatan from tabel_akuntansi_master where normal='kredit' and kelompok='pendapatan' or kelompok='biaya'"));
		
		$biaya['biaya'];
		$pendapatan['pendapatan'];
		
		//hitung SHU
		$shu=$pendapatan['pendapatan']-$biaya['biaya'];
	}

	//update SHU yang lama dengan SHU yang baru pada tahun berjalan
	$update_shu=mysqli_query($link,"update tabel_akuntansi_master set rl_debet='$shu', nrc_kredit='$shu' where nomor_perkiraan='$tampil_shu'");
}
?>
