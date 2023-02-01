<?php

function mg_get_template( $template_name, $args = array() ) {
	wc_get_template( $template_name, $args, '', MG_TEMPLATES_PATH );
}

function d( $var ) {
	echo "<pre>";
	var_dump( $var );
	echo "</pre>";
}

function dd( $var ) {
	d( $var );
}