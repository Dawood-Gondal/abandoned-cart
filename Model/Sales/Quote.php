<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AbandonedCart
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\AbandonedCart\Model\Sales;

use M2Commerce\AbandonedCart\Helper\Data;
use M2Commerce\AbandonedCart\Model\AbandonedFactory;
use M2Commerce\AbandonedCart\Model\CampaignFactory;
use M2Commerce\AbandonedCart\Model\ResourceModel\Abandoned;
use M2Commerce\AbandonedCart\Model\ResourceModel\Campaign;
use M2Commerce\AbandonedCart\Model\ResourceModel\Campaign\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\View\Element\Template;
use Magento\Quote\Model\ResourceModel\Quote\Collection as QuoteCollection;
use Magento\Quote\Model\ResourceModel\Quote\CollectionFactory as QuoteCollectionFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Quote
 */
class Quote
{
    //customer
    const XML_PATH_LOSTBASKET_CUSTOMER_ENABLED_1 = 'abandoned_cart/customers/enabled_1';
    const XML_PATH_LOSTBASKET_CUSTOMER_ENABLED_2 = 'abandoned_cart/customers/enabled_2';
    const XML_PATH_LOSTBASKET_CUSTOMER_ENABLED_3 = 'abandoned_cart/customers/enabled_3';
    const XML_PATH_LOSTBASKET_CUSTOMER_ENABLED_4 = 'abandoned_cart/customers/enabled_4';
    const XML_PATH_LOSTBASKET_CUSTOMER_INTERVAL_1 = 'abandoned_cart/customers/send_after_1';
    const XML_PATH_LOSTBASKET_CUSTOMER_INTERVAL_2 = 'abandoned_cart/customers/send_after_2';
    const XML_PATH_LOSTBASKET_CUSTOMER_INTERVAL_3 = 'abandoned_cart/customers/send_after_3';
    const XML_PATH_LOSTBASKET_CUSTOMER_INTERVAL_4 = 'abandoned_cart/customers/send_after_4';
    const XML_PATH_LOSTBASKET_CUSTOMER_TEMPLATE_1 = 'abandoned_cart/customers/template1';
    const XML_PATH_LOSTBASKET_CUSTOMER_TEMPLATE_2 = 'abandoned_cart/customers/template2';
    const XML_PATH_LOSTBASKET_CUSTOMER_TEMPLATE_3 = 'abandoned_cart/customers/template3';
    const XML_PATH_LOSTBASKET_CUSTOMER_TEMPLATE_4 = 'abandoned_cart/customers/template4';

    //guest
    const XML_PATH_LOSTBASKET_GUEST_ENABLED_1 = 'abandoned_cart/guests/enabled_1';
    const XML_PATH_LOSTBASKET_GUEST_ENABLED_2 = 'abandoned_cart/guests/enabled_2';
    const XML_PATH_LOSTBASKET_GUEST_ENABLED_3 = 'abandoned_cart/guests/enabled_3';
    const XML_PATH_LOSTBASKET_GUEST_ENABLED_4 = 'abandoned_cart/guests/enabled_4';
    const XML_PATH_LOSTBASKET_GUEST_INTERVAL_1 = 'abandoned_cart/guests/send_after_1';
    const XML_PATH_LOSTBASKET_GUEST_INTERVAL_2 = 'abandoned_cart/guests/send_after_2';
    const XML_PATH_LOSTBASKET_GUEST_INTERVAL_3 = 'abandoned_cart/guests/send_after_3';
    const XML_PATH_LOSTBASKET_GUEST_INTERVAL_4 = 'abandoned_cart/guests/send_after_4';
    const XML_PATH_LOSTBASKET_GUEST_TEMPLATE_1 = 'abandoned_cart/guests/template1';
    const XML_PATH_LOSTBASKET_GUEST_TEMPLATE_2 = 'abandoned_cart/guests/template2';
    const XML_PATH_LOSTBASKET_GUEST_TEMPLATE_3 = 'abandoned_cart/guests/template3';
    const XML_PATH_LOSTBASKET_GUEST_TEMPLATE_4 = 'abandoned_cart/guests/template4';

