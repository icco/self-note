<?

	require_once('config.php');
	require_once('saveAdapter.class');


	saveAdapter::writeXmlHeader($_REQUEST['action'], true);

	// trigger the appropriate command
	switch ($_REQUEST['action'])
	{
		case 'save' 	: saveAdapter::saveToDatabase($_REQUEST['content']) ;
		case 'draft'	: saveAdapter::saveToDatabase($_REQUEST['content'], true);
	}

	saveAdapter::writeXmlFooter() ;

?>