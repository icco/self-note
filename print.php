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
		<link href="fckeditor/editorstyles.css" rel="stylesheet" type="text/css" />
		<?php $db = connect(); /* Open DB */ ?>
	</head>
	<body>
		<?php
		$arr = getPostbyID($db,$id);

		foreach($arr as $post)
		{
			print dePost($post['post']);
			print "<hr />";	
			print "<div>" . $post['tag'] . "<br />"; 
			print "Last Modified: " . date("r",$post['ts']) . "<br/>\n";
			print "By " . $post['email'] . "</div>";
		}

		$db = NULL; /* Close DB*/
		?>

</body>
</html>
