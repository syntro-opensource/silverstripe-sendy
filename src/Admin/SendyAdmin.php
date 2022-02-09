<?php
namespace Syntro\SilverStripeSendy\Admin;

use SilverStripe\Admin\ModelAdmin;
use Syntro\SilverStripeSendy\Model\SendyCampaign;


/**
 * Description
 *
 * @package silverstripe
 * @subpackage mysite
 */
class SendyAdmin extends ModelAdmin
{
    /**
     * Managed data objects for CMS
     * @var array
     */
    private static $managed_models = [
        SendyCampaign::class
    ];

    /**
     * Menu icon for Left and Main CMS
     * @var string
     */
    private static $menu_icon_class = 'font-icon-p-mail';

    /**
     * URL Path for CMS
     * @var string
     */
    private static $url_segment = 'sendy';

    /**
     * Menu title for Left and Main CMS
     * @var string
     */
    private static $menu_title = 'Newsletter';


}
