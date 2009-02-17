<?php 
/** 
 * @author Nat Welch
 */

include("backend.php");

if(isset($_GET["id"]))
{
	$id = $_GET["id"];
}
else
{
	$id = 0;
}

?>

<html>
	<head>
		<title>Nat's Note Taker - Post #<?php print $id; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="style.css" rel="stylesheet" type="text/css" />
		<?php $db = connect(); /* Open DB */ ?>
	</head>
	<body>
	<div id="container">
	<h1><a href="index.php" >SelfNote</a>.</h1>
		<?php
		print view($db,$id);
		print viewMore($db,$id);
		
		$db = NULL; /* Close DB*/
		?>

	<div id="foot">This note taking software developed by <a href="http://natwelch.com" title="Nat's Homepage">Nat Welch</a>. It's hosted on <a href="http://github.com/icco/self-note/tree/master" title="GitHub Repo">GitHub</a>.
	<br /><small><?php print footQuote(); ?></small>
	</div>
	</div>
</body>
</html>
