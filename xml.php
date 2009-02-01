<?php
include("backend.php");
$db = connect();
$xml = buildXML($db);

print "<pre>" . htmlspecialchars($xml) . "</pre>";

?>
