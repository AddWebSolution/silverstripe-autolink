<?php 

namespace saurabhd\silverstripeplugin;

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