    const CUSTOMER_LOST_BASKET_ONE = 1;
    const CUSTOMER_LOST_BASKET_TWO = 2;
    const CUSTOMER_LOST_BASKET_THREE = 3;
    const CUSTOMER_LOST_BASKET_FOUR = 4;
    const GUEST_LOST_BASKET_ONE = 1;
    const GUEST_LOST_BASKET_TWO = 2;
    const GUEST_LOST_BASKET_THREE = 3;
    const GUEST_LOST_BASKET_FOUR = 4;

    /**
     * @var int
     */
    public $totalCustomers = 0;

    /**
     * @var int
     */
    public $totalGuests = 0;

    /**
     * @var AbandonedFactory
     */
    public $abandonedFactory;

    /**
     * @var Abandoned\CollectionFactory
     */
    public $abandonedCollectionFactory;

    /**
     * @var QuoteCollectionFactory
     */
    public $quoteCollectionFactory;

    /**
     * @var Campaign
     */
    public $campaignResource;

    /**
     * @var Data
     */
    public $helper;

    /**
     * @var ScopeConfigInterface
     */
    public $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    public $storeManager;

    /**
     * @var CampaignFactory
     */
    public $campaignFactory;

    /**
     * @var CollectionFactory
     */
    public $campaignCollection;

    /**
     * @var TimezoneInterface
     */
    public $timeZone;

    /**
     * @var Abandoned
     */
    public $abandonedResource;

    /**
     * @var Template
     */
    public $frameworkTemplate;

    /**
     * @var TransportBuilder
     */
    public $transportBuilder;

    /**
     * @var StateInterface
     */
    public $inlineTranslation;

    /**
     * @param AbandonedFactory $abandonedFactory
     * @param CollectionFactory $campaignCollection
     * @param Campaign $campaignResource
     * @param CampaignFactory $campaignFactory
     * @param Data $helper
     * @param Abandoned\CollectionFactory $abandonedCollectionFactory
     * @param Abandoned $abandonedResource
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param QuoteCollectionFactory $quoteCollectionFactory
     * @param TimezoneInterface $timezone
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param Template $frameworkTemplate
     */
    public function __construct(
        AbandonedFactory            $abandonedFactory,
        CollectionFactory           $campaignCollection,
        Campaign                    $campaignResource,
        CampaignFactory             $campaignFactory,
        Data                        $helper,
        Abandoned\CollectionFactory $abandonedCollectionFactory,
        Abandoned                   $abandonedResource,
        StoreManagerInterface       $storeManager,
        ScopeConfigInterface        $scopeConfig,
        QuoteCollectionFactory      $quoteCollectionFactory,
        TimezoneInterface           $timezone,
        TransportBuilder            $transportBuilder,
        StateInterface              $inlineTranslation,
        Template                    $frameworkTemplate
    ) {
        $this->helper = $helper;
        $this->abandonedFactory = $abandonedFactory;
        $this->abandonedCollectionFactory = $abandonedCollectionFactory;
        $this->abandonedResource = $abandonedResource;
        $this->campaignCollection = $campaignCollection;
        $this->campaignResource = $campaignResource;
        $this->campaignFactory = $campaignFactory;
        $this->storeManager = $storeManager;
        $this->quoteCollectionFactory = $quoteCollectionFactory;
        $this->scopeConfig = $scopeConfig;
        $this->timeZone = $timezone;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->frameworkTemplate = $frameworkTemplate;
    }

