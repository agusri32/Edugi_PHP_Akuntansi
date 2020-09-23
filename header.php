<?php session_start();

//jika ingin menggunakan session
if(!isset($_SESSION['username'])){ header ("location:logout.php"); }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Home</title>
	<link rel="shortcut icon" href="images/favicon.ico"/>

	<link rel="stylesheet" href="style_css/CalendarControl.css" type="text/css">
	<LINK rel="stylesheet" href="style_css/styleauto.css" type="text/css" >
	<link rel="stylesheet" href="style_css/style.css" type="text/css"/>

	<script type="text/javascript" src="style_script/jquery.min.js"></script>
	<script type="text/javascript" src="style_script/ddaccordion.js"></script>
	
	<script type="text/javascript">
	ddaccordion.init(
	{
		headerclass: "submenuheader", 
		contentclass: "submenu", 
		revealtype: "click", 
		mouseoverdelay: 200, 
		collapseprev: true,  
		defaultexpanded: [], 
		onemustopen: false, 
		animatedefault: false, 
		persiststate: true, 
		toggleclass: ["", ""], 
		togglehtml: ["suffix", "<img src='images/plus.gif' class='statusicon'/>", "<img src='images/minus.gif' class='statusicon'/>"],
		animatespeed: "fast", 
		oninit:function(headers, expandedindices)
		{ 
		},
		onopenclose:function(header, index, state, isuseractivated)
		{ 
		}
	})
	</script>
	
	<script src="style_script/niceforms.js" language="javascript" type="text/javascript"></script>
	<link href="style_css/niceforms-default.css" rel="stylesheet" type="text/css" media="all"/>

	<script src="style_script/jquery/jquery.min.js" type="text/javascript"/></script>
	<script src="style_script/jquery/jquery-easing.js" type="text/javascript"/></script>
	<script src="style_script/jquery.maskedinput-1.3.js" type="text/javascript"></script>
</head>
<body>
	<div id="main_container">
		<div align="center">
			<div class="user">
				<div class="right_user">Welcome User, (Time : <?php echo date("y-m-d"); ?>)&nbsp;&nbsp;</div>    
			</div>
			<ul class="slideshow">
				<li><img src="images/banner.jpg" width="1000" height="167"/></li>
			</ul>
		</div>
    	<div class="main_content">
			<div class="menu">
				<ul>
					<li><a href="home.php">Beranda</a></li>
					<li></li>
					<?php include "menu.php"; ?>
				</ul>
			</div>
        	<div class="center_content">
				<div class="right_content">