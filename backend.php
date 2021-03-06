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
	global $DEFAULT_EMAIL;
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
	
	// Create table if it does not exist	
	$query = "select name from sqlite_master where name='auth'";
	if(!$db->query($query)->fetch())
	{
		$query = "CREATE TABLE auth (id INTEGER PRIMARY KEY,email TEXT, passwd TEXT)";
		$db->exec($query);
		$p = md5("secret");
		$query = "insert into auth (email, passwd) values('$DEFAULT_EMAIL','$p')";
		$db->exec($query);
	}
	
	return $db;
}

function login($conn, $u, $p)
{
	$query = "select id from auth where email='$u' AND passwd='$p'";
	if(!$conn->query($query)->fetch())
	{
		return false;
	}
	else
	{
		return true;
	}
}

// Formats a row from the page
function format($row)
{
	$ret = "<div id=\"". $row['id'] . "\">\n ";
	$ret .= "<img src=\"" . gravatar($row['email']) . "\" class=\"grav\" title=\"A gravatar\" \>\n";
	$ret .= "<div class=\"tag\">" . $row['tag'] . " <span class=\"permlink\"><a href=\"view.php?id=" . $row['id'] . "\" >#</a> <a href=\"print.php?id=" . $row['id'] . "\" >p</a></span></div>\n";
	$ret .= "<div class=\"timestamp\">" . date("m.d.Y",$row['ts']) . "<!-- " . date("r",$row['ts'])  . " --> <span class=\"edit\"><a href=\"index.php?update=" . $row['id'] ."\">Edit</a></span> <span class=\"delete\"><a href=\"?delete=" . $row['id'] . "\">x</a></span></div>\n";
	$ret .= "<div class=\"post\"> " . dePost($row['post']) . "</div>\n";
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

function getPostbyID($conn, $id)
{
	$query = "select * from notes where id='$id'";
	return $conn->query($query);
}

// Adds a new post
function add($conn, $post, $tag, $email)
{
	$post = prePost($post);
	$tag = htmlentities($tag);

	$query = "insert into notes (ts, post, tag, email) values(strftime('%s','now','localtime'),'$post','$tag', '$email')";
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
	$post = prePost($post);
	$tag = htmlentities($tag);
	$id = abs((int)floor($id));

	$query = "update notes set ts=strftime('%s','now','localtime'),post='$post',tag='$tag',email='$email' where id='$id'";
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
	
	$ret = "";
	$ret .= "<posts>";
	foreach($all as $row)
	{
		$ret .= "\n<entry>";
		$ret .= "<tag>" . $row["tag"] . "</tag>";
		$ret .= "<text>" . strip_tags(dePost($row['post'])) . "</text>";
		$ret .= "<email>" . $row["email"] . "</email>";
		$ret .= "<date>" . $row["ts"] . "</date>";
		$ret .= "</entry>\n";
	}
	$ret .= "</posts>";
	
	return $ret;
}

function dePost($in)
{
	$out = stripslashes(htmlspecialchars_decode(rawurldecode($in), ENT_QUOTES));
	$out = preg_replace('/\s(\w+:\/\/)(\S+)/', ' <a href="\\1\\2" title="\\2">\\1\\2</a>', $out); 

	// Gist replacement inspired by http://github.com/guitsaru/code-feather/	
	$out = preg_replace("/\[gist: ([0-9]+)\]/i", '<script src="http://gist.github.com/\\1.js"></script>', $out);
	
	// Turn links into links
	$out = preg_replace('/\s(\w+:\/\/)(\S+)/', ' <a href="\\1\\2" title="\\2">\\1\\2</a>', $out);  

	return $out;
}

function prePost($in)
{
	$in = rawurlencode(htmlspecialchars($in, ENT_QUOTES));
	return $in;
}

function view($conn,$id)
{
	$arr = getPostbyID($conn,$id);

	foreach($arr as $row)
	{
		$out = format($row);
	}

	return $out;
}

// Used to display box at bottom of post.
function viewMore($conn,$id)
{
	$arr = getPostbyID($conn,$id);

	// In theory this should only return one...
	foreach($arr as $row)
	{
		$out = "<div class=\"bottom\">";
		$out .= "<p id=\"morehead\">More info about this post:</p>";
		$out .= "<p>Unix time: " . date("U",$row['ts']) ." </p>\n";
		$out .= "<p>Author Email: <a href=\"mailto:" . $row['email'] ."\">" . $row['email'] . "</a></p>\n";
		$out .= "</div>";

	}

	return $out;
}

function footQuote()
{
	global $FOOT_QUOTE;
	return $FOOT_QUOTE;
}

function checkCookie($conn)
{
	$u = $_COOKIE['SELFNOTE_email'];
	$p = $_COOKIE['SELFNOTE_pw'];

	return login($conn,$u,$p);
}

function makeCookie($u, $p)
{
	$expire = time()+(60*60*24); // Expire in a day
	setcookie("SELFNOTE_email", $u, $expire);
	setcookie("SELFNOTE_pw", $p, $expire);
}

?>

