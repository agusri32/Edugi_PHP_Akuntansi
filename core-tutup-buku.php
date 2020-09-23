<?php include "koneksi.php"; ?>	

<?php 	
	$row_setup=mysqli_fetch_array(mysqli_query($link,"select * from tabel_setup_gl_perkiraan"));
	//menampilkan nomor perkiraan khusus
	$tampil_aktiva_lancar=$row_setup['aktiva_lancar'];
	$tampil_hutang_lancar=$row_setup['hutang_lancar'];
	$tampil_modal_sendiri=$row_setup['modal_sendiri'];
	$tampil_kas=$row_setup['kas'];
	$tampil_shu=$row_setup['shu'];
	$tampil_shu_lalu=$row_setup['shu_lalu'];
	
	$file=time().'_tutup_buku_'.$tanggal.'.sql';
	$query=mysqli_query($link,"insert into tabel_akuntansi_history_tutup_buku values('','$file','$tanggal','$id_user')");
	
	//panggil fungsi
	backup_tables($server,$user,$password,$database,$file);
	
	
	// ubah SHU tahun berjalan dengan SHU tahun lalu
	$query_shu=mysqli_fetch_array(mysqli_query($link,"select nrc_kredit from tabel_akuntansi_master where nomor_perkiraan='$tampil_shu'"));
	$shu_berjalan=$query_shu['nrc_kredit'];
	
	$update=mysqli_query($link,"update tabel_akuntansi_master set awal_debet=nrc_debet, awal_kredit=nrc_kredit, mut_debet=0, mut_kredit=0, sisa_debet=0, sisa_kredit=0, rl_debet=0, rl_kredit=0");
	
	$update_shu=mysqli_query($link,"update tabel_akuntansi_master set awal_kredit='$shu_berjalan', nrc_kredit='$shu_kredit' where nomor_perkiraan='$tampil_shu_lalu'");
	$hapus_shu_berjalan=mysqli_query($link,"update tabel_akuntansi_master set awal_kredit=0 where nomor_perkiraan='$tampil_shu'");
	
	mysqli_query($link,"delete from tabel_akuntansi_transaksi"); 
	mysqli_query($link,"delete from tabel_akuntansi_jurnal_masuk");
	mysqli_query($link,"delete from tabel_akuntansi_jurnal_keluar");
	mysqli_query($link,"delete from tabel_akuntansi_jurnal_umum");
?>	
<script language="javascript">document.location.href="history-tutup-buku.php?status=Berhasil Tutup Buku"</script>

<?php
/* backup the db OR just a table */
function backup_tables($server,$user,$password,$database,$nama_file,$tables = '*')
{
	
	$link = mysql_connect($server,$user,$password);
	mysql_select_db($database,$link);
	
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysqli_query($link,'SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
		
	}else{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	
	//cycle through
	foreach($tables as $table)
	{
		$result = mysqli_query($link,'SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		
		$return.= 'DROP TABLE '.$table.';';
		$row2 = mysql_fetch_row(mysqli_query($link,'SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	
	//save file
	$nama_file;
	
	$handle = fopen('./data/'.$nama_file,'w+');
	fwrite($handle,$return);
	fclose($handle);
}
?>