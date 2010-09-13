# SelfNote

This is a simple PHP based webpage to take notes in class. I did this initially
as a way to research the best way for me to take notes and investigate learning
disabilities associated with note-taking.

## Current Features 

 * FCKeditor based javascript text editing
 * A simple login
 * Support for [gist: id##] formatting
 * Auto linking of urls
 * Printer friendly pages
 * Gravatar support

## Installation

Installation is pretty straight forward.

 * Get a web host with PHP >= 5.2.0 and that you have access to PDO_SQLITE.
 * Edit config.php before you start so the database is created in the right spot and your username is correct.
 * Depending on your permissions (your webserver would need write access to the folder) you might need to create the sqlite db ahead of time as well.

## Feature Ideas

Some future features:

 * Update search page. It would be sweet if this was all done via JavaScript and you could save common views.
 * Clean up the delete setup
 * Add authentication so only you can view your notes, and thus host online.
 * Sync multiple instances? (use git? yet keep private?)
 * The ability to sort notes on that search page.
 * More stats on the view page. Word/line count?
 * Better styling, of both the editor and the page. Maybe a quick button that inverses the page?
 * Turn buttons to links maybe?

