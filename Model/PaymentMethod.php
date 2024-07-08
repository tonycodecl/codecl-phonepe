<?php
/**
 * PaymentMethod.php
 * php version 8.1
 
 * @category  Payment
 * @package   Phonepe
 * @author    Tony Benny <tony.codecl@gmail.com>
 * @copyright 2024 Codecl
 * @license   https://codecl.com Codecl
 * @link      Link to project website
 */

declare(strict_types=1);

namespace Codecl\Phonepe\Model;

use Magento\Sales\Model\Order\Payment;
use Magento\Sales\Model\Order\Payment\Transaction;
use Magento\Framework\Encryption\EncryptorInterface;
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

class PaymentMethod extends \Magento\Payment\Model\Method\AbstractMethod
{

    private const METHOD_CODE = 'phonepe';
    private const PAYMENT_CHECK_STATUS = 'payment/check-status';
    private const PAYMENT_REQUEST_URL = 'api/payments';
    private const PAYMENT_CALLBACK_URL = 'phonepe/payment/status';
    private const PAYMENT_REDIRECT_URL = 'phonepe/payment/success';
    private const PAYMENT_REDIRECT_MODE = 'REDIRECT';
    private const PAYMENT_ORIGIN_PREFIX = 'magento';
    private const PAYMENT_DESCRIPTION = 'Payment Test Description';
    private const PHONEPE_CHECKOUT_START = 'phonepe/payment/start';
    private const CHECKOUT_ONEPAGE_SUCCESS = 'checkout/onepage/success';
    private const CHECKOUT_ONEPAGE_FAILURE = 'checkout/onepage/failure';
    private const PAISA = 100;
    private const PHONEPE_PENDING = 'phonepe_pending';
    private const PG_V1_PAY_ENDPOINT                 = "/pg/v1/pay";
    private const PG_V1_STATUS_ENDPOINT                 = "/pg/v1/status/";
    private  const INGEST_EVENT_ENDPOINT                 = "/plugin/ingest-event";

    /**
     * Code for Payment method
     * 
     * @var string
     */
    protected $_code = self::METHOD_CODE;

    /**
     * Initialize needed
     * 
     * @var boolean
     */
    protected $isInitializeNeeded = true;

    /**
     * Exception
     * 
     * @var \Magento\Framework\Exception\LocalizedExceptionFactory
     */
    protected $exception;

    /**
     * Transaction
     * 
     * @var \Magento\Sales\Api\TransactionRepositoryInterface
     */
    protected $transactionRepository;

    /**
     * Transaction Builder Interface
     * 
     * @var Transaction\BuilderInterface
     */
    protected $transactionBuilder;

    /**
     * URL Builder
     * 
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * Order Factory
     * 
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * Phonepe Config Provider
     * 
     * @var \Codecl\Phonepe\Model\PhonepeConfigProvider
     */
    protected $phonepeConfigProvider;

 
    /**
     * Country Helper
     * 
     * @var \Magento\Directory\Model\Country
     */
    protected $countryHelper;
    
    /**
     * Store Manager
     * 
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Config Encryptor
     *
     * @var Magento\Framework\Encryption\EncryptorInterface
     */
    protected $encryptor;

    /**
     * Curl
     * 
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    protected $curl;
    
    /**
     * Phone Logger
     *
     * @var \Codecl\Phonepe\Logger\Logger
     */
    protected $phonelogger;
 
