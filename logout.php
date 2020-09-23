<?php session_start(); 
	include ("koneksi.php");
	$id_user=$_SESSION['id_user'];
	$update_user=mysqli_query($link,"update tabel_induk_user set statuslogin='Offline' where id_user='$id_user'");
	session_destroy();
	?><script language="javascript">document.location.href="index.php?status=logout";</script><?php
?>
