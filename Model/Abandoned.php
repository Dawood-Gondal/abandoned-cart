<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AbandonedCart
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\AbandonedCart\Model;

use M2Commerce\AbandonedCart\Model\ResourceModel\Abandoned\CollectionFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;

/**
 * Class Abandoned
 */
class Abandoned extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var CollectionFactory
     */
    public $abandonedCollectionFactory;

    /**
     * @var DateTime
     */
    public $dateTime;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param CollectionFactory $abandoned
     * @param DateTime $dateTime
     * @param array $data
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     */
    public function __construct(
        Context $context,
        Registry $registry,
        CollectionFactory $abandoned,
        DateTime $dateTime,
        array $data = [],
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null
    ) {
        $this->abandonedCollectionFactory = $abandoned;
        $this->dateTime     = $dateTime;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init(\M2Commerce\AbandonedCart\Model\ResourceModel\Abandoned::class);
    }

    /**
     * @return $this|Abandoned
     */
    public function beforeSave()
    {
        parent::beforeSave();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($this->dateTime->formatDate(true));
        }
        $this->setUpdatedAt($this->dateTime->formatDate(true));

        return $this;
    }

    /**
     * @param $quoteId
     * @param $storeId
     * @return \Magento\Framework\DataObject
     */
    public function loadByQuoteId($quoteId, $storeId)
    {
        $collection = $this->abandonedCollectionFactory->create()
            ->addFieldToFilter('quote_id', $quoteId)
            ->addFieldToFilter('store_id', $storeId)
            ->setPageSize(1);

        return $collection->getFirstItem();
    }
}
