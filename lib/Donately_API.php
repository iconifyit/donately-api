<?php
/**
 * Iconfinder API access functions.
 *
 * @since      1.0.0
 *
 */


class Donately_API {

    /**
     * @var string
     */
    private static $domain       = DONATELY_ROOT_DOMAIN;

    /**
     * @var string
     */
    private static $api_url      = DONATELY_API_ENDPOINT;

    /**
     * @var string
     */
    private static $endpoint     = DONATELY_API_ENDPOINT;

    /**
     * @var string
     */
    private static $site_url     = 'https://' . DONATELY_ROOT_DOMAIN;

    /**
     * @var bool
     */
    private static $ssl_verify   = 0;

    /**
     * @var string
     */
    private static $api_token    = DONATELY_API_KEY;

    /**
     * @var string
     */
    private static $account_id   = DONATELY_ACCOUNT_ID;

    /**
     * @var string
     */
    private static $unique_id   = DONATELY_UNIQUE_ID;

    /**
     * @return string
     */
    public static function domain() {
        return self::$domain;
    }

    /**
     * @return string
     */
    public static function api_url() {
        return self::$api_url;
    }

    /**
     * @return string
     */
    public static function site_url() {
        return self::$site_url;
    }

    /**
     * @return boolean
     */
    public static function ssl_verify() {
        return self::$ssl_verify;
    }

