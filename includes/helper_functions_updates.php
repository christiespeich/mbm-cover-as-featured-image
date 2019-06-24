<?php

add_action('init', 'mbdbcafi_update_versions' );
function mbdbcafi_update_versions() {
	$current_version = get_option(MBDBCAFI_PLUGIN_VERSION_KEY);
	
	if ( $current_version == MBDBCAFI_PLUGIN_VERSION ) {
		return;
	}
	
	if ($current_version == '') {
		$current_version = '1.0';
	}


	if ( version_compare( $current_version, '2.0', '<')) {
		mbdbcafi_update_2_0();
	}


	// update database to the new version
	update_option(MBDBCAFI_PLUGIN_VERSION_KEY, MBDBCAFI_PLUGIN_VERSION);

}

function mbdbcafi_update_2_0() {

	mbdbcafi_set_all_attach_ids();

}
