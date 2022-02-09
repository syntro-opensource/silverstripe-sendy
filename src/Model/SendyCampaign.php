<?php
namespace Syntro\SilverStripeSendy\Model;


use SilverStripe\Forms\LiteralField;
use SilverStripe\Security\Permission;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\PermissionProvider;
use SilverStripe\Forms\HTMLEditor\HTMLEditorConfig;
use LeKoala\CmsActions\CustomAction;
use LeKoala\CmsActions\CustomLink;
use Syntro\SilverStripeSendy\Connector;

/**
 *
 */
class SendyCampaign extends DataObject
{

    const PERMISSION_VIEW = 'SendyCampaign_PERMISSION_VIEW';
    const PERMISSION_EDIT = 'SendyCampaign_PERMISSION_EDIT';

    /**
     * Add default values to database
     *  @var array
     */
    private static $defaults = [
        '' => ''
    ];

    /**
     * Defines the database table name
     *  @var string
     */
    private static $table_name = 'SendyCampaign';

    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'Title' => 'Varchar',
        'FromName' => 'Varchar',
        'FromEmail' => 'Varchar',
        'ReplyToEmail' => 'Varchar',
        'Subject' => 'Varchar',
        'IsTransferred' => 'Varchar',
    ];

    /**
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @var array
     */
    private static $summary_fields = [
        'Title' => 'Title',
        'Subject' => 'Subject',
        'summarizedLabel' => 'Is Transferred'
    ];

    /**
     * Defines a default list of filters for the search context
     * @var array
     */
    private static $searchable_fields = [
        'Title',
        'Subject'
    ];

    public function providePermissions($value='')
    {
        return [
            self::PERMISSION_VIEW => 'View and preview campaigns',
            self::PERMISSION_EDIT => 'Edit sendy campaigns'
        ];
    }

    /**
     * DataObject view permissions
     * @param Member $member
     * @return boolean
     */
    public function canView($member = null)
    {
        return Permission::check(self::PERMISSION_VIEW, 'any', $member);
    }

    /**
     * DataObject edit permissions
     * @param Member $member
     * @return boolean
     */
    public function canEdit($member = null)
    {
        return !$this->IsTransferred && Permission::check(self::PERMISSION_EDIT, 'any', $member);
    }

    /**
     * DataObject delete permissions
     * @param Member $member
     * @return boolean
     */
    public function canDelete($member = null)
    {
        return !$this->IsTransferred && Permission::check(self::PERMISSION_EDIT, 'any', $member);
    }

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        HtmlEditorConfig::set_active(HtmlEditorConfig::get('newsletter'));
        $fields->removeByName([
            'IsTransferred'
        ]);
        if ($this->IsTransferred) {
            $fields->removeByName([
                'ElementalArea',
                'ElementalAreaID',
                'Root.Main.ElementalArea',
            ]);
            $message = _t(
                __CLASS__ . '.CAMPAIGNTRANSFERREDINFO',
                'This Campaign has been transferred to your sendy instance. Please make further adjustments there.'
            );
            $fields->addFieldToTab(
                'Root.Main',
                LiteralField::create(
                    'infoTransferred',
                    <<<HTML
                        <div class="alert alert-info">
                            {$message}
                        </div>
                    HTML
                ),
                'Title'
            );
        }
        return $fields;
    }

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSActions()
    {
        $actions = parent::getCMSActions();
        $previewLink = CustomLink::create('preview', 'Preview', $this->Link());
        $previewLink->setButtonIcon('eye');
        $previewLink->setButtonType('outline-info');
        $previewLink->setNewWindow(true);
        $actions->push($previewLink);

        $transferAction = CustomAction::create("doTransfer", "Upload to Sendy");
        $transferAction->setButtonIcon('upload');
        $transferAction->setButtonType('outline-success');
        $transferAction->setShouldRefresh(true);
        $transferAction->setConfirmation('You will not be able to edit this campaign anymore. Are you sure?');
        $actions->push($transferAction);
        return $actions;
    }

    /**
     * doTransfer - this transfers the campaign to the configured sendy instance
     *
     * @return {type}  description
     */
    public function doTransfer()
    {
        if ($this->IsTransferred) {
            throw new \Exception("This campaign has already been transferred", 1);
        }
        $connector = new Connector();
        $response = $connector->createCampaign([
            'from_name' => $this->FromName,
            'from_email' => $this->FromEmail,
            'reply_to' => $this->ReplyToEmail ? $this->ReplyToEmail : $this->FromEmail,
            'title' => $this->Title,
            'subject' => $this->Subject,
            'plain_text' => $this->getPlainNewsletter(),
            'html_text' => $this->getHTMLNewsletter()->forTemplate(),
        ]);
        if (!$response['status']) {
            throw new \Exception($response['message'], 1);
        }
        $this->IsTransferred = true;
        $this->write();
        return "Transferred campaign {$this->Title}";
    }

    /**
     * Link - returns the Linkto this specific post
     *
     * @return string
     */
    public function Link()
    {
        return '/sendycampaign_preview/'.$this->ID;
    }

    /**
     * CMSEditLink
     *
     * @return string
     */
    public function CMSEditLink()
    {
        return 'admin/sendy/Syntro-SilverStripeSendy-Model-SendyCampaign/EditForm/field/Syntro-SilverStripeSendy-Model-SendyCampaign/item/' . $this->ID . '/edit';
    }

    public function summarizedLabel()
    {
        return $this->IsTransferred ? '🟢' : '⚪️';
    }

    public function getHTMLNewsletter()
    {
        return $this->renderWith(self::class);
    }

    public function getPlainNewsletter()
    {
        return $this->ElementalArea->forTemplate()->Plain();
    }
}
