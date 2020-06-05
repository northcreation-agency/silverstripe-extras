<?php

namespace NorthCreationAgency\SSExtras;

use function class_exists;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBField;
use TractorCow\Fluent\Extension\FluentExtension;
use TractorCow\Fluent\Model\Locale;

/**
 * Created by PhpStorm.
 * User: herbertcuba
 * Date: 2019-12-11
 * Time: 22:19
 */
if (class_exists("TractorCow\Fluent\Extension\FluentExtension")) {
    class FluentExtensionInjector extends FluentExtension
    {


        public function ActiveInLocale($locale)
        {
            $class = get_class($this->owner);
            $schema = DataObject::getSchema();
            $localisedTable = $this->owner->getLocalisedTable($schema->tableName($class));
            $item = DB::query("SELECT * FROM " . $localisedTable . " WHERE Locale='" . $locale . "' AND RecordID=" . $this->owner->ID)->first();
            if ($item) {
                return true;
            }
        }

        /**
         * @param FieldList $fields
         */
        public function updateCMSFields(FieldList $fields)
        {
            parent::updateCMSFields($fields);
            $currentLocale = Locale::getCurrentLocale();
            $badgeClasses = ['badge', 'fluent-badge'];
            if ($currentLocale->getIsDefault() && $this->owner->ActiveInLocale($currentLocale->Locale)) {
                // Current locale should always show a "default" or green state badge
                $badgeClasses[] = 'fluent-badge--default';
                $tooltip = _t(__CLASS__ . '.BadgeDefault', 'Default locale');
            } elseif ($this->owner->ActiveInLocale($currentLocale->Locale)) {
                // If the object has been localised in the current locale, show a "localised" state
                $badgeClasses[] = 'fluent-badge--localised';
                $tooltip = _t(__CLASS__ . '.BadgeInvisible', 'Localised in {locale}', [
                    'locale' => $currentLocale->getTitle(),
                ]);
            } else {
                // Otherwise the state is that it hasn't yet been localised in the current locale, so is "invisible"
                $badgeClasses[] = 'fluent-badge--invisible';
                $tooltip = _t(__CLASS__ . '.BadgeLocalised', '{type} is not visible in this locale', [
                    'type' => $this->owner->i18n_singular_name(),
                ]);
            }

            $fields->unshift(LiteralField::create(
                'HTMLFragment',
                sprintf(
                    '<span class="%s" title="%s" style="margin-left:0;">%s</span>',
                    implode(' ', $badgeClasses),
                    $tooltip,
                    $this->owner->ID ? $this->owner->getSourceLocale()->getBadgeLabel() : $currentLocale->Locale
                )));
        }


    }
}