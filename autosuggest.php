<?php
   include "koneksi.php";
   
   $db = new mysqli($server,$user,$password,$database);
	
	if(!$db) {
		echo 'Could not connect to the database.';
	} else {
	
		if(isset($_POST['queryString'])) {
			$queryString = $db->real_escape_string($_POST['queryString']);
			
			if(strlen($queryString) >0) {

				$query = $db->query("SELECT nomor_perkiraan, nama_perkiraan FROM tabel_akuntansi_master WHERE tipe='detail' and nomor_perkiraan LIKE '$queryString%' order by nomor_perkiraan asc");
				
				if($query) {
				echo '<ul>';
					while ($result = $query ->fetch_object()) {
	         			echo '<li onClick="fill(\''.addslashes($result->nama_perkiraan).'\'); fill2(\''.addslashes($result->nomor_perkiraan).'\');">'.$result->nomor_perkiraan.'&nbsp;&nbsp;'.strtoupper($result->nama_perkiraan).'</li>';
	         		
					}
					
					if(empty($result)){
						echo "&nbsp;&nbsp;&nbsp;&nbsp;Nomor Perkiraan ini belum ada<br>";
					}
				echo '</ul>';
					
				} else {
					echo 'OOPS we had a problem :(';
				}
			} else {
				// do nothing
			}
		} else {
			echo 'There should be no direct access to this script!';
		}
	}
?>