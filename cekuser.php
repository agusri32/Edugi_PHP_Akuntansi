<?php session_start();

if(isset($_POST['submit'])=='Login'){

	include ("koneksi.php");
	$user_get = $_POST['pengenal'];
	$pass_get = md5($_POST['kunci']);
	$waktu=date("Y-m-d");

	$query = "select * from tabel_induk_user where username='$user_get' and password='$pass_get' and publish='Yes'";
	$cek_user=mysqli_query($link,$query);
	$tot=mysqli_num_rows($cek_user);
	
	if ($tot==0)
	{
		header ("location:index.php?warn=try");
    }
	else
	{
		while ($a=mysqli_fetch_array($cek_user))
		{
			$i=$a['id_user'];
			$u=$a['username'];
			$l=$a['leveluser'];
			$n=$a['nama_lengkap'];	
		}

		$_SESSION['waktu']=$waktu;
		$_SESSION['id_user']=$i;
		$_SESSION['username']=$u;
  		$_SESSION['leveluser']=$l;
 		$_SESSION['nama_lengkap']=$n;
		
  		$tgl=date("Y-m-d");
  		$update_user=mysqli_query($link,"update tabel_induk_user set statuslogin='Online', tgllogin='$tgl' where id_user='$i'");
   		
		?>
		<script>
		document.location.href="home.php";
		</script>
		<?php 
 	}
}
?>