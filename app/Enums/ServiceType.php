<?php


namespace App\Enums;


use Spatie\Enum\Enum;

/**
 * @method static self AIRTIME()
 * @method static self DATA()
 * @method static self EPIN()
 * @method static self CABLE_TV()
 * @method static self ELECTRICITY()
 * @method static self BANK_TRANSFER()
 * @method static self BETTING()
 */


class ServiceType extends Enum
{


    protected static function values()
    {
        return [
            'AIRTIME' => 'airtime',
            'DATA' => 'data',
            'EPIN' => 'epin',
            'CABLE_TV' => 'cable-tv',
            'ELECTRICITY' => 'electricity',
            'BANK_TRANSFER' => 'bank-transfer',
            'BETTING' => 'betting',
        ];
    }
}
