<?php
include("backend.php");
$db = connect();
$xml = buildXML($db);
$db = NULL;
$xmlDoc = new DOMDocument();
$xmlDoc->loadXML($xml);

$x = $xmlDoc->getElementsByTagName('posts');

//get the q parameter from URL
$q = $_GET["q"];

//lookup all links from the xml file if length of q>0
if (strlen($q) > 0)
{
	$hint = "";
	for($i = 0; $i < $x->length; $i++)
	{
		$y = $x->item($i)->getElementsByTagName('tag');
		$z = $x->item($i)->getElementsByTagName('text');
		$e = $x->item($i)->getElementsByTagName('email');
		
		if($y->item(0)->nodeType == 1)
		{
			//find a link matching the search text
			if (stristr($y->item(0)->childNodes->item(0)->nodeValue,$q) ||
					stristr($e->item(0)->childNodes->item(0)->nodeValue,$q) ||
					stristr($z->item(0)->childNodes->item(0)->nodeValue,$q))
			{
				if ($hint == "")
				{
					$hint="post: " .$z->item(0)->childNodes->item(0)->nodeValue .  
						"\ntag: " .$y->item(0)->childNodes->item(0)->nodeValue .
						"\nemail: " .$e->item(0)->childNodes->item(0)->nodeValue;
				}
				else
				{
					$hint = $hint . "<br />\n" . 
						"post: " .$z->item(0)->childNodes->item(0)->nodeValue .  
						"\ntag: " .$y->item(0)->childNodes->item(0)->nodeValue .
						"\nemail: " .$e->item(0)->childNodes->item(0)->nodeValue;
				}
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
?>