    /**
     * Session 
     * 
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    
    /**
     * Json
     * 
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $json;

    /**
     * Contructor Parameters
     * 
     * @param StoreManagerInterface          $storeManager           StoreManager
     * @param Context                        $context                Context
     * @param Registry                       $registry               Registry
     * @param ExtensionAttributesFactory     $extensionFactory       Extension
     * @param AttributeValueFactory          $customAttributeFactory AttributeFactory
     * @param Data                           $paymentData            Payment Data
     * @param ScopeConfigInterface           $scopeConfig            ScopeConfig
     * @param Logger                         $logger                 Logger
     * @param UrlInterface                   $urlBuilder             Url Builder
     * @param LocalizedExceptionFactory      $exception              Exception
     * @param TransactionRepositoryInterface $transactionRepository  Transaction
     * @param BuilderInterface               $transactionBuilder     Transaction
     * @param OrderFactory                   $orderFactory           Order factory
     * @param PhonepeConfigProvider          $phonepeConfigProvider  PhonepeConfig
     * @param EncryptorInterface             $encryptor              Encryptor
     * @param Curl                           $curl                   Curl
     * @param PhoneLogger                    $phonelogger            Phone Logger
     * @param Session                        $checkoutSession        Checkout Session
     * @param Json                           $json                   Json
     * @param AbstractResource               $resource               Resource
     * @param AbstractDb                     $resourceCollection     Collection
     * @param array                          $data                   Data
     */
    public function __construct(  
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Exception\LocalizedExceptionFactory $exception,
        \Magento\Sales\Api\TransactionRepositoryInterface $transactionRepository,
        \Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface 
        $transactionBuilder,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Codecl\Phonepe\Model\PhonepeConfigProvider $phonepeConfigProvider,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Magento\Framework\HTTP\Client\Curl $curl, 
        \Codecl\Phonepe\Logger\Logger $phonelogger,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) { 
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->exception = $exception;
        $this->transactionRepository = $transactionRepository;
        $this->transactionBuilder = $transactionBuilder;
        $this->orderFactory = $orderFactory; 
        $this->phonepeConfigProvider = $phonepeConfigProvider;
        $this->encryptor = $encryptor;
        $this->curl = $curl;
        $this->phonelogger = $phonelogger;
        $this->checkoutSession = $checkoutSession;
        $this->json = $json;
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Instantiate state and set it to state object.
     *
     * @param $paymentAction paymentAction
     * @param \Magento\Framework\DataObject $stateObject   StateObject
     * 
     * @return void
     */
    public function initialize($paymentAction, $stateObject):void
    {
        $orderStatus = $this->config->getOrderStatus();
        $payment = $this->getInfoInstance();
        $order = $payment->getOrder();
        $order->setCanSendNewEmailFlag(false);
        $stateObject->setState(\Magento\Sales\Model\Order::STATE_NEW);
        $stateObject->setStatus(self::PHONEPE_PENDING);
        $stateObject->setIsNotified(false);
    }

    /**
     * Return json response
     *
     * @param $order   Order
     * @param $storeId StoreId
     * 
     * @return String
     */
    public function getPostHTML($order, $storeId = null):string
    {
            $merchantId = "";
            $saltKey = "";
            $saltIndex = "";
            $paymentUrl = $this->phonepeConfigProvider->getPaymentGatewayUrl();
            $storeName = $this->phonepeConfigProvider->getStoreName();
            $description = self::PAYMENT_DESCRIPTION;
            $originCall = self::PAYMENT_ORIGIN_PREFIX;

           // $orderId = $order->getEntityId();
            $incrementId = $order->getIncrementId();
            $amount = $order->getGrandTotal();
            $amount = number_format((float)$amount, 2, '.', '');
            $total = $amount * self::PAISA; //Amount must be in paisa(amount*100)
            $currency = $order->getOrderCurrencyCode();
            $billingAddress = $order->getBillingAddress();
            $firstname = $billingAddress->getData('firstname');
            $lastname = $billingAddress->getData('lastname');
            $userFullName = $firstname.$lastname;
            $baseUrl = $this->storeManager->getStore()->getBaseUrl();
            $redirectUrl = $baseUrl.self::PAYMENT_REDIRECT_URL;
            $redirectMode = self::PAYMENT_REDIRECT_MODE;
            $callbackUrl = $baseUrl.self::PAYMENT_CALLBACK_URL;
            $mobile = $billingAddress->getData('telephone');
            $merchantId = $this->encryptor->decrypt($this->phonepeConfigProvider->getMerchantId());
            $paymentMode = $this->phonepeConfigProvider->getPaymentMode();
            $merchantTransactionId = $order->getEntityId();
            $paymentCheckStatus = $paymentUrl.self::PAYMENT_CHECK_STATUS."/".$incrementId;
            $saltKey = $this->encryptor->decrypt($this->phonepeConfigProvider->getSaltkey());
            $saltIndex = $this->encryptor->decrypt($this->phonepeConfigProvider->getSaltIndex());
            $postData = array (
                'merchantId' => $merchantId, 
                'merchantTransactionId' => $merchantTransactionId,
                'merchantUserId' => $userFullName,
                'amount' => $total,
                'redirectUrl' => $redirectUrl,
                'redirectMode' => $redirectMode,
                'callbackUrl' => $callbackUrl,
                'mobileNumber' => $mobile,
                'paymentInstrument' =>
                 array (
                  'type' => 'PAY_PAGE',
                ),
              );
              $encodeData = base64_encode(json_encode($postData));
              $payload  = json_encode(array("request" => $encodeData));
              $endpoint = self::PG_V1_PAY_ENDPOINT ;
              $finalXHeader = $this->phonepeConfigProvider->genChecksum($encodeData, $saltKey, $saltIndex, $endpoint);  
              $headers = ["Content-Type" => "application/json", "X-VERIFY" => $finalXHeader];
              $this->curl->setHeaders($headers);
              $this->curl->post($paymentUrl, $payload);
              $this->curl->setOption(CURLOPT_POST, 1);
              $this->curl->setOption(CURLOPT_RETURNTRANSFER, true);
              $this->curl->setOption(CURLOPT_SSL_VERIFYPEER, true);

            $response = $this->curl->getBody();
            $isJson = $this->isJsonResponse($response);
           
            if ($isJson == 1) {
                $resDecode = $this->json->unserialize($response);
                if (isset($resDecode->errors)) {
                    $comment = __('Error Comes From Payment gateway');
          
                    $this->cancelCurrentOrder($incrementId, $comment);
                    $this->restoreQuote();
                }
            } else {
                $comment = __('Something went wrong - please confirm credentail.');
  
                $this->cancelCurrentOrder($incrementId, $comment);
                $this->restoreQuote();
                return $response = '{"message": "Something went wrong" ,"errors":
                        {"order_id": ["Something went wrong."]}}';
            }
            return $response;
    }

    

