<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AbandonedCart
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\AbandonedCart\Model\ResourceModel\Cron;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'schedule_id';

    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(
            \Magento\Cron\Model\Schedule::class,
            \Magento\Cron\Model\ResourceModel\Schedule::class
        );
    }
}
