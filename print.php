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
		<?php $db = connect(); /* Open DB */ ?>
	</head>
	<body>
		<?php
		$arr = getPostbyID($db,$id);

		foreach($arr as $post)
		{
			print "<div>" . $post['tag'] . "<br />"; 
			print "Last Modified: " . date("r",$post['ts']) . "<br/>\n";
			print "By " . $post['email'] . "</div>";
			print "<hr />";	
			print dePost($post['post']);
		}

		$db = NULL; /* Close DB*/
		?>

</body>
</html>
