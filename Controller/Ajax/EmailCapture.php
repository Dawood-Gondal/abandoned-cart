<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AbandonedCart
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\AbandonedCart\Controller\Ajax;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Quote\Model\ResourceModel\Quote;

/**
 * Class Email Capture
 */
class EmailCapture implements \Magento\Framework\App\ActionInterface
{
    /**
     * @var Quote
     */
    protected $quoteResource;

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param Quote $quoteResource
     * @param Session $session
     * @param JsonFactory $resultJsonFactory
     * @param RequestInterface $request
     */
    public function __construct(
        Quote $quoteResource,
        Session $session,
        JsonFactory $resultJsonFactory,
        RequestInterface $request
    ) {
        $this->quoteResource = $quoteResource;
        $this->checkoutSession = $session;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->request = $request;
    }

    /**
     * @return Json
     */
    public function execute()
    {
        $email = $this->request->getParam('email');
        $resultJson = $this->resultJsonFactory->create();
        try {
            if ($email && $quote = $this->checkoutSession->getQuote()) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return $resultJson->setData(['error' => 'Email is not Valid!']);
                }

                if ($quote->hasItems()) {

                    $quote->setCustomerEmail($email);
                    $this->quoteResource->save($quote);
                    return $resultJson->setData(['success' => 'Subscribed Successfully!']);

                } else {
                    return $resultJson->setData(['error' => 'No quote items!']);
                }
            } else {
                return $resultJson->setData(['error' => 'No quote available!']);
            }
        } catch (\Exception $e) {
            return $resultJson->setData(['error' => $e->getMessage()]);
        }
    }
}
