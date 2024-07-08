<?php
/**
 * PhonepeConfigProvider.php
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

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
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
class PhonepeConfigProvider
{
    private const CONFIG_PATH = 'payment/phonepe/';
    private const CONFIG_PATH_ENABLE = 'enable';
    private const CONFIG_PATH_MERCHANT_ID = 'merchant_id';
    private const CONFIG_PATH_SALT_KEY = 'salt_key';
    private const CONFIG_PATH_SALT_INDEX = 'salt_index';
    private const CONFIG_PATH_PHONEPE_ENV = 'phonepe_env';
    private const CONFIG_PATH_PHONEPE_PUBLISHEVENTS= 'phonepe_publishevents';
    private const CONFIG_PATH_PHONEPE_GATEWAY_URL= 'gateway_url';
    private const CONFIG_PATH_PHONEPE_PAYMODE= 'pay_mode';
    private const CONFIG_PATH_NEWORDERSTATUS = 'new_order_status';
    private const CONFIG_PATH_STORENAME = 'general/store_information/name';
    private const CONFIG_PATH_CRON = 'cron';

    /**
     * Scope Config  
     *
     * @var ScopeConfigInterface $ScopeConfigInterface ScopeConfigInterface
     */
    private $_scopeConfig;
    /**
     * Scope StoreManager 
     *
     * @var \Magento\Store\Model\StoreManagerInterface $_storeManager StoreManager 
     */
    private $_storeManager;

    
    /**
     * Setting Constructor variables
     * 
     * @param StoreManagerInterface $storeManager Store Manager
     * @param ScopeConfigInterface  $scopeConfig  ScopeConfig
     */
    public function __construct( 
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
        
    
    }

    /**
     * This function is used to check if module is enabled or not
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (boolean)$this->getConfigValue(self::CONFIG_PATH_ENABLE);
    }

    /**
     * This function is used to get config value
     *
     * @param string $value Configvalue
     * 
     * @return string
     */
    protected function getConfigValue(string $value): ?string
    {
        $path = self::CONFIG_PATH . $value;
        return $this->_scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }
    /**
     * This function is used to get Merchant ID
     *
     * @return string
     */
    public function getMerchantId(): string
    {
        return  $this->getConfigValue(self::CONFIG_PATH_MERCHANT_ID);
    }
    /**
     * This function is used to get production or sandbox url
     *
     * @return string
     */
    public function getPaymentGatewayUrl(): string
    {
        return $this->getConfigValue(self::CONFIG_PATH_PHONEPE_GATEWAY_URL);
    }
    /**
     * This function is used to get Salt Key  value
     *
     * @return string
     */
    public function getSaltkey(): string
    {
        return  $this->getConfigValue(self::CONFIG_PATH_SALT_KEY);
    }
    /**
     * This function is used to get Salt Index
     *
     * @return string
     */
    public function getSaltIndex(): string
    {
        return  $this->getConfigValue(self::CONFIG_PATH_SALT_INDEX);
    }
    /**
     * This function is used to get Payment Mode value
     *
     * @return string
     */
    public function getPaymentMode(): string
    {
        return  $this->getConfigValue(self::CONFIG_PATH_PHONEPE_PAYMODE);
    }
    /**
     * This function is used to get order status
     *
     * @return string
     */
    public function getOrderStatus(): string
    {
        return  $this->getConfigValue(self::CONFIG_PATH_NEWORDERSTATUS);
    }
     
     /**
      * This function is used to get Store name
      *
      * @return string
      */
    public function getStoreName(): ?string
    {
        return $this->_scopeConfig->getValue(
            self::CONFIG_PATH_STORENAME, 
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * This function is used to generate Checksum
     * 
     * @param $bas64_encod_pload Base64 Encode Payload
     * @param $key               Saltkey
     * @param $sindex            Saltindex
     * @param $endpoint          Pay End Point
     * 
     * @return string
     */
    public function genChecksum($bas64_encod_pload, $key, $sindex, $endpoint): string
    {
        $string_to_be_hashed = $bas64_encod_pload . $endpoint .  $key;
        $sha256_hash = hash('sha256', $string_to_be_hashed);
        return $sha256_hash . "###" . $sindex;
    }

    /**
     * This function is used to check if module is enabled or not
     *
     * @return bool
     */
    public function isCronEnabled(): bool
    {
        return (boolean)$this->getConfigValue(self::CONFIG_PATH_CRON);
    }

    
}
