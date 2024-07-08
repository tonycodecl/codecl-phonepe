<?php
/**
 * PayMode.php
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
class PayMode implements ArrayInterface
{
    
    /**
     * Array of Option and values
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'post', 'label' => __('POST')],
            ['value' => 'REDIRECT', 'label' =>__('REDIRECT')]
        ];
    }
}
