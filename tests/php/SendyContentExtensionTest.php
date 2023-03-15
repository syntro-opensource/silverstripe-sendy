<?php
namespace Syntro\SilverStripeSendy\Tests;

use DNADesign\Elemental\Models\ElementalArea;
use Model\SendyCampaignExtension;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Dev\SapphireTest;
use Syntro\SilverStripeSendy\Dev\StylableNewsletterContent;
use Syntro\SilverStripeSendy\Model\SendyCampaign;

class SendyContentExtensionTest extends SapphireTest
{
    protected static $fixture_file = './fixture.yml';

    protected static $extra_dataobjects = [
        StylableNewsletterContent::class,
    ];

    public function testUpdateRenderTemplates()
    {
        $sendyCampaignWithStyle = $this->objFromFixture(
            SendyCampaign::class,
            'SendyCampaignWithStyle'
        );

        $sendyElementalArea = $this->objFromFixture(
            ElementalArea::class,
            'SendyElementalArea'
        );

        $contentWithStyle1 = $this->objFromFixture(
            StylableNewsletterContent::class,
            'ContentWithStyle1'
        );

        $contentWithStyle2 = $this->objFromFixture(
            StylableNewsletterContent::class,
            'ContentWithStyle2'
        );

        $contentWithoutStyle = $this->objFromFixture(
            StylableNewsletterContent::class,
            'ContentWithoutStyle'
        );

        $contentWithoutCampaignStyle = $this->objFromFixture(
            StylableNewsletterContent::class,
            'ContentWithoutCampaignStyle'
        );

        $resultsForContentWithoutStyle = [
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent',
        ];

        $resultsForContentWithStyle1 = [
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_1col_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_1col',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_1col_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_1col',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_1col_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_1col',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_1col_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_1col',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent',
        ];

        $resultsForContentWithStyle2 = [
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_2col_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_2col',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_2col_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_2col',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_2col_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_2col',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_2col_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_2col',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_fancy',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent',
        ];

        $resultsForContentWithoutCampaignStlye = [
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_green',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_green',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea_green',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_green',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent_ElementalArea',
            'Syntro\SilverStripeSendy\Dev\StylableNewsletterContent',
        ];

        $this->assertEquals(
            $resultsForContentWithoutStyle,
            $contentWithoutStyle->getRenderTemplates()
        );

        $this->assertEquals(
            $resultsForContentWithStyle1,
            $contentWithStyle1->getRenderTemplates()
        );

        $this->assertEquals(
            $resultsForContentWithStyle2,
            $contentWithStyle2->getRenderTemplates()
        );

        $this->assertEquals(
            $resultsForContentWithoutCampaignStlye,
            $contentWithoutCampaignStyle->getRenderTemplates()
        );
    }
}