    /**
     * @return array
     */
    public function processAbandonedCarts()
    {
        $result = [];
        $stores = $this->helper->getStores();

        foreach ($stores as $store)
        {
            $storeId = $store->getId();
            $websiteId = $store->getWebsiteId();
            $firstCustomerEnabled = $this->isLostBasketCustomerEnabled(self::CUSTOMER_LOST_BASKET_ONE, $storeId);
            $secondCustomerEnabled = $this->isLostBasketCustomerEnabled(self::CUSTOMER_LOST_BASKET_TWO, $storeId);
            $thirdCustomerEnabled = $this->isLostBasketCustomerEnabled(self::CUSTOMER_LOST_BASKET_THREE, $storeId);
            $fourthCustomerEnabled = $this->isLostBasketCustomerEnabled(self::CUSTOMER_LOST_BASKET_FOUR, $storeId);

            $firstGuestEnabled = $this->isLostBasketGuestEnabled(self::GUEST_LOST_BASKET_ONE, $storeId);
            $secondGuestEnabled = $this->isLostBasketGuestEnabled(self::GUEST_LOST_BASKET_TWO, $storeId);
            $thirdGuestEnabled = $this->isLostBasketGuestEnabled(self::GUEST_LOST_BASKET_THREE, $storeId);
            $fourthGuestEnabled = $this->isLostBasketGuestEnabled(self::GUEST_LOST_BASKET_FOUR, $storeId);
            //customer
            if ($firstCustomerEnabled) {
                $result[$storeId]['firstCustomer'] = $this->processCustomerFirstAbandonedCart($storeId);
            }

            //second customer
            if ($firstCustomerEnabled && $secondCustomerEnabled) {
                $result[$storeId]['secondCustomer'] = $this->processExistingAbandonedCart(
                    $storeId,
                    $websiteId,
                    self::CUSTOMER_LOST_BASKET_TWO
                );
            }

            //third customer
            if ($firstCustomerEnabled && $secondCustomerEnabled && $thirdCustomerEnabled) {
                $result[$storeId]['thirdCustomer'] = $this->processExistingAbandonedCart(
                    $storeId,
                    $websiteId,
                    self::CUSTOMER_LOST_BASKET_THREE
                );
            }

            //fourth customer
            if ($firstCustomerEnabled && $secondCustomerEnabled && $fourthCustomerEnabled) {
                $result[$storeId]['fourthCustomer'] = $this->processExistingAbandonedCart(
                    $storeId,
                    $websiteId,
                    self::CUSTOMER_LOST_BASKET_FOUR
                );
            }

            //guest
            if ($firstGuestEnabled) {
                $result[$storeId]['firstGuest'] = $this->processGuestFirstAbandonedCart($storeId);
            }

            //second guest
            if ($firstGuestEnabled && $secondGuestEnabled) {
                $result[$storeId]['secondGuest'] = $this->processExistingAbandonedCart(
                    $storeId,
                    $websiteId,
                    self::GUEST_LOST_BASKET_TWO,
                    true
                );
            }
            //third guest
            if ($firstGuestEnabled && $secondGuestEnabled && $thirdGuestEnabled) {
                $result[$storeId]['thirdGuest'] = $this->processExistingAbandonedCart(
                    $storeId,
                    $websiteId,
                    self::GUEST_LOST_BASKET_THREE,
                    true
                );
            }
            //fourth guest
            if ($firstGuestEnabled && $secondGuestEnabled && $thirdGuestEnabled && $fourthGuestEnabled) {
                $result[$storeId]['fourthGuest'] = $this->processExistingAbandonedCart(
                    $storeId,
                    $websiteId,
                    self::GUEST_LOST_BASKET_FOUR,
                    true
                );
            }
        }

        return $result;
    }

