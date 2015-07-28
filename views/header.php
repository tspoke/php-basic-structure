<?php
defined("_uniq_token_") or die('');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />	
	<title>Your title</title>
	<meta name='description' content="" />
	<meta name='keywords' content="" />
	
	<link href='<?php echo URL; ?>/static/css/reset.css' rel='stylesheet' type='text/css' media='screen' />
	<link href='<?php echo URL; ?>/static/css/common.css' rel='stylesheet' type='text/css' media='screen' />
	<link href='<?php echo URL; ?>/static/css/design.css' rel='stylesheet' type='text/css' media='screen' />
	
	<?php echo Includer::load("css"); ?>
	
	<link rel='icon' type='image/png' href='<?php echo URL; ?>/static/img/favicon.png'/>
	
    <script type='text/javascript' src='<?php echo URL; ?>/static/js/libs/jquery-2.1.1.min.js'></script>
</head>
<body>
	<div id='container'>
		