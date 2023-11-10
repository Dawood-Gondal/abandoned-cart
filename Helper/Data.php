<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AbandonedCart
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\AbandonedCart\Helper;

use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\Config\Storage\Writer;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Data
 */
class Data extends AbstractHelper
{

    /**
     * @var StoreManagerInterface
     */
    public $storeManager;

    /**
     * @var CustomerFactory
     */
    public $customerFactory;

    /**
     * @var Writer
     */
    public $writer;

    /**
     * @var DirectoryList
     */
    public $directoryList;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param CustomerFactory $customerFactory
     * @param Writer $writer
     * @param DirectoryList $directoryList
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        CustomerFactory $customerFactory,
        Writer $writer,
        DirectoryList $directoryList,
        LoggerInterface $logger
    ) {
        $this->storeManager = $storeManager;
        $this->customerFactory = $customerFactory;
        $this->writer = $writer;
        $this->directoryList = $directoryList;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * @param $default
     * @return \Magento\Store\Api\Data\StoreInterface[]
     */
    public function getStores($default = false)
    {
        return $this->storeManager->getStores($default);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getEmailCaptureUrl()
    {
        return $this->storeManager->getStore()->getUrl(
            'abandoned_cart/ajax/emailCapture',
            ['_secure' => $this->storeManager->getStore()->isCurrentlySecure()]
        );
    }

    /**
     * @param $data
     * @return void
     */
    public function log($data)
    {
        $this->logger->info($data);
    }

    /**
     * @param $id
     * @return string
     */
    public function getCustomerName($id = null)
    {
        try {
            if ($id) {
                $customer = $this->customerFactory->create()->load($id);
                return $customer->getName();
            }
        } catch (\Exception $exception) {
        }
        return 'Guest User';
    }
}
