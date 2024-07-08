<?php
/**
 * Status.php
 * php version 8.1
 
 * @category  Payment
 * @package   Phonepe
 * @author    Tony Benny <tony.codecl@gmail.com>
 * @copyright 2024 Codecl
 * @license   https://codecl.com Codecl
 * @link      Link to project website
 */
declare(strict_types=1);

namespace Codecl\Phonepe\Controller\Payment;

use Magento\Framework\Controller\Result\Json;
use Magento\Sales\Model\Order;
/**
 * Class summary
 * A longer class description
 
 * @category  Payment
 * @package   Phonepe
 * @author    Tony Benny <tony.codecl@gmail.com>
 * @copyright 2024 Codecl
 * @license   https://codecl.com Codecl
 * @link      Link to project website
 */
class Status extends \Magento\Framework\App\Action\Action
{


    /**
     * Payment Method
     * 
     * @var \Codecl\Phonepe\Model\PaymentMethod
     */
    protected $paymentMethod;

    /**
     * Checkout Session Variable
     * 
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;


     /**
      * Json factory Variable
      *
      * @var \Magento\Framework\Controller\Result\JsonFactory
      */
    protected $resultJsonFactory;


     /**
      * Logging instance
      *
      * @var \Codecl\Phonepe\Logger\Logger
      */
    protected $logger;
   
    /**
     * Adding params to constructor
     * 
     * @param Context       $context           Context 
     * @param PaymentMethod $paymentMethod     Payment Method
     * @param Session       $checkoutSession   Checkout Session
     * @param JsonFactory   $resultJsonFactory Result Json Factory
     * @param Logger        $logger            Logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context, 
        \Codecl\Phonepe\Model\PaymentMethod $paymentMethod,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Codecl\Phonepe\Logger\Logger $logger
    ) {
        $this->paymentMethod = $paymentMethod;
        $this->checkoutSession = $checkoutSession;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->logger = $logger;
         parent::__construct($context);
    }
    /**
     * Check the Payment status and redirect to checkout success.
     *
     * @return void
     */
    public function execute():Json
    {
        $response = $this->paymentMethod->getPaymentStatus($this->getOrder());
        if (($response['success']) && ($response['code'] == 'PAYMENT_SUCCESS')) {
            $this->getResponse()->setRedirect(
                $this->_getUrl('payment/phonepe/success')
            );
        } else {
            $this->getResponse()->setRedirect(
                $this->_getUrl('checkout/onepage/failure')
            ); 
        }
    }

    /**
     * Get order object.
     *
     * @return \Magento\Sales\Model\Order
     */
    protected function getOrder():Order
    {
        return $this->checkoutSession->getLastRealOrder();
    }
}

