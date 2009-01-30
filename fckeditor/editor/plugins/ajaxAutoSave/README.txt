*
* Licensed under the terms of the GNU Lesser General Public License:
* 		http://www.opensource.org/licenses/lgpl-license.php
* 
* File Name: fckplugin.js
* 	Plugin to post the editor's cotent to the server through AJAX 
* 
* File Authors:
*       Mike Tonks (http://greenmap.sourceforge.net/fck_demo/)
*       adapted from ajaxPost by Paul Moers (http://www.saulmade.nl, http://www.saulmade.nl/FCKeditor/FCKPlugins.php)
* 

----------------------------------------------------------
README : ajaxAutoSave plugin for fckeditor
----------------------------------------------------------

SYNOPSIS

This plugin was developed from the excellent ajaxPost plugin by Paul Moers.

The plugin provides an automatic save facility in the background, while you are typing in fckeditor - similar to the auto save / auto backup facility in many popular editors and other programs.  The saved document is treated as a draft.

In addition it includes the facility to show a popup dialogue if the editor is closed or exited without saving.

To enable the auto save feature, it is necessary to have a server side script or web service to recieve the save data.  Sampel code will not work untill this is configured.  A sample php connector is provided.  You may wish to adapt this to fit your own application.

----------------------------------------------------------

USAGE

Initially, the document is unchanged and the Auto Save feature is inactive.  When you start editing in the FCKEditor control, the Auto Save feature will be activated.  The ajaxAutoSave toolbar icon (right hand side) will change and the associated rollover text will change.

After 30 seconds the Auto Save function will run in the background, saving your document to the server.  

If a successfull save result is returned from the server, a green tick appears and the Auto Save status is reset.  When you continue typing the Auto Save function will be triggered again.  If an error is recieved from the server, a red cross will appear and the AutoSave function will be disabled.  The error message will appear in the rollover text.

In addition, if you exit the form without saving a dialogue box will appear, reminding you to save the document.

----------------------------------------------------------

INSTALLATION

1) Unpack the plugin files to the folder: /fckeditor/plugins/ajaxAutoSave in your website directory.  I assume you have already installed the latest version of fckeditor from http://www.fckeditor.net

2) Edit the file /fckeditor/fckconfig.js and add the following lines:


//----------------------------------------------------
// ajaxAutoSave plugin 
FCKConfig.Plugins.Add( 'ajaxAutoSave','en') ;

// --- config settings for the ajaxAutoSave plugin ---
// URL to post to
FCKConfig.ajaxAutoSaveTargetUrl = '/phpmap/saveAdapter/saveAdapter.php' ;

// Enable / Disable Plugin onBeforeUpdate Action 
FCKConfig.ajaxAutoSaveBeforeUpdateEnabled = true ;

// RefreshTime
FCKConfig.ajaxAutoSaveRefreshTime = 30 ;

// Sensitivity to key strokes
FCKConfig.ajaxAutoSaveSensitivity = 2 ;


3) If necessary, adjust these settings to fit your configuration.

4) Still in the fckconfig.js file, edit your toolbarSet and add a button names ajaxAutoSave, e.g.

FCKConfig.ToolbarSets["Default"] = [
	['Bold','Italic','-','ajaxAutoSave']
] ;


5) Set up your database to accept the Auto Save requests.  Edit the file /saveAdapter/config/php with your database connection settings or alter the script to fit your application.

For a mySQL database, you coul duse the following:


CREATE TABLE drafts (
	userId 	MEDIUMINT NOT NULL AUTO_INCREMENT, 
	text 	TEXT,
	PRIMARY KEY (userID)
);

----------------------------------------------------------

Links :

- The FCKeditor's official web site: http://www.fckeditor.net/
- The ajaxAutoSave demonstration page: http://greenmap.sourceforge.net/fck_demo/
- The forum thread about the ajaxAutoSave plugin: FCKeditor forum @ sourceforge.net: [http://tbc]
- Paul Moers' FCK_Plugin page (see ajaxPost) http://www.saulmade.nl/FCKeditor/FCKPlugins.php
