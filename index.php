<?php 
/** 
 * A script for note taking in class.
 * @author Nat Welch
 */

/*
 * USER CONFIG
 */
$DEFAULT_EMAIL = "nat@natwelch.com";
$DEFAULT_COURSE = "COURSE";
$SQLITE_DB = "note.db";

include("fckeditor/fckeditor.php") ;

// Connects to DB. Returns a connection.
function connect()
{
	global $SQLITE_DB;
	try {
		$db = new PDO("sqlite:" . $SQLITE_DB);
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
		exit(-1);
	}
	
	// Uncomment for detailed database errors
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 

	// Create table if it does not exist	
	$query = "select name from sqlite_master where name='notes'";
	if(!$db->query($query)->fetch())
	{
		$query = "CREATE TABLE notes (id INTEGER PRIMARY KEY,post TEXT, tag TEXT, ts TIMESTAMP, email TEXT)";
		$db->exec($query);
	}

	return $db;
}

// Formats a row from the page
function format($row)
{
	$ret = "<hr>\n";
	$ret .= "<div id=\"". $row['id'] . "\"> ";
	$ret .= "<img src=\"" . gravatar($row['email']) . "\" class=\"grav\" title=\"A gravatar\" \>";
	$ret .= "<div class=\"tag\">" . $row['tag'] . "</div>";
	$ret .= "<div class=\"timestamp\">" . date("m.d.Y",$row['ts']) . "</div>";
	$ret .= "<div class=\"post\"> " . html_entity_decode($row['post']) . "</div>";
	//$ret .= "<div class=\"email\"> " . $row['email'] . "</div>";
	$ret .= "</div>\n";

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
function add($conn, $post, $tag, $email)
{
	$post = htmlentities(filter_var($post));
	$tag = htmlentities(filter_var($tag));

	$query = "insert into notes (ts, post, tag, email) values(strftime('%s','now','localtime'),'$post','$tag', '$email')";
	//print $query;
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

// Converts an email to a gravatar img link
function gravatar($email)
{
	// Size in pixels of avatar
	$size = 40;

	// Default Image
	$default = "http://some.com/image.jpg";

	// Generate Image Link
	$grav_url = "http://www.gravatar.com/avatar.php?gravatar_id=";
	$grav_url .= md5( strtolower($email) );
	$grav_url .= "?d=identicon";
	//$grav_url .= "&default=".urlencode($default); // Comment out for default gravatar
	$grav_url .= "&size=" . $size; 

	return $grav_url;
}

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
	<h1>SelfNote.</h1>
		<div id="editor">
		<form action="index.php" method="post">
		<?php
			$oFCKeditor = new FCKeditor('post') ;
			$oFCKeditor->ToolbarSet = 'NatToolbar';
			$oFCKeditor->BasePath = 'fckeditor/' ;
			$oFCKeditor->Width = '100%';
			$oFCKeditor->Height = '440px';
			$oFCKeditor->Value = '<p>Enter Notes Here.</p>' ;
			$oFCKeditor->Config["CustomConfigurationsPath"] = "../FCKconfig.js";
			$oFCKeditor->Config['SkinPath'] = 'skins/silver/';
			$oFCKeditor->Create();
		?>
		<br /><input value="<?php print $DEFAULT_EMAIL; ?>" name="email"> <input value="<?php print $DEFAULT_COURSE; ?>" name="tag"> <input type="submit" value="Submit">
		</form>

		<?php 
			if(isset($_POST["post"])) 
			{ 
				add($db, $_POST["post"], $_POST["tag"], $_POST["email"]); 
			} 
		?>
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
