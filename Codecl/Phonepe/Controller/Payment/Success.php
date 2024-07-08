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
class Success extends \Magento\Framework\App\Action\Action
{
    /**
     * Store Manager Interface
     * 
     * @var \Magento\Store\Model\StoreManagerInterface
     */
      protected $storeManager;
    
    /**
     * Url Builder Interface
     * 
     * @var \Magento\Framework\UrlInterface 
     */
    protected $urlBuilder;

    /**
     * Checkout Session Variable
     * 
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

     /**
      * Logging Instance
      * 
      * @var \Codecl\Phonepe\Logger\Logger
      */
    protected $logger;

    /**
     * Adding params to constructor
     * 
     * @param StoreManagerInterface $storeManager    StoreManager 
     * @param Context               $context         Context 
     * @param UrlInterface          $urlBuilder      UrlBuilder 
     * @param Session               $checkoutSession Checkout Session
     * @param Logger                $logger          Logger
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Codecl\Phonepe\Logger\Logger $logger
    ) {
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->checkoutSession = $checkoutSession;
        $this->logger = $logger;
        parent::__construct($context); 
    }

    /**
     * Unset the quote and redirect to checkout success.
     *
     * @return void
     */
    public function execute():void
    {
        
        $this->getResponse()->setRedirect($this->getUrl('checkout/onepage/success'));
       
       
    }

    /**
     * Build URL for store.
     *
     * @param string    $path   Path
     * @param bool|null $secure Secure
     *
     * @return string
     */
    protected function getUrl($path, $secure = null):string
    {
        $store = $this->storeManager->getStore(null);

        return $this->urlBuilder->getUrl(
            $path,
            ['_store' => $store, 
            '_secure' => $secure === null ? $store->isCurrentlySecure() : $secure]
        );
    }
}
