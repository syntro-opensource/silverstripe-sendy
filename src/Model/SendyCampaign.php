<?php
namespace Syntro\SilverStripeSendy\Model;

use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Member;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ValidationResult;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\SSViewer;
use SilverStripe\Security\PermissionProvider;
use SilverStripe\Forms\HTMLEditor\HTMLEditorConfig;
use LeKoala\CmsActions\CustomAction;
use LeKoala\CmsActions\CustomLink;
use SilverStripe\Forms\DropdownField;
use Syntro\SilverStripeSendy\Connector;

/**
 * represents a campaign that can be transferred
 *
 * @author Matthias Leutenegger
 */
class SendyCampaign extends DataObject
{

    const PERMISSION_VIEW = 'SendyCampaign_PERMISSION_VIEW';
    const PERMISSION_EDIT = 'SendyCampaign_PERMISSION_EDIT';

    /**
     * Defines the database table name
     * @config
     *  @var string
     */
    private static $table_name = 'SendyCampaign';

    /**
     * Database fields
     * @config
     * @var array
     */
    private static $db = [
        'Title' => 'Varchar',
        'FromName' => 'Varchar',
        'FromEmail' => 'Varchar',
        'ReplyToEmail' => 'Varchar',
        'Subject' => 'Varchar',
        'IsTransferred' => 'Varchar',
        'Style' => 'Varchar'
    ];

    /**
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @config
     * @var array
     */
    private static $summary_fields = [
        'Title' => 'Title',
        'Subject' => 'Subject',
        'summarizedLabel' => 'summarizedLabel'
    ];

    /**
     * Defines a default list of filters for the search context
     * @config
     * @var array
     */
    private static $searchable_fields = [
        'Title',
        'Subject'
    ];

    /**
     * Default sort ordering
     * @config
     * @var array
     */
    private static $default_sort = [
        'IsTransferred' => 'ASC',
        'Created' => 'DESC'
    ];

    /**
     * Holds styles (format: <template name> => <human name>)
     *
     * @config
     * @var array
     */
    private static $styles = [];

    /**
     * If no entry in Style field, return default style
     * If there is entry in Style field, return that with default fallback
     *
     * @return array
     */
    public function getStyles()
    {
        $styles = [];
        if(!($this->Style))
        {
            array_push($styles, "Syntro/SilverStripeSendy/Model/SendyCampaign");
        } else {
            array_push(
                $styles,
                "Syntro/SilverStripeSendy/Model/SendyCampaign_".$this->Style,
                "Syntro/SilverStripeSendy/Model/SendyCampaign"
            );
        }

        return $styles;
    }

    /**
     * providePermissions - provides CMS permissions
     *
     * @return array
     */
    public function providePermissions()
    {
        return [
            self::PERMISSION_VIEW => _t(__CLASS__ . '.PERM_VIEW', 'View and preview campaigns'),
            self::PERMISSION_EDIT => _t(__CLASS__ . '.PERM_EDIT', 'Edit sendy campaigns')
        ];
    }

    /**
     * DataObject view permissions
     * @param Member $member the member to be checked
     * @return boolean
     */
    public function canView($member = null)
    {
        return Permission::check(self::PERMISSION_VIEW, 'any', $member);
    }

    /**
     * DataObject edit permissions
     * @param Member $member the member to be checked
     * @return boolean
     */
    public function canEdit($member = null)
    {
        return !$this->IsTransferred && Permission::check(self::PERMISSION_EDIT, 'any', $member);
    }

    /**
     * DataObject delete permissions
     * @param Member $member the member to be checked
     * @return boolean
     */
    public function canDelete($member = null)
    {
        return !$this->IsTransferred && Permission::check(self::PERMISSION_EDIT, 'any', $member);
    }

    /**
     * DataObject archive permissions
     * @param Member $member the member to be checked
     * @return boolean
     */
    public function canArchive($member = null)
    {
        return !$this->IsTransferred && Permission::check(self::PERMISSION_EDIT, 'any', $member);
    }

    /**
     * fieldLabels - get the field label titles
     *
     * @param  bool $includerelations = true wether to include relations
     * @return array
     */
    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['Title'] = _t(__CLASS__ . '.FIELDS_Title', 'Campaign Title');
        $labels['FromName'] = _t(__CLASS__ . '.FIELDS_FromName', 'From Name');
        $labels['FromEmail'] = _t(__CLASS__ . '.FIELDS_FromEmail', 'From Email');
        $labels['ReplyToEmail'] = _t(__CLASS__ . '.FIELDS_ReplyToEmail', 'Reply To Email');
        $labels['Subject'] = _t(__CLASS__ . '.FIELDS_Subject', 'Subject');
        $labels['summarizedLabel'] = _t(__CLASS__ . '.SUMMARY_Transferred', 'Uploaded');
        return $labels;
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