    /**
     * @param $storeId
     * @return int
     * @throws \Exception
     */
    public function processCustomerFirstAbandonedCart($storeId)
    {
        $result = 0;
        $abandonedNum = 1;
        $sendAfter = $this->getSendAfterIntervalForCustomerLogged($storeId, $abandonedNum);
        $fromTime = new \DateTime('now', new \DateTimezone('UTC'));
        $fromTime->sub($sendAfter);
        $toTime = clone $fromTime;
        $fromTime->sub(\DateInterval::createFromDateString('10 mins'));
        //format time
        $fromDate = $fromTime->format('Y-m-d H:i:s');
        $toDate = $toTime->format('Y-m-d H:i:s');
        //active quotes
        $quoteCollection = $this->getStoreQuotes($fromDate, $toDate, false, $storeId);
        if ($quoteCollection->getSize()) {
            $this->helper->log('Customer 1 Abandoned Cart: ' . $fromDate . ' - ' . $toDate);
        }

        foreach ($quoteCollection as $quote)
        {
            $quoteId = $quote->getId();
            $storeId = $quote->getStoreId();
            $items = $quote->getAllItems();
            $email = $quote->getCustomerEmail();
            $itemIds = $this->getQuoteItemIds($items);

            $abandonedModel = $this->abandonedFactory->create()->loadByQuoteId($quoteId, $storeId);
            if ($this->abandonedCartAlreadyExists($abandonedModel) && $this->shouldNotSendAbandonedCartAgain($abandonedModel, $quote)) {
                if ($this->shouldDeleteAbandonedCart($quote)) {
                    $this->deleteAbandonedCart($abandonedModel);
                }
                continue;
            }

            //create abandoned cart
            $this->createAbandonedCart($abandonedModel, $quote, $itemIds);
            $template = $this->getLostBasketCustomerTemplate($abandonedNum, $storeId);

            //send campaign; check if valid to be sent
            if ($this->isLostBasketCustomerEnabled(self::CUSTOMER_LOST_BASKET_ONE, $storeId)) {
                $this->sendEmailCampaign($email, $quote, self::CUSTOMER_LOST_BASKET_ONE, $template);
            }
            $this->totalCustomers++;
            $result = $this->totalCustomers;
        }

        return $result;
    }

    /**
     * @param $storeId
     * @return int
     * @throws NoSuchEntityException
     */
    public function processGuestFirstAbandonedCart($storeId)
    {
        $result = 0;
        $abandonedNum = 1;
        $sendAfter = $this->getSendAfterIntervalForGuest($storeId, $abandonedNum);
        $fromTime = new \DateTime('now', new \DateTimezone('UTC'));
        $fromTime->sub($sendAfter);
        $toTime = clone $fromTime;
        $fromTime->sub(\DateInterval::createFromDateString('10 mins'));
        //format time
        $fromDate   =  $fromTime->format('Y-m-d H:i:s');
        $toDate     =  $toTime->format('Y-m-d H:i:s');
        //active quotes
        $quoteCollection = $this->getStoreQuotes($fromDate, $toDate, true, $storeId);

        if ($quoteCollection->getSize()) {
            $this->helper->log('Guest AC 1 ' . $fromDate . ' - ' . $toDate);
        }

        foreach ($quoteCollection as $quote)
        {
            $quoteId = $quote->getId();
            $storeId = $quote->getStoreId();
            $items = $quote->getAllItems();
            $email = $quote->getCustomerEmail();
            $itemIds = $this->getQuoteItemIds($items);

            $abandonedModel = $this->abandonedFactory->create()->loadByQuoteId($quoteId, $storeId);
            if ($this->abandonedCartAlreadyExists($abandonedModel) && $this->shouldNotSendAbandonedCartAgain($abandonedModel, $quote)) {
                if ($this->shouldDeleteAbandonedCart($quote)) {
                    $this->deleteAbandonedCart($abandonedModel);
                }
                continue;
            }

            //create abandoned cart
            $this->createAbandonedCart($abandonedModel, $quote, $itemIds);
            $template = $this->getLostBasketGuestTemplate($abandonedNum, $storeId);
            //send campaign; check if valid to be sent
            if ($this->isLostBasketGuestEnabled(self::GUEST_LOST_BASKET_ONE, $storeId)) {
                $this->sendEmailCampaign($email, $quote, self::GUEST_LOST_BASKET_ONE, $template);
            }

            $this->totalGuests++;
            $result = $this->totalGuests;
        }

        return $result;
    }

