<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AbandonedCart
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\AbandonedCart\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Campaign
 */
class Campaign extends AbstractDb
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init('m2c_abandoned_cart_email_campaign', 'id');
    }
}