    /**
     * Get the default HTTP headers.
     * @return array
     */
    private static function headers() {
        return array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode( self::$api_token ),
                'Accept'        => 'application/json'
            )
        );
    }

    /**
     * Get the default HTTP headers with form data content type.
     * @return array
     */
    private static function form_headers() {
        return array_merge(
            self::headers(),
            array(
                'content-type' => 'multipart/form-data; ' // boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW'
            )
        );
    }

    /**
     * Build URL query string from keyed array.
     * @param $args
     * @return string
     */
    public static function get_query( $args ) {
        $query_params = array();
        foreach ( $args as $key => $value ) {
            if ( ! empty( $value ) ) {
                $query_params[] =  sanitize_text_field( $key ) . "=" . sanitize_text_field( $value );
            }
        }
        if ( ! empty( $query_params ) ) {
            return "?" . implode( "&", $query_params );
        }
        return "";
    }

    /**
     * Shows your person.
     *
     * https://{{api_subdomain}}.{{api_host}}.com/api/v1/people/show
     *
     * @return array|mixed|null|object
     * @throws Exception
     */
    public static function get_people() {
        return self::get(
            self::$endpoint . "people/show",
            self::headers()
        );
    }

    /**
     * Retrieves list of all people in account.
     *
     * https://{{account_subdomain}}.{{dntly_api_host}}/api/v1/admin/people
     *
     * @param int $count
     * @param int $offset
     * @param string $order
     * @param bool $include_errors
     * @param string $search
     *
     * @return array|mixed|null|object
     * @throws Exception
     */
    public static function admin_get_people( $count=10, $offset=0, $order='default', $include_errors=true, $search='' ) {
        $query_params = self::get_query(array(
            'count'          => $count,
            'offset'         => $offset,
            'order'          => $order,
            'include_errors' => $include_errors,
            'search'         => $search
        ));
        return self::get(
            self::$endpoint . "admin/people{$query_params}",
            self::headers()
        );
    }

    /**
     * Retrieves a person in the account by id.
     *
     * https://{{account_subdomain}}.{{dntly_api_host}}/api/v1/admin/people/{{person_id}}
     *
     * @param $person_id
     * @param string $donation_status
     *
     * @return array|mixed|null|object
     * @throws Exception
     */
    public static function admin_get_person( $person_id, $donation_status='' ) {
        $query_params = self::get_query( array( 'donation_status' => $donation_status ) );
        return self::get(
            self::$endpoint . "admin/people/{$person_id}{$query_params}",
            self::headers()
        );
    }

    /**
     * Shows info about an account.
     *
     * https://{{account_subdomain}}.{{dntly_api_host}}/api/v1/accounts/{{account_id}}
     *
     * @return array|mixed|null|object
     * @throws Exception
     */
    public static function get_accounts() {
        return self::get(
            self::$endpoint . "accounts/" . self::$account_id,
            self::headers()
        );
    }

    /**
     * Joins your person to an account.
     *
     * https://{{account_subdomain}}.{{dntly_api_host}}/api/v1/people/join_account
     *
     * @param $account_id
     * @return array|Exception|mixed|object
     */
    public static function join_account( $account_id ) {
        return self::post(
            self::$endpoint . "people/join_account",
            array(
                'account_id' => $account_id
            ),
            self::form_headers()
        );
    }

    /**
     * Retrieves list of the existing donations for your person.
     *
     * https://{{account_subdomain}}.{{dntly_api_host}}/api/v1/donations
     *
     * @return array|mixed|null|object
     * @throws Exception
     */
    public static function get_donations() {
        return self::get(
            self::$endpoint . "accounts/" . self::$account_id,
            self::headers()
        );
    }

    /**
     * Updates the donation.
     *
     * https://{{account_subdomain}}.{{dntly_api_host}}/api/v1/donations/{{donation_id}}/update
     *
     * @param $donation_id
     * @param $email
     * @return array|Exception|mixed|object
     */
    public static function update_donation( $donation_id, $email ) {
        return self::post(
            self::$endpoint . "donations/{$donation_id}/update",
            array(
                'email' => $email
            ),
            self::form_headers()
        );
    }

    /**
     * Retrieves list of fundraisers for your person.
     *
     * https://{{account_subdomain}}.{{dntly_api_host}}/api/v1/fundraisers
     *
     * @return array|mixed|null|object
     * @throws Exception
     */
    public static function get_fundraisers() {
        return self::get(
            self::$endpoint . "fundraisers",
            self::headers()
        );
    }

    /**
     * Retrieves a fundraiser for your person.
     *
     * https://{{account_subdomain}}.{{dntly_api_host}}/api/v1/fundraisers/{{fundraiser_id}}
     *
     * @param $fundraiser_id
     * @return array|mixed|null|object
     * @throws Exception
     */
    public static function get_fundraiser( $fundraiser_id ) {
        return self::get(
            self::$endpoint . "fundraisers/" . $fundraiser_id,
            self::headers()
        );
    }

    /**
     * Create a fundraiser.
     *
     * https://{{account_subdomain}}.{{dntly_api_host}}/api/v1/fundraisers
     *
     * @param $title
     * @param $description
     * @param $goal_in_cents
     * @param $campaign_id
     * @param $email
     *
     * @return array|Exception|mixed|object
     */
    public static function create_fundraiser( $title, $description, $goal_in_cents, $campaign_id, $email ) {

        try {
            // Sanitize values.
            $title         = sanitize_text_field( $title );
            $description   = sanitize_text_field( $description );
            $goal_in_cents = is_int( $goal_in_cents ) ? $goal_in_cents : 0 ;
            $campaign_id   = is_numeric( $campaign_id ) ? $campaign_id : -1 ;
            $email         = sanitize_email( $email );

            // Validate values.
            if ( empty( $email ) ) {
                return new WP_Error( __( 'Email is required to create a fundraiser.', 'throwing-bones' ) );
            }
            if ( empty( $campaign_id ) ) {
                return new WP_Error( __( 'Campaign ID is required to create a fundraiser.', 'throwing-bones' ) );
            }

            // Set a default title.
            if ( empty( $title ) ) $title = "{$email}'s Fundraiser";

            // Set the minimum goal to $10.
            if ( $goal_in_cents < 1000 ) $goal_in_cents = 1000;

            // Set a default description.
            if ( empty ( $description ) ) {
                $description = __(
                    $title . " to help Kenny Capps raise money for Multiple Myeloma research.",
                    'throwing-bones'
                );
            }

            $response = self::post(
                self::$endpoint . "fundraisers",
                array(
                    'title'         => $title,
                    'description'   => $description,
                    'goal_in_cents' => $goal_in_cents,
                    'campaign_id'   => $campaign_id,
                    'email'         => $email
                ),
                self::form_headers()
            );

            if ( empty( $response ) ) {
                $response = new WP_Error(
                    'DNTLY_ENDPOINT_ERROR',
                    __( 'No response from Donately API endpoint ' , 'throwing-bones' )
                );
            }

            return $response;
        }
        catch( Exception $e ) {
            return new WP_Error( $e );
        }
    }

    /**
     * @param string    $api_url    The URL to which to post the request.
     * @param array     $body       The body of the request (key => value pairs).
     * @param array     $headers    The headers for the request (key => value pairs).
     *
     * @return array|Exception|mixed|object
     */
    public static function post( $api_url, $body, $headers ) {
        try {
            $response = json_decode(
                wp_remote_retrieve_body(
                    wp_remote_post(
                        $api_url,
                        array(
                            'body'        => $body,
                            'timeout'     => '5',
                            'redirection' => '5',
                            'httpversion' => '1.0',
                            'blocking'    => true,
                            'headers'     => $headers,
                            'cookies'     => array()
                        )
                    )
                ),
                true
            );
        }
        catch(Exception $e) {
            $response = $e;
        }
        return $response;
    }

    /**
     * Makes the api call.
     * @param $api_url The url to which to make the call
     * @param string $cache_key A unique key matching the call path for caching the results
     * @param bool $from_cache Whether or not to pull requests from the cache first
     * @return array|mixed|null|object
     * @throws Exception
     */
    public static function get( $api_url, $headers=array() ) {

        // Always try the local cache first. If we get a hit, just return the stored data.

        $response = null;

        $from_cache = false;

        $cache_key = 'tbr_donately_' . base64_encode( $api_url );

        if ( $from_cache ) {
            $response = Utils::get_option( $cache_key );
        }

        // If there is no cached data, make the API cale.

        if ( empty($response) || ! $from_cache ) {
            try {
                $response = json_decode(
                    wp_remote_retrieve_body(
                        wp_remote_get(
                            $api_url,
                            $headers
                        )
                    ),
                    true
                );

                $response['from_cache'] = 0;

                # Utils::update_option($option_key, $response);

//                if (trim($option_key) != '') {
//                    if ( update_option( $option_key, $response ) ) {
//                        $stored_keys = get_option( 'icf_cache_keys', array() );
//                        if ( ! in_array( $option_key, $stored_keys ) )  {
//                            array_push( $stored_keys, $option_key );
//                            update_option('icf_cache_keys', $stored_keys, 'no');
//                        }
//                    }
//                }
            }
            catch( Exception $e ) {
                Utils::debug(array(
                    'api_url' => $api_url,
                    'exceptionn' => $e
                ), false );
            }
        }

        if ( $response == null && trim( $cache_key ) != '') {
            # $response = get_option( $option_key );
        }

        return $response;
    }
}