     /**
      * Return json response
      *
      * @param $order   order
      * @param $storeId storeId
      *
      * @return String
      */
    public function getPaymentStatus($order, $storeId = null):string 
    {
          $stmerchantId = ""; 
          $paymentMode = $this->phonepeConfigProvider->getPaymentMode();
          $stmerchantId = $this->encryptor->decrypt($this->phonepeConfigProvider->getMerchantId());
          $stmerchantTransactionId = $order->getEntityId();
          $statusUrl = "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/".$stmerchantId."/".$stmerchantTransactionId;
          $hsaltKey = $this->encryptor->decrypt($this->phonepeConfigProvider->getSaltkey());
          $hsaltIndex = $this->encryptor->decrypt($this->phonepeConfigProvider->getSaltIndex());
          $status_to_be_hashed = "/pg/v1/status/$stmerchantId/$stmerchantTransactionId".$hsaltKey;
          $sha256_hash_status= hash('sha256', $status_to_be_hashed);
          $hashed_status_string = $sha256_hash_status."###".$hsaltIndex;
          $finalstatusXHeader = $hashed_status_string;  

            $stheaders = array(
                "Content-Type: application/json",
                "accept: application/json",
                "X-VERIFY: " . $finalstatusXHeader,
                "X-MERCHANT-ID:" . $stmerchantId
            );
            
            
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $statusUrl);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, '0');
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, '0');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $stheaders);
            $statusResponse = curl_exec($curl);
            curl_close($curl);
            $statusResponsePayment = json_decode($statusResponse, true);
            
         
            return $statusResponsePayment;
    }

    /**
     * Check valid Json Response
     *
     * @param string $resDecode DecodeResponse
     * 
     * @return bool
     */
    protected function isJsonResponse($resDecode):bool
    {
        return ((is_string($resDecode) &&
                (is_object(json_decode($resDecode)) ||
                is_array(json_decode($resDecode))))) ? true : false;
    }

    /**
     * Cancel last placed order with specified comment message
     *
     * @param int    $incrementId Increment Id
     * @param string $comment     Comment appended to order history
     * 
     * @return bool True if order cancelled, false otherwise
     */
    public function cancelCurrentOrder($incrementId, $comment):bool
    {
        $order = $this->orderFactory->create()->loadByIncrementId($incrementId);
        if ($order->getId() && $order->getState() != \Magento\Sales\Model\Order::STATE_CANCELED) {
          
            $order->registerCancellation($comment)->save();
            return true;
        }
        return false;
    }

    /**
     * Restores quote
     *
     * @return bool
     */
    public function restoreQuote():bool
    {
     
        return $this->checkoutSession->restoreQuote();
    }

    /**
     * Get Return URL
     *
     * @param int|null $storeId Storeid
     *
     * @return String
     */
    public function getOrderPlaceRedirectUrl($storeId = null):string
    {
        return $this->getUrl(self::PHONEPE_CHECKOUT_START, $storeId);
    }
    
    /**
     * Get return URL.
     *
     * @param int|null $storeId Storeid
     *
     * @return string
     */
    public function getSuccessUrl($storeId = null):string
    {
        return $this->getUrl(self::CHECKOUT_ONEPAGE_SUCCESS, $storeId);
    }
    
    /**
     * Get cancel URL.
     *
     * @param int|null $storeId storeid
     *
     * @return string
     */
    public function getCancelUrl($storeId = null):string
    {
        return $this->getUrl(self::CHECKOUT_ONEPAGE_FAILURE, $storeId);
    }

    /**
     * Build URL for store.
     *
     * @param string    $path    path
     * @param int       $storeId storeid
     * @param bool|null $secure  secure
     *
     * @return string
     */
    protected function getUrl($path, $storeId, $secure = null):string
    {
        $store = $this->storeManager->getStore($storeId);

        return $this->urlBuilder->getUrl(
            $path,
            ['_store' => $store, 
            '_secure' => $secure === null ? $store->isCurrentlySecure() : $secure]
        );
    }
}
