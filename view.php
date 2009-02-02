<?php 
/** 
 * @author Nat Welch
 */

include("backend.php");
?>

<html>
	<head>
		<title>Nat's Note Taker</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="style.css" rel="stylesheet" type="text/css" />
		<?php $db = connect(); /* Open DB */ ?>
	</head>
	<body>
	<div id="container">
	<h1><a href="index.php" >SelfNote</a>.</h1>
		<?php
		if(isset($_GET["id"]))
		{
			print view($db,$_GET["id"]);
		}
		else
		{
			print view($db,0);
		}
		$db = NULL; /* Close DB*/
		
		?>

	<div id="foot">This note taking software developed by <a href="http://natwelch.com" title="Nat's Homepage">Nat Welch</a>. It's hosted on <a href="http://github.com/icco/self-note/tree/master" title="GitHub Repo">GitHub</a>.
	<br /><small>// I don't know if this opens pants, but I'll give it a try. --- trainman419</small>
	</div>
	</div>
</body>
</html>
