<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AbandonedCart
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\AbandonedCart\Model\ResourceModel\Abandoned;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(
            \M2Commerce\AbandonedCart\Model\Abandoned::class,
            \M2Commerce\AbandonedCart\Model\ResourceModel\Abandoned::class
        );
    }
}
