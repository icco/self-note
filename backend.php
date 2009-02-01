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
	
	return $db;
}

// Formats a row from the page
function format($row)
{
	$ret = "<hr>\n";
	$ret .= "<div id=\"". $row['id'] . "\">\n ";
	$ret .= "<img src=\"" . gravatar($row['email']) . "\" class=\"grav\" title=\"A gravatar\" \>\n";
	$ret .= "<div class=\"tag\">" . $row['tag'] . "</div>\n";
	$ret .= "<div class=\"timestamp\">" . date("m.d.Y",$row['ts']) . " <span class=\"edit\"><a href=\"index.php?update=" . $row['id'] ."\">Edit</a></span> <span class=\"delete\"><a href=\"?delete=" . $row['id'] . "\">x</a></span></div>\n";
	$ret .= "<div class=\"post\"> " . stripslashes(htmlspecialchars_decode(rawurldecode($row['post']), ENT_QUOTES)) . "</div>\n";
	//$ret .= "<div class=\"email\"> " . $row['email'] . "</div>";
	$ret .= "</div>\n";

	return $ret;
}

// Returns an array of posts, tags, and dates
function getPosts($conn)
{
	$query = "select * from notes order by id desc";
	return $conn->query($query);
}

// Returns the post content 
function getPost($conn, $id)
{
	$query = "select post from notes where id='$id'";
	$ret = $conn->query($query)->fetch();

	return $ret["post"];
}

// Returns the email of a certain post 
function getEmail($conn, $id)
{
	$query = "select email from notes where id='$id'";
	$ret = $conn->query($query)->fetch();

	return $ret["email"];
}

// Returns the tag of a certain post 
function getTag($conn, $id)
{
	$query = "select tag from notes where id='$id'";
	$ret = $conn->query($query)->fetch();

	return $ret["tag"];
}

// Returns an array of posts, tags, and dates within specified tag
function getPostsbyTag($conn, $tag)
{
	$query = "select * from notes where tag='$tag'";
	return $conn->query($query);
}

// Adds a new post
function add($conn, $post, $tag, $email)
{
	$post = rawurlencode(htmlspecialchars($post, ENT_QUOTES));
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
		print "\n<!-- " . $query . " -->\n";
	}
}

function update($conn, $post, $tag, $email, $id)
{
	$post = rawurlencode(htmlspecialchars(filter_var($post), ENT_QUOTES));
	$tag = htmlentities(filter_var($tag));
	$id = abs((int)floor($id));

	$query = "update notes set ts=strftime('%s','now','localtime'),post='$post',tag='$tag',email='$email' where id='$id'";
	//print $query;
	$c = $conn->exec($query);

	if($c >= 1)
	{
		//print "Updated " . $c . "POSTS.\n";	
	}
	else
	{
		print "UPDATE FAIL.\n";	
		print "\n<!-- " . $query . " -->\n";
	}
}

function del($conn, $id)
{
	$id = abs((int)floor($id));

	$query = "delete from notes where id='$id'";
	$c = $conn->exec($query);

	if($c >= 1)
	{
		print "Deleted Post #" . $id;	
	}
	else
	{
		print "DELETE FAIL.\n";	
		print "\n<!-- " . $query . " -->\n";
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

function buildXML($conn)
{
	$all = getPosts($conn);
	
	//$ret = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?\>\n";
	$ret = "<posts>";
	foreach($all as $row)
	{
		$ret .= "<entry>";
		$ret .= "<tag>" . $row["tag"] . "</tag>";
		$ret .= "<text>" . strip_tags(stripslashes(htmlspecialchars_decode(rawurldecode($row['post']), ENT_QUOTES))) . "</text>";
		$ret .= "<email>" . $row["email"] . "</email>";
		$ret .= "<date>" . $row["ts"] . "</date>";
		$ret .= "</entry>";
	}
	$ret .= "</posts>";
	
	return $ret;
}

?>

