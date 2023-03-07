<?php

function mg_get_template( $template_name, $args = array() ) {
	wc_get_template( $template_name, $args, '', MG_TEMPLATES_PATH );
}

function mg_get_saved_location_name() {
	$user = MG_User::current();

	$location_id = $user->get_location();

	$location = MG_Location::get_location( $location_id );

	$name = '';

	if ( $location ) {
		$name = $location[ 'name' ];
	}

	return $name;
}

function d( $var ) {
	echo "<pre>";
	var_dump( $var );
	echo "</pre>";
}

function dd( $var ) {
	d( $var );
}