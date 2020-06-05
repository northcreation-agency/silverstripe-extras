<?php
namespace NorthCreationAgency\SSExtras;
use SilverStripe\Security\Security;
use SilverStripe\Control\Director;
use SilverStripe\i18n\i18n;
use SilverStripe\ORM\DataExtension;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * Created by: herbertcuba
 * Date: 2019-10-03
 * Time: 21:45
 */

class PageExtension extends DataExtension
{
    function SiteConfig()
    {
        return SiteConfig::current_site_config();
    }

    /**
     * @return bool
     */
    public function CurrentMember()
    {
        return Security::getCurrentUser();
    }

    /**
     * @return string
     */
    public function CurrentLocale()
    {
        return i18n::get_locale();
    }

    /**
     * @return bool
     */
    public function isLive()
    {
        return Director::isLive();
    }

    /**
     * @return bool
     */
    public function isTest()
    {
        return Director::isTest();
    }

    /**
     * @return bool
     */
    public function isDev()
    {
        return Director::isDev();
    }

    public function FirstPageByType($className){
        return $className::get()->First();
    }

    public function FirstPageControllerByType($className){
        $controllerName = $className."Controller";
        return $controllerName::create($this->FirstPageByType($className));
    }
}