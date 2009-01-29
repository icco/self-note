<?php 
/** 
 * A script for note taking in class.
 * @author Nat Welch
 */

include("fckeditor/fckeditor.php") ;
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
	<h1><a href="." >SelfNote</a>.</h1>
		<div id="editor">
		<?php 
			if(isset($_POST["post"]) && !isset($_POST["id"])) 
			{ 
				add($db, $_POST["post"], $_POST["tag"], $_POST["email"]); 
			} 
			else if(isset($_POST["id"]))
			{
				update($db, $_POST["post"], $_POST["id"]);
			}
		?>
		
		<form action="index.php" method="post">
		<?php
			$oFCKeditor = new FCKeditor('post') ;
			$oFCKeditor->ToolbarSet = 'NatToolbar';
			$oFCKeditor->BasePath = 'fckeditor/' ;
			$oFCKeditor->Width = '100%';
			$oFCKeditor->Height = '440px';
			if(isset($_GET["update"]))
			{
				$oFCKeditor->Value = html_entity_decode(getPost($db,$_GET["update"]));
				$up = $_GET["update"];
			}
			else
			{
				$oFCKeditor->Value = '<p>Enter Notes Here.</p>';
				$up = 0;
			}
			$oFCKeditor->Config["CustomConfigurationsPath"] = "../FCKconfig.js";
			$oFCKeditor->Config['SkinPath'] = 'skins/silver/';
			$oFCKeditor->Create();
		?>
		<br /><input value="<?php print $DEFAULT_EMAIL; ?>" name="email"> <input value="<?php print $DEFAULT_COURSE; ?>" name="tag"> <input type="submit" value="Submit"> 
		<a href="http://gist.github.com/" style=" margin-left: 150px;">GitHub Gists</a>
		<?php if($up > 0) { ?>
			<input type="hidden" value="<?php print $up; ?>" name="id" />
		<?php } ?>
		</form>

		</div>
		<?php
			$arr = getPosts($db);
			
			foreach($arr as $row)
			{
				print format($row);
			}
		?>

		<?php $db = NULL; /* Close DB */ ?>

	<div id="foot">This note taking software developed by <a href="http://natwelch.com" title="Nat's Homepage">Nat Welch</a>. It's hosted on <a href="http://github.com/icco/self-note/tree/master" title="GitHub Repo">GitHub</a>.
	<br /><small>// I don't know if this opens pants, but I'll give it a try. --- trainman419</small>
	</div>
	</div>
	</body>
</html>
