<?php
include "koneksi.php";
include "./lib_excel/excel_reader.php";

$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
$baris = $data->rowcount($sheet_index=0);

$sukses = 0;
$gagal = 0;

for ($i=4; $i<=$baris; $i++)
{
  
  $nomor = $data->val($i, 2);
  $nama = $data->val($i, 3);
  $tipe = $data->val($i, 4);
  $induk = $data->val($i, 5);
  $level = $data->val($i, 6);
  $kelompok = $data->val($i, 7);
  $normal = $data->val($i, 8);

 $tgl_inp=date("Y-m-d H:i:s");
 if(!empty($nama)){
 $query=mysqli_query($link,"INSERT INTO tabel_akuntansi_master(nomor_perkiraan, nama_perkiraan, tipe, induk, level, kelompok, normal) VALUES ('$nomor','$nama','$tipe','$induk','$level','$kelompok','$normal')") or die(mysql_error());
  
  if ($query) $sukses++;
  else $gagal++;
  
  }
}
echo "<h3>Proses import data selesai.</h3><br>";
echo "<p>Jumlah data yang sukses diimport : ".$sukses."<br>";
echo "Jumlah data yang gagal diimport : ".$gagal."</p>";
?>
 