<?php
/**
 * GatewayURL.php
 * php version 8.1
 
 * @category  Payment
 * @package   Phonepe
 * @author    Tony Benny <tony.codecl@gmail.com>
 * @copyright 2024 Codecl
 * @license   https://codecl.com Codecl
 * @link      Link to project website
 */

namespace Codecl\Phonepe\Model\Source;
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

use Magento\Framework\Option\ArrayInterface;
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
class GatewayURL implements ArrayInterface
{

     /**
      * Array of Option and values
      *
      * @return array
      */
    public function toOptionArray()
    {
        return [
            ['value' => 'https://api.phonepe.com/apis/hermes', 
            'label' => __('Production')],
            ['value' => 'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay', 
            'label' =>__('Staging')],
            ['value' => 'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay', 
            'label' =>__('Uat')],
              ];
    }
}
