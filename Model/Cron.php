<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AbandonedCart
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\AbandonedCart\Model;

use M2Commerce\AbandonedCart\Helper\Data;
use M2Commerce\AbandonedCart\Model\ResourceModel\Cron\CollectionFactory;
use M2Commerce\AbandonedCart\Model\Sales\QuoteFactory;

/**
 * Class Cron
 */
class Cron
{
    /**
     * @var QuoteFactory
     */
    public $quoteFactory;

    /**
     * @var Data
     */
    public $helper;

    /**
     * @var CollectionFactory
     */
    public $cronCollection;

    /**
     * @param QuoteFactory $quoteFactory
     * @param Data $helper
     * @param CollectionFactory $cronCollection
     */
    public function __construct(
        QuoteFactory $quoteFactory,
        Data $helper,
        CollectionFactory $cronCollection
    ) {
        $this->quoteFactory = $quoteFactory;
        $this->helper = $helper;
        $this->cronCollection = $cronCollection;
    }

    /**
     * @return void
     */
    public function abandonedCarts()
    {
        if ($this->jobHasAlreadyBeenRun('m2comm_abandoned_cart')) {
            $this->helper->log('Skipping m2comm_abandoned_cart job run');
            return;
        }
        $this->quoteFactory->create()->processAbandonedCarts();
    }

    /**
     * @param $jobCode
     * @return bool
     */
    public function jobHasAlreadyBeenRun($jobCode)
    {
        $currentRunningJob = $this->cronCollection->create()
            ->addFieldToFilter('job_code', $jobCode)
            ->addFieldToFilter('status', 'running')
            ->setPageSize(1);

        if ($currentRunningJob->getSize()) {
            $jobOfSameTypeAndScheduledAtDateAlreadyExecuted =  $this->cronCollection->create()
                ->addFieldToFilter('job_code', $jobCode)
                ->addFieldToFilter('scheduled_at', $currentRunningJob->getFirstItem()->getScheduledAt())
                ->addFieldToFilter('status', ['in' => ['success', 'failed']]);

            return ($jobOfSameTypeAndScheduledAtDateAlreadyExecuted->getSize()) ? true : false;
        }

        return false;
    }
}
