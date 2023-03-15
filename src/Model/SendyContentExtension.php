<?php

namespace Syntro\SilverStripeSendy\Model;

use DNADesign\Elemental\Models\BaseElement;
use DNADesign\Elemental\TopPage\DataExtension;
use Model\Elements\SendyContent;
use SilverStripe\Core\ClassInfo;

/**
 * Provides newsletter styling support for Elemental blocks that extend it
 *
 * @author Patrick Côté
 */

class SendyContentExtension extends DataExtension
{
    private static $controller_template = 'NewsletterElementHolder';

    /**
     * Hooks into BaseElement->getRenderTemplates()
     * Enables styles by  appending style of associated SendyCampaign
     * and changing order so that new styles take precedence
     *
     * @param array $templates
     * @param string $suffix
     * @return void
     */
    public function updateRenderTemplates(&$templates, $suffix)
    {
        $owner = $this->getOwner();
        if ($owner->getPage()->Style) {
            foreach ($templates as $class => $styles) {
                $tmp = [];
                foreach ($styles as $style) {
                    $tmp[] = $style . '_' . $owner->getPage()->Style;
                    $tmp[] = $style;
                }
                $templates[$class] = $tmp;
            }
        }
    }
}
