<?php

/**
 * Wrapper for adding admin notice. The Utils::add_admin_notice adds the WP action hook.
 * @param string	$message	The message to display.
 * @param string 	$level		The level of the notice (success, info, warning, error).
 * @param bool		$dismiss	Whether or not the notice can be dismissed by the user.
 */
function add_admin_notice( $message, $level='info', $dismiss=false ) {
    Utils::add_admin_notice( $message, $level, $dismiss );
}

function donately_test_api( $form_data=array() ) {

    $response = Donately_API::get_people();
    Utils::dump( $response );
}

donately_test_api();