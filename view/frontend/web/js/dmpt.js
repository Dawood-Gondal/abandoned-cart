/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AbandonedCart
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

define([
    'domReady!'], function () {
    'use strict';

    /**
     * Create script tag
     */
    function createTag(regionPrefix)
    {
        var connector = document.createElement('script'),
            s = document.getElementsByTagName('script')[0];

        connector.type = 'text/javascript';
        connector.src = '//' + regionPrefix + 't.trackedlink.net/_dmpt.js';
        s.parentNode.insertBefore(connector, s);
    }

    /**
     * Export/return tracking code init
     *
     * @param {Object} trackingCode
     */
    return function (trackingCode) {
        createTag(trackingCode.regionPrefix);
    };
});

