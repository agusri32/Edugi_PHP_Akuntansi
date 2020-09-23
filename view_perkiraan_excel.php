<?php 
function xlsBOF() {
	echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
	return;
}

function xlsEOF() {
	echo pack("ss", 0x0A, 0x00);
	return;
}

function xlsWriteNumber($Row, $Col, $Value) {
	echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
	echo pack("d", $Value);
	return;
}

function xlsWriteLabel($Row, $Col, $Value ) {
	$L = strlen($Value);
	echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
	echo $Value;
	return;
}

include "koneksi.php";

$queabsdetail = "SELECT * FROM tabel_akuntansi_master order by nomor_perkiraan asc";
$exequeabsdetail = mysqli_query($link,$queabsdetail);
while($res = mysqli_fetch_array($exequeabsdetail)){

	$data['id_perkiraan'][] = $res['id_perkiraan'];
	$data['nomor_perkiraan'][] = $res['nomor_perkiraan'];
	$data['nama_perkiraan'][] = $res['nama_perkiraan'];
	$data['tipe'][] = $res['tipe'];
	$data['induk'][] = $res['induk'];
	$data['level'][] = $res['level'];
	$data['kelompok'][] = $res['kelompok'];
	$data['normal'][] = $res['normal'];
	
} 

$jm = sizeof($data['id_perkiraan']);
header("Pragma: public" );
header("Expires: 0" );
header("Cache-Control: must-revalidate, post-check=0, pre-check=0" );
header("Content-Type: application/force-download" );
header("Content-Type: application/octet-stream" );
header("Content-Type: application/download" );;
header("Content-Disposition: attachment;filename=nomor_perkiraan.xls " );
header("Content-Transfer-Encoding: binary " );
xlsBOF();
xlsWriteLabel(0,3,"Chart Of Account" );
xlsWriteLabel(2,1,"No.Perkiraan" );
xlsWriteLabel(2,2,"Nama Perkiraan" );
xlsWriteLabel(2,3,"Tipe" );
xlsWriteLabel(2,4,"Induk" );
xlsWriteLabel(2,5,"Level" );
xlsWriteLabel(2,6,"Kelompok" );
xlsWriteLabel(2,7,"Normal" );
$xlsRow = 3;

for ($y=0; $y<=$jm; $y++) {
	++$i;
	xlsWriteLabel($xlsRow,1,$data['nomor_perkiraan'][$y]);
	xlsWriteLabel($xlsRow,2,$data['nama_perkiraan'][$y]);
	xlsWriteLabel($xlsRow,3,$data['tipe'][$y]);
	xlsWriteLabel($xlsRow,4,$data['induk'][$y]);
	xlsWriteLabel($xlsRow,5,$data['level'][$y]);
	xlsWriteLabel($xlsRow,6,$data['kelompok'][$y]);
	xlsWriteLabel($xlsRow,7,$data['normal'][$y]);
	$xlsRow++;
}
xlsEOF();
exit();