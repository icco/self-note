<?php 
/** 
 * A script for note taking in class.
 * @author Nat Welch
 */

// Connects to DB. Returns a connection.
function connect()
{
	$DBNAME = "selfnote.db"; // The sqlite DataBase.
	
	try {
		$db = new PDO("sqlite:" . $DBNAME);
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}
	
	// Uncomment for detailed database errors
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 

	// Create table if it does not exist	
	$query = "select name from sqlite_master where name='notes'";
	if(!$db->query($query)->fetch())
	{
		$query = "CREATE TABLE notes (id INTEGER PRIMARY KEY,post TEXT, tag TEXT, ts TIMESTAMP)";
		$db->exec($query);
	}

	return $db;
}

// Formats a row from the page
function format($row)
{
	$ret = "<hr>\n";
	$ret .= "<div id=\"". $row['id'] . "\"> ";
	$ret .= "<div class=\"post\"> " . $row['post'] . "</div><div class=\"timestamp\">" . $row['ts'] . "</div><div class=\"tag\">" . $row['tag'] . "</div>";

	return $ret;
}

// Returns an array of posts, tags, and dates
function getPosts($conn)
{
	$query = "select * from notes order by ts desc";
	return $conn->query($query);
}

// Returns an array of posts, tags, and dates within specified tag
function getPostsbyTag($conn, $tag)
{
	$query = "select * from notes where tag = '$tag'";
	return $conn->query($query);
}

// Adds a new post
function add($conn, $post, $tag)
{
	$post = htmlspecialchars(filter_var($post));
	$tag = htmlspecialchars(filter_var($tag));

	$query = "insert into notes (ts, post, tag) values(strftime('%s','now','localtime'),'$post','$tag')";
	print $query;
	$c = $conn->exec($query);

	if($c >= 1)
	{
		//print "INSERTED" . $c . "POSTS.\n";	
	}
	else
	{
		print "INSERT FAIL.\n";	
	}
}

?>

<html>
<head>
<title>Nat's Note Taker</title>
<?php $db = connect(); ?>
</head>
<body>
<form action="index.php" method="post">
<textarea rows="3" cols="63" name="post">Your Notes</textarea>
<br /><input value="Class" name="tag"> <input type="submit" value="Submit">
</form>

<?php if($_POST["post"] != NULL) { add($db, $_POST["post"], $_POST["tag"]); } ?>

<?php
	$arr = getPosts($db);
	
	foreach($arr as $row)
	{
		print format($row);
	}
?>

<?php $db = NULL; ?>
</body>
</html>
