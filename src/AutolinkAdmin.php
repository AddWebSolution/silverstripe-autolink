<?php 

<<<<<<< HEAD
namespace saurabhd\silverstripe\autolink;
=======
namespace saurabhd\silverstripeplugin;
>>>>>>> 8762de3948ae3b86fda1686044b72296475b36b1

use SilverStripe\Admin\ModelAdmin;


class AutolinkAdmin extends ModelAdmin
{

    private static $menu_title = 'Autolink';
    private static $url_segment = 'autolinks';
    private static $managed_models = [
        Autolink::class,
    ];

    private static $menu_icon_class = 'font-icon-link';

    public function getSearchContext() 
    {
        $context = parent::getSearchContext();
        return $context;
    }

    public function getList() 
    {
        $list = parent::getList();  
        return $list;
    }
}

?>
