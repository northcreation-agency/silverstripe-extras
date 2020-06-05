<?php

namespace NorthCreationAgency\SSExtras;
// Extenstion
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\HeaderField;
use SilverStripe\ORM\DataExtension;

// Custom fields
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

// Logo
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\TextField;


class CustomSiteConfig extends DataExtension
{
    private static $db = [
        'ShareHeading' => 'Varchar(255)',
        'FacebookURL' => 'Varchar(255)',
        'TwitterURL' => 'Varchar(255)',
        'InstagramURL' => 'Varchar(255)',
        'PinterestURL' => 'Varchar(255)',
        'LinkedinURL' => 'Varchar(255)',
        'FooterHTML1' => 'HTMLText',
        'FooterHTML2' => 'HTMLText',
        'ContactEmail' => 'Varchar(100)',
        'ContactPhone' => 'Varchar(100)',
        'ContactAddress' => 'Varchar(255)',
        'ScriptsHeadCode' => "HTMLText",
        'ScriptsBodyFirstCode' => "HTMLText",
        'ScriptsBodyLastCode' => "HTMLText"
    ];

    private static $has_one = [
        'LogoImage' => Image::class
    ];
    private static $has_many = [
        'FooterMenu' => SiteMenu::class
    ];
    private static $owns = [
        'LogoImage'
    ];

    public function updateCMSFields(FieldList $fields)
    {
        // Footer
        $MenusField = GridField::create(
            'SiteMenu',
            'Site menus',
            SiteMenu::get(),
            GridFieldConfig_RelationEditor::create()
        );
        $facebookLinkField = TextField::create('FacebookURL', 'Facebook link');
        $twitterLinkField = TextField::create('TwitterURL', 'Twitter link');
        $instagramLinkField = TextField::create('InstagramURL', 'Instagram link');
        $LinkedinLinkField = TextField::create('LinkedinURL', 'LinkedIn link');
        $PinterestLinkField = TextField::create('PinterestURL', 'Pinterest link');


        $fields->addFieldsToTab(
            'Root.Main',
            [
                UploadField::create('LogoImage', 'Logo')->setAllowedMaxFileNumber(1)->setAllowedFileCategories('image'),
                TextAreaField::create('ScriptsHeadCode', 'Custom Head HTML')->setRightTitle("scripts/HTML/meta tags etc"),
                TextAreaField::create('ScriptsBodyFirstCode', 'Custom Body First HTML')->setRightTitle("scripts/HTML/meta tags etc"),
                TextAreaField::create('ScriptsBodyLastCode', 'Custom Body Last HTML')->setRightTitle("scripts/HTML/meta tags etc"),
                //LinkField::create('CookieDisclaimerLinkID', _t('SiteConfig.COOKIEDISCLAIMER_LINK', 'Cookie disclaimer')),
            ]
        );
        $fields->addFieldsToTab("Root.Footer", [
            HeaderField::create("FooterDetails","Footer details",2),
            EmailField::create("ContactEmail"),
            TextField::create("ContactPhone"),
            TextareaField::create("ContactAddress"),
            HtmlEditorField::create("FooterHTML1"),
            HtmlEditorField::create("FooterHTML2"),
            $facebookLinkField,
            $twitterLinkField,
            $instagramLinkField,
            $LinkedinLinkField,
            $PinterestLinkField,

        ]);
        $fields->addFieldsToTab("Root.SiteMenus", [
            $MenusField,
        ]);


    }
}
