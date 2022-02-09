<?php
namespace Syntro\SilverStripeSendy\Controller;

use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;
use SilverStripe\Control\Controller;
use Syntro\SilverStripeSendy\Model\SendyCampaign;



/**
 *
 */
class CampaignPreviewController extends Controller
{
    public function doInit()
    {
        parent::doInit();
    }

    /**
     * Defines methods that can be called directly
     * @var array
     */
    private static $allowed_actions = [
        'index' => SendyCampaign::PERMISSION_VIEW,
        'preview' => true
    ];

    /**
     * Defines URL patterns.
     * @var array
     */
    private static $url_handlers = [
        '$ID' => 'preview'
    ];

    public function preview($request)
    {
        $id = $this->getRequest()->params()['ID'];
        $campaign = SendyCampaign::get()->byID($id);

        if ($campaign && $campaign->canView()) {
            return $campaign->getHTMLNewsletter();
        }
        return Security::permissionFailure($this, 'You need to be logged in to preview Sendy campaigns.');
    }

}
