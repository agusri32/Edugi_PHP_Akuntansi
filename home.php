<?php include "header.php"; ?>
	<?php if(isset($_SESSION['username'])){ $username = $_SESSION['username']; }else{ $username = ""; } ?>
	<h2 align="center"><br>
	Selamat Datang <?php echo strtoupper($username);?> di Aplikasi Akuntansi Online - Ri32
	</h2>
<?php include "footer.php"; ?>