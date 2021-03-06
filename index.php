<?php 
/** 
 * A script for note taking in class.
 * @author Nat Welch
 */

ob_start(); // a hack but works.

include("fckeditor/fckeditor.php");
include("backend.php");

$db = connect();

if(!checkCookie($db))
{
	header('Location: auth.php');
}

ob_end_flush();

?>

<html>
	<head>
		<title>Nat's Note Taker</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="style.css" rel="stylesheet" type="text/css" />
		<link href="fckeditor/editorstyles.css" rel="stylesheet" type="text/css" />
		<?php $db = connect(); /* Open DB */ ?>
	</head>
	<body>
	<div id="clock"></div>
	<div id="container">
	<form action="search.php" method="get">
	<h4 style="float: right"><a href="search.html" >Search</a>: <input name="q" size="50"></h4>
	<input type="hidden" name="display" value="1">
	<h1><a href="." >SelfNote</a>.</h1>
	</form>
		<div id="editor">
		<?php 
			if(isset($_POST["post"]) && !isset($_POST["id"])) 
			{ 
				add($db, $_POST["post"], $_POST["tag"], $_POST["email"]); 
			} 
			else if(isset($_POST["id"]))
			{
				update($db, $_POST["post"],$_POST["tag"],$_POST["email"], $_POST["id"]);
				$up = $_POST["id"];
			}
			else if(isset($_GET["delete"]))
			{
				del($db, $_GET["delete"]);
			}
		?>
		
		<form action="index.php" method="post">
		<?php
			$oFCKeditor = new FCKeditor('post') ;
			$oFCKeditor->Config["CustomConfigurationsPath"] = "../FCKconfig.js";
			$oFCKeditor->ToolbarSet = 'NatToolbar';
			$oFCKeditor->BasePath = 'fckeditor/' ;
			$oFCKeditor->Width = '100%';
			$oFCKeditor->Height = '440px';
			if(isset($_GET["update"]))
			{
				$oFCKeditor->Value = stripslashes(htmlspecialchars_decode(rawurldecode(getPost($db,$_GET["update"])),ENT_QUOTES));
				$up = $_GET["update"];
			}
			else if($_POST["submit"] == "Save")
			{
				$oFCKeditor->Value = stripslashes(htmlspecialchars_decode(rawurldecode(getPost($db,$up)),ENT_QUOTES));
			}
			else
			{
				$oFCKeditor->Value = '<p>Enter Notes Here.</p>';
				$up = 0;
			}
			$oFCKeditor->Config['SkinPath'] = 'skins/silver/';
			$oFCKeditor->Create();
		?>
		<a href="http://gist.github.com/" target="_blank" style="padding: 5px; float: right;">GitHub Gists</a>
		<br /><input readonly value="<?php if(isset($_GET["update"])){ print getEmail($db,$_GET["update"]);  } else { print $DEFAULT_EMAIL; } ?>" name="email"> 
		<input value="<?php if($up > 0){ print getTag($db,$up);  } else { print  $DEFAULT_COURSE; } ?>" name="tag">
		<input type="submit" name="submit" value="Submit"> 
		<input type="submit" name="submit" value="Save"> 
		<?php if($up > 0) { ?>
			<input type="hidden" value="<?php print $up; ?>" name="id" />
		<?php } ?>
		</form>

		</div>
		<?php
			$arr = getPosts($db);
			
			foreach($arr as $row)
			{
				print "<hr>\n" . format($row);
	
			}
		?>

		<?php $db = NULL; /* Close DB */ ?>

	<div id="foot">This note taking software developed by <a href="http://natwelch.com" title="Nat's Homepage">Nat Welch</a>. It's hosted on <a href="http://github.com/icco/self-note/tree/master" title="GitHub Repo">GitHub</a>.
	<br /><small><?php print footQuote(); ?></small>
	</div>
	</div>
<script language="javascript">
function js_clock()
{
	var clock_time = new Date();
	var clock_hours = clock_time.getHours();
	var clock_minutes = clock_time.getMinutes();
	var clock_seconds = clock_time.getSeconds();
	if (clock_hours < 10)
	{
		clock_hours = "0" + clock_hours;
	}
	if (clock_minutes < 10)
	{
		clock_minutes = "0" + clock_minutes;
	}
	if (clock_seconds < 10)
	{
		clock_seconds = "0" + clock_seconds;
	}
	var clock_div = document.getElementById('clock');
	clock_div.innerHTML = clock_hours + ":" + clock_minutes + ":" + clock_seconds;
	//clock_div.innerHTML = clock_time.toGMTString();
	setTimeout("js_clock()", 1000);
} js_clock();
</script>

</body>
</html>
