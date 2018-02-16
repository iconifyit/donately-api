<?php
/**
 * Include settings here that do not need to be editable via
 * web browser or need to be kept secure.
 */


defined( 'DONATELY_ENV' ) or define( 'DONATELY_ENV', 'production' );

if ( ! defined( 'DONATELY_API_ENDPOINT' ) ) {
    if ( DONATELY_ENV == 'staging' ) {
        define( 'DONATELY_API_ENDPOINT', 'http://throwingbonesrun.dntly-staging.com/api/v1/' );
    }
    else {
        define( 'DONATELY_API_ENDPOINT', 'https://throwingbonesrun.dntly.com/api/v1/' );
    }
}

defined( 'DONATELY_ROOT_DOMAIN' )  or define( 'DONATELY_ROOT_DOMAIN',    'donately.com' );
defined( 'DONATELY_API_URL' )      or define( 'DONATELY_API_URL' ,       'https://throwingbonesrun.dntly.com/api/v1/' );
defined( 'DONATELY_CAMPAIGN_ID' )  or define( 'DONATELY_CAMPAIGN_ID',    'cmp_20cb61218664' );
defined( 'DONATELY_ACCOUNT_ID' )   or define( 'DONATELY_ACCOUNT_INT_ID',  6121 );
defined( 'DONATELY_API_KEY' )      or define( 'DONATELY_API_KEY',        'e6c120a39c76393aff4a9451ed8a163b' );
defined( 'DONATELY_API_KEY_B64' )  or define( 'DONATELY_API_KEY_B64',    base64_encode( DONATELY_API_KEY ) );
defined( 'DONATELY_ACCOUNT_ID' )   or define( 'DONATELY_ACCOUNT_ID',     14287 );
defined( 'DONATELY_UNIQUE_ID' )    or define( 'DONATELY_UNIQUE_ID',      'act_0ae3b9dfc598' );
