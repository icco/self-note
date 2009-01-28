<?php 
/** 
 * A script for note taking in class.
 * @author Nat Welch
 */

include("config.php");

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
	
	/* Create drafts table
	$query = "select name from sqlite_master where name='notes_drafts'";
	if(!$db->query($query)->fetch())
	{
		$query = "CREATE TABLE notes_drafts (id INTEGER PRIMARY KEY,post TEXT, tag TEXT, ts TIMESTAMP, email TEXT)";
		$db->exec($query);
	}
	 */

	return $db;
}

// Formats a row from the page
function format($row)
{
	$ret = "<hr>\n";
	$ret .= "<div id=\"". $row['id'] . "\"> ";
	$ret .= "<img src=\"" . gravatar($row['email']) . "\" class=\"grav\" title=\"A gravatar\" \>";
	$ret .= "<div class=\"tag\">" . $row['tag'] . "</div>";
	$ret .= "<div class=\"timestamp\">" . date("m.d.Y",$row['ts']) . " <span class=\"edit\"><a href=\"index.php?update=" . $row['id'] ."\">Edit</a></span></div>";
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

// Returns the post content 
function getPost($conn, $id)
{
	$query = "select * from notes where id='$id'";
	$ret = $conn->query($query)->fetch();

	return $ret["post"];
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
	$post = htmlspecialchars(filter_var($post), ENT_QUOTES);
	$tag = htmlentities(filter_var($tag));

	$query = "insert into notes (ts, post, tag, email) values(strftime('%s','now','localtime'),'$post','$tag', '$email')";
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

function update($conn, $post, $id)
{
	$post = htmlspecialchars(filter_var($post), ENT_QUOTES);

	$query = "update notes (ts, post) values(strftime('%s','now','localtime'),'$post') where id='$id'";
	print $query;
	$c = $conn->exec($query);

	if($c >= 1)
	{
		//print "Updated " . $c . "POSTS.\n";	
	}
	else
	{
		print "UPDATE FAIL.\n";	
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

