<?php
/**
 * Checkpoint extension by Skizzerz, from a request on IRC
 * It would be nice if this was in core and not an extension, but having an
 * extension is good for testing purposes, etc. before implementation
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	echo "This is an extension to the MediaWiki software and cannot be used standalone";
	die( 1 );
}

$wgExtensionCredits['other'][] = array(
	'path'           => __FILE__,
	'name'           => 'Checkpoint',
	'descriptionmsg' => 'checkpoint-desc',
	'author'         => 'Ryan Schmidt',
	'url'            => 'https://www.mediawiki.org/wiki/Extension:Checkpoint',
	'version'        => '0.2.0',
);

$wgMessagesDirs['Checkpoint'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['Checkpoint'] = dirname( __FILE__ ) . '/Checkpoint.i18n.php';

$wgHooks['EditPageBeforeEditButtons'][] = 'efCheckpointButton';
$wgHooks['ArticleSave'][] = 'efCheckpointSave';
$wgHooks['GetFullURL'][] = 'efCheckpointReturn';

function efCheckpointButton( &$editpage, &$buttons ) {
	$attr = array(
		'id'    => 'wpCheckpoint',
		'name'  => 'wpCheckpoint',
		'type'  => 'submit',
		'value' => wfMessage( 'checkpoint' )->text(),
		'title' => wfMessage( 'checkpoint-tooltip' )->text(),
	);
	$buttons['checkpoint'] = Xml::element( 'input', $attr, '' );
	return true;
}

function efCheckpointSave( $article, $user, $text, &$summary, $minor, $watch, $sectionanchor, $flags ) {
	global $wgRequest;

	if ( $wgRequest->getCheck( 'wpCheckpoint' ) ) {
		if ( $summary == '' ) {
			// blank summary, so let's get an automatic one if
			// applicable (the appending bit prevents autosummaries
			// from appearing otherwise).
			$oldtext = $article->getContent( Revision::RAW ); // current revision
			$summary = $article->getAutosummary( $oldtext, $text, $flags );
		}
		$summary .= wfMessage( 'word-separator' )->text() . wfMessage( 'checkpoint-notice' )->text();
	}
	return true;
}

function efCheckpointReturn( $title, &$url, $query ) {
	global $wgRequest;

	if ( $wgRequest->wasPosted() && $wgRequest->getCheck( 'wpCheckpoint' ) ) {
		$frag = $title->getFragmentForURL();
		$querystr = strpos( $url, '?' ) ? '&' : '?'; // see if we need to append a ? or a &
		if ( $frag == '' ) {
			// just append our query to the end
			$url .= $querystr . 'action=edit&preview=yes';
		} else {
			// do a string replace
			$url = str_replace( $frag, $querystr . 'action=edit&preview=yes' . $frag, $url );
		}
	}
	return true;
}
