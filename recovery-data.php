<?php include "koneksi.php"; ?>

	<?php include "header.php"; ?>
	<div>
	<h2>Maintenance >> Restore Database </h2> 
	<fieldset style="border:solid 2px #ccc;">
		<legend><strong>RESTORE : </strong></legend>
		
		<table border="0" align="center">
		<tr>
			<td>
			<p align="center"><em>Tahap ini untuk mengembalikan kondisi database saat ini ke kondisi database yang telah dibackup sebelumnya. </em></p>
			<p align="center"><em>Tanggal Recovery : <?php echo $tanggal;?></em></p></td>
		</tr>
		<tr>
			<td><p></p></td>
		</tr>
		<tr>
			<td width="954" align="center">
			<?php 
			include "koneksi.php";
			
			?>
			<form enctype="multipart/form-data" action="recovery-data.php" method="post">
			<table class="datatable" align="center">
			  <tr>
				<td align="center">File Backup Database (*.sql) <input type="file" name="datafile" size="30" id="gambar"/></td>
			  </tr>
			  
			  <tr>
				<td width="71%" align="center"><input type="submit" onclick="return confirm('Apakah Anda yakin akan restore database?')" name="restore" value="Restore Database"/> </td>
			  </tr>
			</table>
			</form>  
			</td>
		</tr>
		<tr>
			<td width="954" align="center">
			<br/>
			<font color="#0066FF">
			
			<?php
			if(isset($_POST['restore'])){
		
				$nama_file=$_FILES['datafile']['name'];
				$ukuran=$_FILES['datafile']['size'];
				
				//periksa jika data yang dimasukan belum lengkap
				if ($nama_file=="")
				{
					echo "Fatal Error";
				}else{
					//definisikan variabel file dan alamat file
					$uploaddir='./data_import/';
					$alamatfile=$uploaddir.$nama_file;
			
					//periksa jika proses upload berjalan sukses
					if (move_uploaded_file($_FILES['datafile']['tmp_name'],$alamatfile))
					{
						
						$filename = './data_import/'.$nama_file.'';
						
						// Temporary variable, used to store current query
						$templine = '';
						// Read in entire file
						$lines = file($filename);
						// Loop through each line
						foreach ($lines as $line)
						{
							// Skip it if it's a comment
							if (substr($line, 0, 2) == '--' || $line == '')
								continue;
						 
							// Add this line to the current segment
							$templine .= $line;
							// If it has a semicolon at the end, it's the end of the query
							if (substr(trim($line), -1, 1) == ';')
							{
								// Perform the query
								mysqli_query($link,$templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br/><br/>');
								// Reset temp variable to empty
								$templine = '';
							}
						}
						echo "Berhasil Restore Database";
					
					}else{
						//jika gagal
						echo "Proses upload gagal, kode error = " . $_FILES['location']['error'];
					}	
				}
		
			}else{
				unset($_POST['restore']);
			}
			?>
			
			</font>
			</td>
		</tr>
		</table>		
	</fieldset>
	</div>
	<?php include "footer.php"; ?>
