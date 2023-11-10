<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AbandonedCart
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\AbandonedCart\Controller\Cart;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Quote\Model\QuoteFactory;

/**
 * Class Index
 */
class Index implements \Magento\Framework\App\ActionInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var QuoteFactory
     */
    protected $quoteRepository;

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var ResultFactory
     */
    protected $resultRedirect;

    /**
     * @param QuoteFactory $quoteRepository
     * @param Session $checkoutSession
     * @param ResultFactory $result
     * @param RequestInterface $request
     */
    public function __construct(
        QuoteFactory $quoteRepository,
        Session $checkoutSession,
        ResultFactory $result,
        RequestInterface $request
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->checkoutSession = $checkoutSession;
        $this->resultRedirect = $result;
        $this->request = $request;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $params = $this->request->getParams();
        $quoteId = $params['quote'];
        $quote = $this->quoteRepository->create()->load($quoteId);
        $this->checkoutSession->replaceQuote($quote);
        $resultRedirect = $this->resultRedirect->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('checkout/cart');
        return $resultRedirect;
    }
}
