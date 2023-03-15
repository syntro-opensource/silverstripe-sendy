<?php

namespace Syntro\SilverStripeSendy\Model;

use DNADesign\Elemental\Models\BaseElement;
use DNADesign\Elemental\TopPage\DataExtension;
use Model\Elements\SendyContent;
use SilverStripe\Core\ClassInfo;

class SendyContentExtension extends DataExtension
{
    private static $controller_template = 'NewsletterElementHolder';

    public function updateRenderTemplates(&$templates, $suffix)
    {
        $owner = $this->getOwner();
        if($owner->getPage()->Style) {
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
