<?php 
/*session_start();
//untuk bypass halaman tanpa login
$_SESSION['waktu']=date("Y-m-d");
$_SESSION['id_user']="1";
$_SESSION['username']="KangAgus";
$_SESSION['leveluser']="Admin";
$_SESSION['nama_lengkap']="Agus Sumarna";
*/
?>
<!--<script language="javascript">document.location.href="home.php";</script>-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aplikasi Akuntansi Online</title>
<link rel="shortcut icon" href="images/favicon.ico" />

<link rel="stylesheet" type="text/css" href="style_css/style.css" />

<script type="text/javascript" src="style_script/clockp.js"></script>
<script type="text/javascript" src="style_script/clockh.js"></script> 
<script type="text/javascript" src="style_script/jquery.min.js"></script>
<script type="text/javascript" src="style_script/ddaccordion.js"></script>
<script type="text/javascript" src="style_script/jconfirmaction.jquery.js"></script>
<script type="text/javascript">	
	$(document).ready(function() 
	{
		$('.ask').jConfirmAction();
	});
</script>
<script language="javascript" type="text/javascript" src="style_script/niceforms.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="style_css/niceforms-default.css" />

<script src='style_script/jquery/jquery.min.js' type='text/javascript'/></script>
<script src='style_script/jquery/jquery-easing.js' type='text/javascript'/></script>
<script type='text/javascript'>
$(function() 
{ 
	$(&quot;.lavaLamp&quot;).lavaLamp(
	{ 
		fx: &quot;backout&quot;, speed: 450
	})
});
</script>

</head>
<body>
<div id="main_container">
		<div align="center">
            <div class="user">
    			<div class="right_user">Waktu : <?php echo $waktu=date("Y-m-d H:i:s"); ?> &nbsp;&nbsp;</div>    
			</div>
            
			<ul class="slideshow">
				<li><img src="images/banner.jpg" width="1000" height="167"/></li>
			</ul>
   		</div>
    <div style="margin:0 auto;width:1000px;height:auto;padding:0px 0 50px 0;background:url(images/bg_login.png) no-repeat center top #fff;">                
	    <div class="menu"></div> 
    	<div class="center_content">  
   			<div class="right_content_admin">            
        		<div align="center" style="">
					<fieldset style="border:solid 2px #ccc;">
						<legend style="padding:2px;"><img src="images/gembok.png" style="margin-bottom:-3px;" height=15 width=15 valign=center>&nbsp; Staff Only</legend>
						<table width="700">
							<tr>
								<td width="300" valign="top">
									<p>Welcome<p>
									<p>This page is used to log into the Accounting Application</p> 
									<p>Please use the correct User Name and Password.</p>
								<br>
								<br></td>
								<td width="300">
									<div>
         								<form action="cekuser.php" method="post" class="niceform" />
       										<table border=0>
                    							<tr>  
                        							<td><label for="username">Username : </label></td>
                        							<td><input type="text" name="pengenal" id="" size="54" style="width:200px;" /></td>
                    							</tr>
                    							<tr>
                       							  <td><label for="password">Password : </label></td>
                        							<td><input type="password" name="kunci" id="" size="54" style="width:200px;" /></td>
                    							</tr>
												<tr >
													<td align="right" valign="top"></td>
												    <td valign="bottom"><input type="hidden" name="kantor"  value="CU"></td> 
												</tr>
												<tr>
													<td colspan="2">&nbsp;</td>
												</tr>
												<tr class="submit">
													<td colspan="2" align="right">
                    								<input type="submit" name="submit" id="submit" value="LOGIN" />                    								</td> 
												</tr>
											</table>
                   					  </form>
    								</div>  
                    			</td>
							</tr>
							<tr>
								<td></td>
								<td><a href="#" class="forgot_pass">Forgot Password</a></td>
							</tr>
						</table>
					</fieldset>
				</div>
     		</div>                    
  		</div>
    	<div class="clear"></div>
	</div>
	<div class="footer">
    	<div class="left_footer">Copyright @ Ri32</div>    
	</div>
</div>		
</body>
</html>