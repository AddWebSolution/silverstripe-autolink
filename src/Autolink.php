<?php

<<<<<<< HEAD
namespace saurabhd\silverstripe\autolink;
=======
namespace saurabhd\silverstripeplugin;
>>>>>>> 8762de3948ae3b86fda1686044b72296475b36b1

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\CurrencyField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\ORM\ArrayLib;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\TabSet;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\Queries\SQLUpdate;
use SilverStripe\ORM\Queries\SQLSelect;
use SilverStripe\Blog\Model\BlogPost;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\CMS\Controllers\CMSMain;

class Autolink extends DataObject
{

    private static $db = [
        'Key' => 'Varchar',
        'Url' => 'Varchar',
        'PageType' => 'Varchar'
    ];

    private static $searchable_fields = [
      'Key',
      'Url'
    ];

    private static $summary_fields = [
      'Key',
      'Url'
    ];


    public function getCMSfields()
    {


        $pageTypeList = array();
        $resultColumn = DB::query("SELECT DISTINCT(ClassName) as ClassName FROM SiteTree");
        foreach($resultColumn as $resultColumnKey => $resultColumnVal)
        {
            $pagetype = explode("\\",$resultColumnVal['ClassName']);
            $pageTypeList[$resultColumnVal['ClassName']] = $pagetype[sizeof($pagetype)-1];            
        }       

        $fields = FieldList::create(TabSet::create('Root'));
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Key'),
            TextField::create('Url'),
            DropdownField::create( 'PageType', 'PageType', $pageTypeList )
        ]);

        return $fields;
    }

    function getCMSValidator() {
        return new Autolink_Validator();
    }

    function onAfterWrite() {
        $ListPageType = DB::query('SELECT distinct(PageType) as PageType FROM SilverStripe_Autolink_Autolink');
        foreach ($ListPageType as $key => $value) 
        {
            $ListAutoLinks = DB::query("SELECT * FROM SilverStripe_Autolink_Autolink where PageType ='".$value['PageType']."'");
            $KeyArray = [];
            $UrlArray = [];
            foreach ($ListAutoLinks as $ListAutoLinkskey => $ListAutoLinksvalue) 
            {
                array_push($KeyArray,'/\b'.$ListAutoLinksvalue['Key'].'\b/');
                array_push($UrlArray, "<a href='".$ListAutoLinksvalue['Url']."'>".$ListAutoLinksvalue['Key']."</a>");
            }
            $getContent = DB::query("SELECT Content,ID FROM SiteTree where ClassName = '".$value['PageType']."'");
            foreach($getContent as $getContentKey => $getContentValue)
            {
                $replacedContent = preg_replace($KeyArray,$UrlArray,$getContentValue['Content']);
                //SiteTree
                $updateSiteTree = SQLUpdate::create('"SiteTree"')->addWhere(array('ClassName' => $value['PageType'], 'ID'=>$getContentValue['ID']));
                $updateSiteTree->assign('"Content"', $replacedContent);
                $updateSiteTree->execute();

                
                //SiteTree_Live
                $updateSiteTree_Live = SQLUpdate::create('"SiteTree_Live"')->addWhere(array('ClassName' => $value['PageType'], 'ID'=>$getContentValue['ID']));
                $updateSiteTree_Live->assign('"Content"', $replacedContent);
                $updateSiteTree_Live->execute();

                //SiteTree_version
                $SiteTree_version = SQLUpdate::create('"SiteTree_Versions"')->addWhere(array('ClassName' => $value['PageType'], 'ID'=>$getContentValue['ID']));
                $SiteTree_version->assign('"Content"', $replacedContent);
                $SiteTree_version->execute();

            }
        }
    }
}

?>
