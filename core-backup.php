<?php include "koneksi.php"; ?>	

<?php 
$file=time().'_backup_data_'.$tanggal.'.sql';
$query=mysqli_query($link,"insert into tabel_akuntansi_history_backup values('','$file','$tanggal','$username')");

//panggil fungsi
backup_tables($server,$user,$password,$database,$file);

?><script language="javascript">document.location.href="history-backup-data.php?status=File Sudah di Backup"</script>

<?php
/* backup the db OR just a table */
function backup_tables($host,$userdb,$passdb,$mydb,$nama_file,$tables = '*')
{
	
	$link = mysql_connect($host,$userdb,$passdb);
	mysql_select_db($mydb,$link);
	
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