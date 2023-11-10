/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AbandonedCart
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */
require([
    'jquery',
    'stick-to-me',
], function ($) {
    $(document).ready(
        function () {
            $.stickToMe(
                {
                    layer: '#abandoned_cart_stick_layer',
                    trigger: ['all'],
                    fadespeed: 0,
                    maxamount : 1,
                    maxtime: 0,
                    bgclickclose: false,
                    escclose: false
                }
            );

        }
    );
});
