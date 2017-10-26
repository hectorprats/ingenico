<?php
/*
* Config for the ingenico hosted checkout payment
* locales
* https://preprod.account.ingenico.com/#/merchantsetup/1155/languagepacks
*/
return array(
    'api_key'                   => '',
    'secret_key'                => '',
    'end_point'                 => '',
    'merchant'                  => '',
    'return_url'                => '',
    'base_redirect_url'         => '',
    'locales'                   => [
                                'en'    => ['GB', 'AU', 'CA', 'IE', 'NZ', 'US'],
                                'es'    => ['ES', 'AR', 'CO', 'MX'],
                                'fr'    => ['FR', 'BE', 'CA'],
                                'de'    => ['DE', 'AT', 'CH'],
                                'pt'    => ['BR', 'PT'],
                                'nl'    => ['NL', 'BE'],
                                'it'    => ['IT'],
                                'pl'    => ['PL'],
        ],
);
