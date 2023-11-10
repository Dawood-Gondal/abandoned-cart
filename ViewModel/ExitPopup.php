<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AbandonedCart
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\AbandonedCart\ViewModel;

use Magento\Checkout\Helper\Cart;
use Magento\Checkout\Model\Session;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class ExitPopup
 */
class ExitPopup implements ArgumentInterface
{
    /**
     * @var Cart
     */
    protected $cartHelper;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @param Cart $cartHelper
     * @param CustomerSession $customerSession
     * @param Session $checkoutSession
     */
    public function __construct(
        Cart            $cartHelper,
        CustomerSession $customerSession,
        Session         $checkoutSession
    ) {
        $this->cartHelper = $cartHelper;
        $this->customerSession = $customerSession;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return bool
     */
    public function isCartEmpty()
    {
        if ($this->cartHelper->getItemsCount() === 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isGuestUser()
    {
        if ($this->customerSession->isLoggedIn()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return bool
     */
    public function isQuoteEmailEmpty()
    {
        try {
            $quote = $this->checkoutSession->getQuote();
            if ($quote->getCustomerEmail()) {
                return false;
            }
        } catch (\Exception $exception) {
        }
        return true;
    }
}
