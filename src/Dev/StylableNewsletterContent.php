<?php

namespace Syntro\SilverStripeSendy\Dev;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Dev\TestOnly;
use Syntro\SilverStripeSendy\Model\SendyContentExtension;

class StylableNewsletterContent extends BaseElement implements TestOnly
{
    private static $table_name = 'Test content';
    private static $singular_name = 'Sendy content';
    private static $plural_name = 'Sendy contents';
    private static $description = 'Simple content placeholder for Sendy';

    private static $extensions = [
        SendyContentExtension::class,
    ];

    private static $controller_template = 'NewsletterElementHolder';
}
