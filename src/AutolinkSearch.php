<?php

<<<<<<< HEAD
namespace saurabhd\silverstripe\autolink;
=======
namespace saurabhd\silverstripeplugin;
>>>>>>> 8762de3948ae3b86fda1686044b72296475b36b1
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\Queries\SQLUpdate;
use SilverStripe\CMS\Model\SiteTree;

class AutolinkSearch extends DataExtension
{
    public static function AutolinkDiff($searchableClasses = [SiteTree::class, File::class])
    {
        $ListPageType = DB::query('SELECT distinct(PageType) as PageType FROM SilverStripe_Autolink_Autolink');
        foreach ($ListPageType as $key => $value) 
        {
            $ListAutoLinks = DB::query("SELECT * FROM SilverStripe_Autolink_Autolink where PageType ='".$value['PageType']."'");
            $KeyArray = [];
            $UrlArray = [];
            foreach ($ListAutoLinks as $ListAutoLinkskey => $ListAutoLinksvalue) 
            {
                array_push($KeyArray, '/\b'.$ListAutoLinksvalue['Key'].'\b/');
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
