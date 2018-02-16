<?php
/**
 * Utility class
 */

class Utils {

	private static $admin_notices;
    
    /**
     * Returns the requested value or default if empty
     * @param mixed $subject
     * @param string $key
     * @param mixed $default
     * @return mixed
     *
     * @since 1.1.0
     */
    public static function get($subject, $key, $default=null) {
        $value = $default;
        if (is_array($subject)) {
            if (isset($subject[$key])) {
                $value = $subject[$key];
            }
        }
        else if (is_object($subject)) {
            if (isset($subject->$key)) {
                $value = $subject->$key;
            }
        }
        else if (! empty($subject)) {
            $value = $subject;
        }
        return $value;
    }

    /**
     * Tests a mixed variable for true-ness.
     * @param int|null|bool|string $value
     * @param null|string|bool|int $default
     * @return bool|null
     */
    public static function is_true($value, $default=null) {
        $result = $default;
        $trues  = array(1, '1', 'true', true, 'yes', 'da', 'si', 'oui', 'absolutment', 'yep', 'yeppers', 'fuckyeah');
        $falses = array(0, '0', 'false', false, 'no', 'non', 'nein', 'nyet', 'nope', 'nowayjose');
        if (in_array(strtolower($value), $trues, true)) {
            $result = true;
        }
        else if (in_array(strtolower($value), $falses, true)) {
            $result = false;
        }
        return $result;
    }

    /**
     * This is a debug function and ideally should be removed from the production code.
     * @param array|object  $what   The object|array to be printed
     * @param bool          $die    Whether or not to die after printing the object
     * @return string
     */
    public static function dump($what, $die=true) {

        if (is_string( $what )) $what = array( 'debug' => $what );
        $output = sprintf( '<pre>%s</pre>', print_r($what, true) );
        if ( $die ) die( $output );
        return $output;
    }

    /**
     * This is an alias for Utils::dump()
     * @param array|object  $what   The object|array to be printed
     * @param bool          $die    Whether or not to die after printing the object
     * @return string
     */
    public static function debug($what, $die=true) {

        return Utils::dump( $what, $die );
    }

    /**
     * Buffers the output from a file and returns the contents as a string.
     * You can pass named variables to the file using a keyed array.
     * For instance, if the file you are loading accepts a variable named
     * $foo, you can pass it to the file  with the following:
     *
     * @example
     *
     *      do_buffer('path/to/file.php', array('foo' => 'bar'));
     *
     * @param string $path
     * @param array $vars
     * @return string
     */
    public static function buffer( $path, $vars=null ) {
        $output = null;
        if (! empty($vars)) {
            extract($vars);
        }
        if (file_exists( $path )) {
            ob_start();
            include_once( $path );
            $output = ob_get_contents();
            ob_end_clean();
        }
        return $output;
    }

	/**
	 * Format a WP admin messages error.
	 * @param string	$message	The error message string (do not localize).
	 * @param string	$level		The message level class (info, error, success, warning)
	 * @param boolean	$dismiss	Whether or not the notice is dissmissible
	 * @return string	Returns a string if not printed immediate.
	 */
	public static function get_admin_notice( $message, $level='info', $dismiss=false ) {
		$dismissible = $dismiss ? 'is-dismissible' : '' ;
		return sprintf( "<div class=\"notice notice-{$level} {$dismissible}\"><p>%s</p></div>", $message );
	}
	
	/**
	 * Print a WP admin message.
	 * @param string	$message	The error message string (do not localize).
	 * @param string	$level		The message level class (info, error, success, warning)
	 * @param boolean	$dismiss	Whether or not the notice is dissmissible
	 * @return void
	 */
	public static function admin_notice( $message, $level='info', $dismiss=false ) {
		echo self::get_admin_notice( $message, $level, $dismiss );
	}
	
	/**
	 * Adds a callback to fire when admin_messages action is executed.
	 * @param string	$message	The error message string (do not localize).
	 * @param string	$level		The message level class (info, error, success, warning)
	 * @param boolean	$dismiss	Whether or not the notice is dissmissible
	 * @returns void
	 */
	function add_admin_notice( $message, $level='info', $dismiss=false ) {

		if ( ! is_array( self::$admin_notices ) ) {
		    self::$admin_notices = array();
		    add_action( 'admin_notices', 'Utils::show_admin_notices' );
		}
		
		self::$admin_notices[] = Utils::get_admin_notice( $message, $level, $dismiss );
	}
	
