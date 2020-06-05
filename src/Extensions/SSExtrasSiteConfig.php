<?php
// Extenstion
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
		'GooglePlusURL' => 'Varchar(255)',
		'PinterestURL' => 'Varchar(255)',
		'LinkedinURL' => 'Varchar(255)',
		'FooterHTML1' => 'HTMLText',
        'FooterHTML2' => 'HTMLText',
		'ContactEmail' => 'Varchar(100)',
        'ContactPhone' => 'Varchar(100)',
        'ContactAddress' => 'Varchar(255)',
		'UseAkismet' => 'Boolean',
        'ScriptsHeadCode'=>"HTMLText",
        'ScriptsBodyFirstCode' =>"HTMLText",
        'ScriptsBodyLastCode' =>"HTMLText"
	];

	private static $has_one = [
		'LogoImage' => Image::class
	];
    private static $has_many = [
        'FooterMenu' => FooterMenu::class
    ];
    private static $owns = [
        'LogoImage'
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab(
			'Root.Main',
			[
				EmailField::create("ContactEmail"),
                TextField::create("ContactPhone"),
                TextareaField::create("ContactAddress"),
				TextAreaField::create('ScriptsHeadCode', 'Head Scripts'),
                TextAreaField::create('ScriptsBodyFirstCode', 'Body First Script'),
                TextAreaField::create('ScriptsBodyLastCode', 'Body Last Script'),
				UploadField::create('LogoImage', 'Logo')->setAllowedMaxFileNumber(1)->setAllowedFileCategories('image'),
				//LinkField::create('CookieDisclaimerLinkID', _t('SiteConfig.COOKIEDISCLAIMER_LINK', 'Cookie disclaimer')),
				CheckboxField::create("UseAkismet", "Use Akismet for spam check")
			]
		);
        $fields->addFieldsToTab("Root.Footer",[
            HtmlEditorField::create("FooterHTML1"),
            HtmlEditorField::create("FooterHTML2"),
        ]);
    }  
}
