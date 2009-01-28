FCKConfig.ToolbarSets["NatToolbar"] = [
['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo'],
['Cut','Copy','Paste'],
['OrderedList','UnorderedList','-','Outdent','Indent'],
['Link','Unlink'],['Style'],['Source'],['-','ajaxAutoSave']] ;


FCKConfig.FirefoxSpellChecker = true ;
FCKConfig.AutoDetectPasteFromWord = false ;
FCKConfig.ProcessHTMLEntities = true ;

// ajaxAutoSave plugin
FCKConfig.Plugins.Add( 'ajaxAutoSave','en') ;

// --- config settings for the ajaxAutoSave plugin ---
// URL to post to
FCKConfig.ajaxAutoSaveTargetUrl = '/../saveAdapter/saveAdapter.php' ;

// Enable / Disable Plugin onBeforeUpdate Action
FCKConfig.ajaxAutoSaveBeforeUpdateEnabled = true ;

// RefreshTime
FCKConfig.ajaxAutoSaveRefreshTime = 30 ;

// Sensitivity to key strokes
FCKConfig.ajaxAutoSaveSensitivity = 2 ;

