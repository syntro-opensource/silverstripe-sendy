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
    /**
     * Holder template for newsletter elements
     *
     * @config
     * @var string
     */
    private static $controller_template = 'NewsletterElementHolder';

    /**
     * Hooks into BaseElement->getRenderTemplates()
     * Enables styles by  appending style of associated SendyCampaign
     * and changing order so that new styles take precedence
     *
     * @param array  $templates Templates from the hooked function
     * @param string  $suffix    Suffix from the hooked function
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
