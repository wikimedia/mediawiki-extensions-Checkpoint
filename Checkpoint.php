<?php
/**
 * Checkpoint extension by Skizzerz, from a request on IRC
 * It would be nice if this was in core and not an extension, but having an
 * extension is good for testing purposes, etc. before implementation
 */

if ( function_exists( 'wfLoadExtension' ) ) {
	wfLoadExtension( 'Checkpoint' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['Checkpoint'] = __DIR__ . '/i18n';
	wfWarn(
		'Deprecated PHP entry point used for the Checkpoint extension. ' .
		'Please use wfLoadExtension instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	);
	return;
} else {
	die( 'This version of the Checkpoint extension requires MediaWiki 1.25+' );
}
