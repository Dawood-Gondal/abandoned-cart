/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AbandonedCart
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

var config = {
    'paths': {
        'dmpt': 'M2Commerce_AbandonedCart/js/dmpt',
        'stick-to-me' : 'M2Commerce_AbandonedCart/js/stick-to-me'
    },
    'shim': {
        'dmpt': {
            exports: '_dmTrack',
            deps: ['jquery']
        },
        'stick-to-me': {
            deps: ['jquery']
        }
    }
};
