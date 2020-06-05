<?php

namespace NorthCreationAgency\SSExtras;

use function array_values;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\DataObject;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TreeMultiselectField;
use SilverStripe\SiteConfig\SiteConfig;
use App\Model\FooterMenuItem;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

/**
 * Class \App\Model\FooterMenu
 *
 * @property string $Title
 * @property int $SiteConfigID
 * @method \SilverStripe\SiteConfig\SiteConfig SiteConfig()
 * @method \SilverStripe\ORM\ManyManyList|\SilverStripe\CMS\Model\SiteTree[] MenuPages()
 */
class SiteMenu extends DataObject
{
    private static $singluar_name = 'Footer Menu';
    
    private static $plural_name = 'Footer Menus';

    private static $table_name = 'SSExtras_SiteMenu';
    
    private static $db = [
        'Title' => 'Varchar(255)',
    ];

    private static $has_one = [
        'SiteConfig' => SiteConfig::class
    ];

    private static $many_many = [
        'MenuPages' => SiteTree::class
    ];
    /**
     * @var array
     */
    private static $many_many_extraFields = [
        'MenuPages' => [
            'FooterSort' => 'Int'
        ]
    ];


    /**
     * @var array
     */
    private static $summary_fields = [
        "Title" =>"Title",
        "ListMenuPages"=>"Pages in list"
    ];

    public function getListMenuPages()
    {
        return implode(", ", array_values($this->MenuPages()->map()->toArray()));
    }
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $titleField = TextField::create('Title', 'Menu title');
        $fields->removeByName("MenuPages");
        $pagesField = TreeMultiselectField::create(
            'MenuPages',
            'Page links',
            SiteTree::class
        );

        $pagesField = GridField::create(
            'MenuPages',
            'Page links',
            $this->MenuPages(),
            GridFieldConfig_RelationEditor::create()
                ->removeComponentsByType(GridFieldAddNewButton::class)
                ->addComponent(new GridFieldSortableRows("FooterSort"))
        );

        $fields->addFieldsToTab('Root.Main', [
            $titleField,
            $pagesField
        ]);
        
        $fields->addFieldsToTab('Root.SiteConfig', [
            DropdownField::create("SiteConfigID", "SiteConfig", SiteConfig::get())
        ]);

        return $fields;
    }
}
