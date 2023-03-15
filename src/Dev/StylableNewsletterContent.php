<?php

namespace Syntro\SilverStripeSendy\Dev;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Dev\TestOnly;
use Syntro\SilverStripeSendy\Model\SendyContentExtension;

/**
 * For testing output of SendyContentExtension->updateRenderTemplates()
 *
 * @author Patrick Côté
 */
class StylableNewsletterContent extends BaseElement implements TestOnly
{
    /**
     * Table name
     * @config
     * @var string
     */
    private static $table_name = 'TestObjects';

    /**
     * Singular name
     * @config
     * @var string
     */
    private static $singular_name = 'Test object';

    /**
     * Plural name
     * @config
     * @var string
     */
    private static $plural_name = 'Test objects';

    /**
     * Description
     * @config
     * @var string
     */
    private static $description = 'TestOnly object for testing';

    /**
     * Make sure we get the extension we want to test
     * @config
     * @var array
     */
    private static $extensions = [
        SendyContentExtension::class,
    ];

    /**
     * Make sure we get correct holder template
     * @config
     * @var string
     */
    private static $controller_template = 'NewsletterElementHolder';
}
