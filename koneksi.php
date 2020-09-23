<?php
$server   = "localhost";
$user     = "root";
$password = "";
$database = "proj_akuntansidb";

///////////////////// SETUP ///////////////////////
ini_set('display_errors',TRUE);
error_reporting(E_ALL ^ E_NOTICE);

//seting waktu
$date=date("Y-m-d");
$time=date("H:i:s");
$tanggal=date("Y-m-d");
$datetime=date("Y-m-d H:i:s");

//koneksi RDBMS
$link = mysqli_connect($server,$user,$password,$database) or die("Koneksi gagal");

//seting perkiraan
$row_setup=mysqli_fetch_array(mysqli_query($link,"select * from tabel_setup_gl_perkiraan"));
$tampil_pendapatan=$row_setup['pendapatan'];
$tampil_pengeluaran=$row_setup['pengeluaran'];
$tampil_aktivalancar=$row_setup['aktiva_lancar'];
$tampil_aktivatetap=$row_setup['aktiva_tetap'];
$tampil_kewajiban=$row_setup['kewajiban'];
$tampil_modal=$row_setup['modal'];
$tampil_kas=$row_setup['kas'];

//untuk penyusutan
$tampil_penyusutan=explode("-",$row_setup['penyusutan']);

//pecah menjadi start-end
$pendapatan = explode("-",$tampil_pendapatan);
$pendapatan_start = $pendapatan[0];
$pendapatan_end = $pendapatan[1];

$pengeluaran = explode("-",$tampil_pengeluaran);
$pengeluaran_start = $pengeluaran[0];
$pengeluaran_end = $pengeluaran[1];

$aktivalancar = explode("-",$tampil_aktivalancar);
$aktivalancar_start = $aktivalancar[0];
$aktivalancar_end = $aktivalancar[1];

$aktivatetap = explode("-",$tampil_aktivatetap);
$aktivatetap_start = $aktivatetap[0];
$aktivatetap_end = $aktivatetap[1];

$kewajiban = explode("-",$tampil_kewajiban);
$kewajiban_start = $kewajiban[0];
$kewajiban_end = $kewajiban[1];

$modal = explode("-",$tampil_modal);
$modal_start = $modal[0];
$modal_end = $modal[1];
?>