        $fields->addFieldsToTab(
            'Root.Main',
            [
                TextField::create('Title', $this->fieldLabel('Title')),
                TextField::create('FromName', $this->fieldLabel('FromName')),
                EmailField::create('FromEmail', $this->fieldLabel('FromEmail')),
                EmailField::create('ReplyToEmail', $this->fieldLabel('ReplyToEmail')),
                TextField::create('Subject', $this->fieldLabel('Subject')),
            ],
            'ElementalArea'
        );

        $styles = $this->config()->get('styles');

        if($styles && count($styles ?? []) > 0)
        {
            $fields->addFieldToTab(
                'Root.Main',
                $styleDropdownField = DropdownField::create('Style', 'Style', $styles),
                'ElementalArea'
            );
            $styleDropdownField->setHasEmptyDefault(true);
            $styleDropdownField->setEmptyString(_t("DNADesign\\Elemental\\Models\\BaseElement".'.CUSTOM_STYLES', 'Select a style..'));
        } else {
            $fields->removeByName('Style');
        }

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
     * validate - check that all necessary fields are present.
     *
     * @return ValidationResult
     */
    public function validate()
    {
        $result = parent::validate();
        if (!$this->Title) {
            $result->addFieldError(
                'Title',
                _t(__CLASS__ . '.FIELDERROR_Title', 'A title is required'),
                ValidationResult::TYPE_ERROR
            );
        }
        if (!$this->FromName) {
            $result->addFieldError(
                'FromName',
                _t(__CLASS__ . '.FIELDERROR_FromName', 'A sender name is required'),
                ValidationResult::TYPE_ERROR
            );
        }
        if (!$this->FromEmail) {
            $result->addFieldError(
                'FromEmail',
                _t(__CLASS__ . '.FIELDERROR_FromEmail', 'A sender email is required'),
                ValidationResult::TYPE_ERROR
            );
        }
        if (!$this->Subject) {
            $result->addFieldError(
                'Subject',
                _t(__CLASS__ . '.FIELDERROR_Subject', 'A subject is required'),
                ValidationResult::TYPE_ERROR
            );
        }
        return $result;
    }

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSActions()
    {
        $actions = parent::getCMSActions();
        $previewLink = CustomLink::create('preview', _t(__CLASS__ . '.PREVIEW', 'Preview'), $this->Link());
        $previewLink->setButtonIcon('eye');
        $previewLink->setButtonType('outline-info');
        $previewLink->setNewWindow(true);

        $transferAction = CustomAction::create("doTransfer", _t(__CLASS__ . '.UPLOAD', 'Upload to Sendy'));
        $transferAction->setButtonIcon('upload');
        $transferAction->setButtonType('outline-dark');
        $transferAction->setShouldRefresh(true);
        $transferAction->setConfirmation(_t(__CLASS__ . '.UPLOADCONFIRM', 'You will not be able to edit this campaign anymore. Are you sure?'));

        if ($this->isInDB() && $this->canView()) {
            $actions->push($previewLink);
        }
        if ($this->isInDB() && !$this->IsTransferred && $this->canEdit()) {
            $actions->push($transferAction);
        }

        return $actions;
    }

    /**
     * doTransfer - this transfers the campaign to the configured sendy instance
     *
     * @return string|bool
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
        return _t(__CLASS__ . '.UPLOADSUCCESS', 'Transferred campaign {title}', ['title' => $this->Title]);
    }

    /**
     * Link - returns the Linkto this specific post
     *
     * @return string
     */
    public function Link()
    {
        return '/sendycampaign_preview/' . $this->ID;
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

    /**
     * summarizedLabel - returns a label to summarize if this has been transferred
     *
     * @return string
     */
    public function summarizedLabel()
    {
        return $this->IsTransferred ? '🟢' : '⚪️';
    }

    /**
     * getHTMLNewsletter - returns the HTML render of the Newsletter
     *
     * @return DBHTMLText
     */
    public function getHTMLNewsletter()
    {
        $template = SSViewer::create($this->getStyles());
        $template->includeRequirements(false);
        return $this->renderWith($template);
    }

    /**
     * getPlainNewsletter - returns a plaintext representation
     *
     * @return string
     */
    public function getPlainNewsletter()
    {
        return $this->ElementalArea->forTemplate()->Plain();
    }
}
