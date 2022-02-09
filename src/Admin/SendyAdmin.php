<?php
namespace Syntro\SilverStripeSendy\Admin;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldImportButton;
use SilverStripe\Forms\GridField\GridFieldPrintButton;
use Syntro\SilverStripeSendy\Model\SendyCampaign;

/**
 * Allows Users to manage campaigns
 *
 * @author Matthias Leutenegger
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

    /**
     * getEditForm - get the displayed edit form
     *
     * @param  int $id = null     the id of the record
     * @param  FieldList $fields = null the fields
     * @return Form
     */
    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);
        if ($this->modelClass === SendyCampaign::class) {
            $listField = $form->Fields()->dataFieldByName($this->sanitiseClassName($this->modelClass));
            $config = $listField->getConfig();
            $config->removeComponentsByType([
                GridFieldExportButton::class,
                GridFieldImportButton::class,
                GridFieldPrintButton::class
            ]);
        }
        return $form;
    }

}
