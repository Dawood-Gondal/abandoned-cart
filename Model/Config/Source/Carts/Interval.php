<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AbandonedCart
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\AbandonedCart\Model\Config\Source\Carts;

/**
 * Class Interval
 */
class Interval implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var int[]
     */
    public $times = [
            1,
            2,
            3,
            4,
            5,
            6,
            12,
            24,
            36,
            48,
            60,
            72,
            84,
            96,
            108,
            120,
            240,
        ];

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];
        $i = 0;
        foreach ($this->times as $one)
        {
            if ($i == 0) {
                $row = [
                    'value' => $one,
                    'label' => $one . __(' Hour'),
                ];
            } else {
                $row = [
                    'value' => $one,
                    'label' => $one . __(' Hours'),
                ];
            }
            $result[] = $row;
            ++$i;
        }
        return $result;
    }
}