	/**
	 * Callback to display previously queued admin notices.
	 */
	public static function show_admin_notices() {
		if ( is_array( self::$admin_notices ) )  {
		    foreach ( self::$admin_notices as $notice ) {
		        echo $notice;
		    }
		}
	}
	
	/**
     * Store data in the cache.
     * @param string    $key            The key of the option to be saved.
     * @param mixed     $value          The value of the option.
     * @param int      $expiration      The time until the transient expires.
     * @return bool
     */
    public static function set_option( $key, $value, $expiration = 0  ) {

        return set_transient( $key, $value, $expiration );
    }

    /**
     * Retrieve a cached value.
     * @param string    $key        The key of the option to retrieve.
     * @param null      $default    The default value to return if the option is not set or is empty.
     *
     * @return mixed|null
     */
    public static function get_option($key, $default=null ) {

        $cache = get_transient( $key );
        return empty( $cache ) ? $default : $cache ;
    }

    /**
     * Update a previously cached item.
     * @param string    $cache_key  The unique identifier for the cached item.
     * @param mixed     $data       The data to be cached.
     * @param string    $prefix     A prefix for the cache key (for grouping cached items)
     * @return void
     */
    public static function update_option($cache_key, $data, $prefix='' ) {
        // If the key or data is empty, ignore.
        if ( empty( $cache_key ) || empty( $data ) ) return;

        // If the cache key does not already have the prefix, add it.
        if ( strcasecmp( array_shift( explode('_', $cache_key) ), $prefix ) != 0 ) {
            $cache_key = "{$prefix}_{$cache_key}";
        }

        // Get the name of the current plugin.
        $cache_name = self::plugin_name() . "_cache_keys";

        if ( update_option( $cache_key, $data ) ) {
            $stored_keys = get_option( $cache_name, array() );
            if ( ! in_array( $cache_key, $stored_keys ) ) {
                array_push( $stored_keys, $cache_key );
                update_option( $cache_name, $stored_keys, 'no' );
            }
        }
    }

    /**
     * Create a unique cache key for a request path.
     * @param string $prefix    A string prefix for the cache key in form 'myprefix'.
     *
     * @return string
     */
    public static function option_key( $prefix='' ) {
        $cache_key = implode('_', explode('/', $path ) );
        if ( ! empty( $prefix ) ) {
            $cache_key = "{$prefix}_$cache_key";
        }
        return $cache_key;
    }
    
    /**
     * Get the plugin directory name from the file path.
     * @param string $file
     *
     * @return string
     */
    public static function plugin_name( $file=__FILE__ ) {
        return basename( dirname( plugin_dir_path($file ) ) );
    }
    
    /**
     * Get the current WP context.
     * @return string
     */
    public static function wp_context() {

        $context = 'index';

        if ( is_home() ) {
            // Blog Posts Index
            $context = 'home';
            if ( is_front_page() ) {
                // Front Page
                $context = 'front-page';
            }
        }
        else if ( is_date() ) {
            // Date Archive Index
            $context = 'date';
        }
        else if ( is_author() ) {
            // Author Archive Index
            $context = 'author';
        }
        else if ( is_category() ) {
            // Category Archive Index
            $context = 'category';
        }
        else if ( is_tag() ) {
            // Tag Archive Index
            $context = 'tag';
        }
        else if ( is_tax() ) {
            // Taxonomy Archive Index
            $context = 'taxonomy';
        }
        else if ( is_archive() ) {
            // Archive Index
            $context = 'archive';
        }
        else if ( is_search() ) {
            // Search Results Page
            $context = 'search';
        }
        else if ( is_404() ) {
            // Error 404 Page
            $context = '404';
        }
        else if ( is_attachment() ) {
            // Attachment Page
            $context = 'attachment';
        }
        else if ( is_single() ) {
            // Single Blog Post
            $context = 'single';
        }
        else if ( is_page() ) {
            // Static Page
            $context = 'page';
        }
        return $context;
    }
}