    /**
     * @param $storeId
     * @param $websiteId
     * @param $number
     * @param $guest
     * @return int
     * @throws \Exception
     */
    public function processExistingAbandonedCart($storeId, $websiteId, $number, $guest = false)
    {
        $result = 0;
        $fromTime = new \DateTime('now', new \DateTimezone('UTC'));

        if ($guest) {
            $interval = $this->getSendAfterIntervalForGuest($storeId, $number);
            $template = $this->getLostBasketGuestTemplate($number, $storeId);
            $message = 'Guest';
        } else {
            $interval = $this->getSendAfterIntervalForCustomerLogged($storeId, $number);
            $template = $this->getLostBasketCustomerTemplate($number, $storeId);
            $message = 'Customer';
        }

        $fromTime->sub($interval);
        $toTime = clone $fromTime;
        $fromTime->sub(\DateInterval::createFromDateString('10 mins'));
        $fromDate   = $fromTime->format('Y-m-d H:i:s');
        $toDate     = $toTime->format('Y-m-d H:i:s');

        //get abandoned carts already sent
        $abandonedCollection = $this->getAbandonedCartsForStore(
            $number,
            $fromDate,
            $toDate,
            $storeId,
            $guest
        );

        //quote collection based on the updated date from abandoned cart table
        $quoteIds = $abandonedCollection->getColumnValues('quote_id');
        if (empty($quoteIds)) {
            return $result;
        }
        $quoteCollection = $this->getProcessedQuoteByIds($quoteIds, $storeId);
        //found abandoned carts
        if ($quoteCollection->getSize()) {
            $this->helper->log($message . ' Abandoned Cart #' . $number . ', from: ' . $fromDate . ' - ' . $toDate . ', storeId '. $storeId);
        }

        foreach ($quoteCollection as $quote)
        {
            $quoteId = $quote->getId();
            $storeId = $quote->getStoreId();
            $email = $quote->getCustomerEmail();

            $abandonedModel = $this->abandonedFactory->create()->loadByQuoteId($quoteId, $storeId);

            //number of items changed or not active anymore
            if ($this->isItemsChanged($quote, $abandonedModel)) {
                if ($this->shouldDeleteAbandonedCart($quote)) {
                    $this->deleteAbandonedCart($abandonedModel);
                }
                continue;
            }
            $abandonedModel->setAbandonedCartNumber($number)
                ->setQuoteUpdatedAt($quote->getUpdatedAt())
                ->save();

            $this->sendEmailCampaign($email, $quote, $number, $template);
            $result++;
        }

        return $result;
    }

