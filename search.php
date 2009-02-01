<?php
include("backend.php");
$db = connect();
$xml = buildXML($db);
$xmlDoc = new DOMDocument();
$xmlDoc->loadXML($xml);

$x = $xmlDoc->getElementsByTagName('posts');

//get the q parameter from URL
$q = $_GET["q"];

//lookup all links from the xml file if length of q>0
if (strlen($q) > 0)
{
	$hint = "";
	$arr = getPosts($db);
	foreach($arr as $row)
	{
		$y = $row['tag'];
		$z = dePost($row['post']);
		$e = $row['email'];

		//find a link matching the search text
		if (stristr($y,$q) || stristr($e,$q) || stristr($z,$q))
		{
			if ($hint == "")
			{
				$hint="post: " .$z .  
					"\ntag: " .$y .
					"\nemail: " .$e;
			}
			else
			{
				$hint = $hint . "<br />\n" . 
					"post: " .$z .  
					"\ntag: " .$y .
					"\nemail: " .$e;
			}
		}
	}
}

// Set output to "no suggestion" if no hint were found
// or to the correct values
if ($hint == "")
{
	$response="no suggestion for " . $q;
}
else
{
	$response=$hint;
}

//output the response
echo $response;
$db = NULL;
?>
