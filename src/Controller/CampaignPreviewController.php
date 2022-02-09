<?php
namespace Syntro\SilverStripeSendy\Controller;

use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Control\HTTPRequest;
use Syntro\SilverStripeSendy\Model\SendyCampaign;

/**
 * Allows a user to preview a newsletter.
 *
 * @author Matthias Leutenegger
 */
class CampaignPreviewController extends Controller
{
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

    /**
     * preview - renders a preview
     *
     * @param  HTTPRequest $request the request
     * @return mixed
     */
    public function preview($request)
    {
        $id = $this->getRequest()->params()['ID'];
        $campaign = SendyCampaign::get()->byID($id);

        if ($campaign && $campaign->canView()) {
            return $campaign->getHTMLNewsletter();
        }
        return Security::permissionFailure($this, _t(
            __CLASS__ . '.PLEASELOGIN',
            'You need to be logged in to preview Sendy campaigns.'
        ));
    }
}
