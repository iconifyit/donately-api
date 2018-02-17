<?php

require_once( plugin_dir_path( __DIR__ ) . 'wp-orm/vendor/autoload.php' );

$include_paths = array();
$include_paths = array_merge( $include_paths, glob( plugin_dir_path( __FILE__ ) . "lib/*.php" ) );

foreach ( $include_paths as $file ) {
    require_once $file;
}
