<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AbandonedCart
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\AbandonedCart\Model\ResourceModel;

use M2Commerce\AbandonedCart\Helper\Data;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class Abandoned
 */
class Abandoned extends AbstractDb
{
    /**
     * @var Data
     */
    public $helper;

    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init('m2c_abandoned_cart', 'id');
    }

    /**
     * @param Context $context
     * @param Data $data
     */
    public function __construct(
        Context $context,
        Data $data
    ) {
        $this->helper = $data;
        parent::__construct($context);
    }
}
