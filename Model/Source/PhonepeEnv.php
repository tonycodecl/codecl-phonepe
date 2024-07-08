<?php
/**
 * PhonepeEnv.php
 * php version 8.1
 
 * @category  Payment
 * @package   Phonepe
 * @author    Tony Benny <tony.codecl@gmail.com>
 * @copyright 2024 Codecl
 * @license   https://codecl.com Codecl
 * @link      Link to project website
 */
declare(strict_types=1);

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
class PhonepeEnv implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Array of Option and values
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 'PRODUCTION', 'label' => __('PRODUCTION')],
        ['value' => 'UAT', 'label' => __('UAT')],['value' => 'STAGING', 
        'label' => __('STAGING')]];
    }
    /**
     * Array of Labels
     *
     * @return array
     */
    public function toArray()
    {
        return ['PRODUCTION' => __('PRODUCTION'),
        'UAT' => __('UAT'),'STAGING' => __('STAGING')];
    }
}
