<?php
/**
 * Start.php
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

class Start extends \Magento\Framework\App\Action\Action
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
     * Adding params to constructor
     *
     * @param $context           Magento context 
     * @param $paymentMethod     Payment Method
     * @param $checkoutSession   Checkout Session
     * @param $resultJsonFactory Result Json 
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context, 
        \Codecl\Phonepe\Model\PaymentMethod $paymentMethod,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->paymentMethod = $paymentMethod;
        $this->checkoutSession = $checkoutSession;
        $this->resultJsonFactory = $resultJsonFactory;
         parent::__construct($context);
    }

    /**
     * Setting Response for Pay Page
     
     * @return string
     */
    public function execute():Json
    {
        
        $response = $this->paymentMethod->getPostHTML($this->getOrder());
        $result = $this->resultJsonFactory->create();
        return $result->setData($response);
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

