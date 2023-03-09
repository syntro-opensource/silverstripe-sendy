# Silverstripe Sendy - Templating

Templating for this module is the same as if you were creating templates for
the [silverstripe-elemental](https://github.com/silverstripe/silverstripe-elemental) module.

We do not provide default blocks, but it should be sufficiently easy to create
new Blocks following the elemental documentation.

## Layout Template
Update the Layout template by adding the following file:

```html
<!-- mysite/templates/Syntro/SilverStripeSendy/Model/SendyCampaign.ss -->
<!DOCTYPE html>
<html>
    <head>
        <!-- ... -->
    </head>
    <body>
        <!-- ... -->
        $ElementalArea
        <!-- ... -->
    </body>
</html>
```

This builds the encapsulating Layout for every Newsletter.

> Note: Use the `$Subject` in the title tag

### Using multiple styles
Silverstripe Sendy supports multiple styles using the configuration API. The approriate style for a given newsletter can be set in its CMS interface.
To configure styles, proceed as follows.
1. Define your templates as key-value pairs in your app.yml. The value represents the front-facing name:
```yaml
# /app/_config/app.yml
Syntro\SilverStripeSendy\Model\SendyCampaign:
  styles:
    red: Red Style
    blue: Blue Style
 ```
 2. Place the templates in the destination given in the previous section with the naming `SendyCampaign_$key`, e.g.: `mysite/templates/Syntro/SilverStripeSendy/Model/SendyCampaign_red.ss`

> Note: Silverstripe Sendy will fall back to `SendyCampaign.ss` if the configuration is faulty, a template file is missing, or a template is not set in the CMS. It is thus **strongly** recommended to provide a `SendyCampaign.ss`.

## Creating Blocks
Creating blocks is done the same way as regular blocks used by the
[silverstripe-elemental](https://github.com/silverstripe/silverstripe-elemental) module,
with some gotchas explained here.

### Using a Custom Holder Template
Your Newsletter blocks should use a custom holder template which is different from
the one used by your page ones. To do this, add this to your newsletter blocks:

```php
/**
 * @var string
 */
private static $controller_template = 'NewsletterElementHolder';
```

Then, create a file `templates/DNADesign/Elemental/Layout/NewsletterElementHolder.ss`
which uses the correct holder markup.

### Limit Blocks to Newsletter Use
It is important to understand the difference between a block in your newsletter
compared to a block in your regular page layout. A block utilized in a newsletter
campaign must not use external css, unlike a regular block. It is therefore
necessary to limit the elements used for newsletter to only appear on the
campaign. For this, follow the docs of the elemental module:
https://github.com/silverstripe/silverstripe-elemental#limit-allowed-elements

You should always disable the blocks you create for newsletters in regular pages
and only enable newsletter blocks for sendy campaigns.
```yml
Syntro\SilverStripeSendy\Model\SendyCampaign:
  allowed_elements:
    - Model\Elements\Newsletter\Content
# Use your actual class here
ElementalPage:
  disallowed_elements:
    - Model\Elements\Newsletter\Content
```

### `HTMLEditorConfig`
As Newsletters do not have the same capabilities as pages in the CMS do, we have
to apply a custom config to all HTMLEditor fields. Do this by adding:

```php
$contentField = $fields->fieldByName('Root.Main.HTML');
$contentField->setEditorConfig('newsletter');
```