    /**
     * @param $num
     * @param $storeId
     * @return bool
     */
    public function isLostBasketCustomerEnabled($num, $storeId)
    {
        return $this->scopeConfig->isSetFlag(
            constant('self::XML_PATH_LOSTBASKET_CUSTOMER_ENABLED_' . $num),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param $num
     * @param $storeId
     * @return bool
     */
    public function isLostBasketGuestEnabled($num, $storeId)
    {
        return $this->scopeConfig->isSetFlag(
            constant('self::XML_PATH_LOSTBASKET_GUEST_ENABLED_' . $num),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param $num
     * @param $storeId
     * @return mixed
     */
    public function getLostBasketSendAfterForCustomer($num, $storeId)
    {
        return $this->scopeConfig->getValue(
            constant('self::XML_PATH_LOSTBASKET_CUSTOMER_INTERVAL_' . $num),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param $num
     * @param $storeId
     * @return mixed
     */
    public function getLostBasketSendAfterForGuest($num, $storeId)
    {
        return $this->scopeConfig->getValue(
            constant('self::XML_PATH_LOSTBASKET_GUEST_INTERVAL_' . $num),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param $num
     * @param $storeId
     * @return mixed
     */
    public function getLostBasketCustomerTemplate($num, $storeId)
    {
        return $this->scopeConfig->getValue(
            constant('self::XML_PATH_LOSTBASKET_CUSTOMER_TEMPLATE_' . $num),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param $num
     * @param $storeId
     * @return mixed
     */
    public function getLostBasketGuestTemplate($num, $storeId)
    {
        return $this->scopeConfig->getValue(
            constant('self::XML_PATH_LOSTBASKET_GUEST_TEMPLATE_' . $num),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param $from
     * @param $to
     * @param $guest
     * @param $storeId
     * @return QuoteCollection
     */
    public function getStoreQuotes($from = null, $to = null, $guest = false, $storeId = 0)
    {
        $updated = [
            'from' => $from,
            'to' => $to,
            'datetime' => true,
        ];

        $salesCollection = $this->quoteCollectionFactory->create()
            ->addFieldToFilter('items_count', ['gt' => 0])
            ->addFieldToFilter('customer_email', ['neq' => ''])
            ->addFieldToFilter('store_id', $storeId)
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('main_table.updated_at', $updated);

        //guests
        if ($guest) {
            $salesCollection->addFieldToFilter('main_table.customer_id', ['null' => true]);
        } else {
            //customers
            $salesCollection->addFieldToFilter('main_table.customer_id', ['notnull' => true]);
        }

        return $salesCollection;
    }

    /**
     * @param $storeId
     * @param $num
     * @return \DateInterval|false
     */
    public function getSendAfterIntervalForCustomerLogged($storeId, $num)
    {
        $timeInterval = $this->getLostBasketSendAfterForCustomer($num, $storeId);
        if ($num == 1) {
            $interval = \DateInterval::createFromDateString($timeInterval . ' minutes');
        } else {
            $interval = \DateInterval::createFromDateString($timeInterval . ' hours');
        }
        return $interval;
    }

    /**
     * @param $storeId
     * @param $num
     * @return \DateInterval|false
     */
    public function getSendAfterIntervalForGuest($storeId, $num)
    {
        $timeInterval = $this->getLostBasketSendAfterForGuest($num, $storeId);
        if ($num == 1) {
            $interval = \DateInterval::createFromDateString($timeInterval . ' minutes');
        } else {
            $interval = \DateInterval::createFromDateString($timeInterval . ' hours');
        }

        return $interval;
    }

    /**
     * @param $allItemsIds
     * @return array
     */
    public function getQuoteItemIds($allItemsIds)
    {
        $itemIds = [];
        foreach ($allItemsIds as $item) {
            $itemIds[] = $item->getProductId();
        }

        return $itemIds;
    }

    /**
     * @param $quote
     * @param $abandonedModel
     * @return bool
     */
    public function isItemsChanged($quote, $abandonedModel)
    {
        if ($quote->getItemsCount() != $abandonedModel->getItemsCount()) {
            return true;
        } else {
            //number of items matches
            $quoteItemIds = $this->getQuoteItemIds($quote->getAllItems());
            $abandonedItemIds = explode(',', $abandonedModel->getItemsIds());

            //quote items not same
            if (!$this->isItemsIdsSame($quoteItemIds, $abandonedItemIds)) {
                return true;
            }
            return false;
        }
    }

    /**
     * @param $abandonedModel
     * @param $quote
     * @param $itemIds
     * @return void
     */
    public function createAbandonedCart($abandonedModel, $quote, $itemIds)
    {
        $abandonedModel->setQuoteId($quote->getId())
            ->setStoreId($quote->getStoreId())
            ->setCustomerId($quote->getCustomerId())
            ->setEmail($quote->getCustomerEmail())
            ->setQuoteUpdatedAt($quote->getUpdatedAt())
            ->setAbandonedCartNumber(1)
            ->setItemsCount($quote->getItemsCount())
            ->setItemsIds(implode(',', $itemIds))
            ->save();
    }

    /**
     * @param $email
     * @param $quote
     * @param $number
     * @param $template
     * @return void
     * @throws AlreadyExistsException
     */
    public function sendEmailCampaign($email, $quote, $number, $template)
    {
        $storeId = $quote->getStoreId();
        $customerId = $quote->getCustomerId();
        $customerName = $this->helper->getCustomerName($customerId);
        $message = ($customerId)? 'Customer Abandoned Cart #' . $number : 'Guest Abandoned Cart #' . $number;
        $campaign = $this->campaignFactory->create()
            ->setEmail($email)
            ->setCustomerId($customerId)
            ->setQuoteId($quote->getId())
            ->setMessage($message)
            ->setStoreId($storeId);

        $this->campaignResource->save($campaign);
        $this->sendAbandonedEmail($storeId, $email, $quote, $customerName, $template);
    }

    /**
     * @param $storeId
     * @param $email
     * @param $quote
     * @param $customerName
     * @param $template
     * @return void
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function sendAbandonedEmail($storeId, $email, $quote, $customerName, $template)
    {
        $quoteUrl = $this->frameworkTemplate->getUrl('abandoned_cart/cart/index', ['quote'=>$quote->getId()]);
        $templateOptions = ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $storeId];
        $templateVars = [
            'store' => $this->storeManager->getStore($storeId),
            'customer_name' => $customerName,
            'items' => $quote->getAllItems(),
            'quoteurl' => $quoteUrl
        ];

        $from = ['name' => $this->scopeConfig->getValue("trans_email/ident_sales/name", ScopeInterface::SCOPE_STORE, $storeId), 'email' => $this->scopeConfig->getValue("trans_email/ident_sales/email", ScopeInterface::SCOPE_STORE, $storeId)];
        $this->inlineTranslation->suspend();
        $transport = $this->transportBuilder->setTemplateIdentifier($template)
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFrom($from)
            ->addTo($email)
            ->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }

    /**
     * @param $abandonedModel
     * @return mixed
     */
    public function abandonedCartAlreadyExists($abandonedModel)
    {
        return $abandonedModel->getId();
    }

    /**
     * @param $abandonedModel
     * @param $quote
     * @return bool
     */
    public function shouldNotSendAbandonedCartAgain($abandonedModel, $quote)
    {
        return !$quote->getIsActive() || $quote->getItemsCount() == 0 || !$this->isItemsChanged($quote, $abandonedModel);
    }

    /**
     * @param $quote
     * @return bool
     */
    public function shouldDeleteAbandonedCart($quote)
    {
        return !$quote->getIsActive() || $quote->getItemsCount() == 0;
    }

    /**
     * @param $abandonedModel
     * @return void
     * @throws \Exception
     */
    public function deleteAbandonedCart($abandonedModel)
    {
        $this->abandonedResource->delete($abandonedModel);
    }

    /**
     * @param $number
     * @param $from
     * @param $to
     * @param $storeId
     * @param $guest
     * @return Abandoned\Collection
     */
    public function getAbandonedCartsForStore($number, $from, $to, $storeId, $guest = false)
    {
        $updated = [
            'from' => $from,
            'to'   => $to,
            'date' => true
        ];

        $abandonedCollection = $this->abandonedCollectionFactory->create()
            ->addFieldToFilter('abandoned_cart_number', --$number)
            ->addFieldToFilter('store_id', $storeId)
            ->addFieldToFilter('quote_updated_at', $updated);

        if ($guest) {
            $abandonedCollection->addFieldToFilter('customer_id', ['null' => true]);
        } else {
            $abandonedCollection->addFieldToFilter('customer_id', ['notnull' => true]);
        }
        return $abandonedCollection;
    }

    /**
     * @param $quoteIds
     * @param $storeId
     * @return QuoteCollection
     */
    public function getProcessedQuoteByIds($quoteIds, $storeId)
    {
        return $this->quoteCollectionFactory->create()
            ->addFieldToFilter('entity_id', ['in' => $quoteIds]);
    }

    /**
     * @param $quoteItemIds
     * @param $abandonedItemIds
     * @return bool
     */
    public function isItemsIdsSame($quoteItemIds, $abandonedItemIds)
    {
        return $quoteItemIds == $abandonedItemIds;
    }
}
