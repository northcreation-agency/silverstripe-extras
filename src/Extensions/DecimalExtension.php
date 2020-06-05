<?php

namespace NorthCreationAgency\SSExtras;

use SilverStripe\Core\Extension;
use SilverStripe\i18n\i18n;

/**
 * Created by PhpStorm.
 *
 * User: herbert
 * Date: 2018-01-21
 * Time: 17:10
 *
 * @property \SilverShop\ORM\FieldType\CanBeFreeCurrency|\SilverShop\ORM\FieldType\ShopCurrency|\SilverStripe\ORM\FieldType\DBCurrency|\SilverStripe\ORM\FieldType\DBDecimal|\SilverStripe\ORM\FieldType\DBPercentage|\Extensions\BTDDecimalExtension $owner
 */
class DecimalExtension extends Extension
{
    public function AutoNice($useThousands = false)
    {
        setlocale(LC_ALL, i18n::get_locale());
        $decimals = (float)($this->owner->Nice() != (int) $this->owner->Nice())? 2: 0;
        $locale = localeconv();
        $val = number_format(
            abs($this->owner->value),
            $decimals,
            $locale['decimal_point'],
            $useThousands?$locale['thousands_sep']:""
        );
        return $val;
    }
  
    public function Normalized()
    {
        $val = number_format(
            $this->owner->value,
            2,
            ".",
            ""
        );
        return $val;
    }
  
    public function LocaleFormat()
    {
        setlocale(LC_ALL, i18n::get_locale());
        $decValue = (float) floatval($this->owner->value."");
        $intVal = (float) number_format($this->owner->value, 0, ".", "");
        $decimals = $intVal !=  $decValue? 2: 0;

        $locale = localeconv();
        $val = number_format(
            abs($this->owner->value),
            $decimals,
            $locale['decimal_point'],
            $locale['thousands_sep']
        );

        return $val;
    }
}
