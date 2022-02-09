# Silverstripe Sendy - Templating

Templating for this module is the same as if you were creating templates for
the [silverstripe-elemental](https://github.com/silverstripe/silverstripe-elemental) module.

We do not provide default blocks, but it should be sufficiently easy to create
new Blocks following the elemental documentation.

## `HTMLEditorConfig`
As Newsletters do not have the same capabilities as pages in the CMS do, we have
to apply a custom config to all HTMLEditor fields. Do this by adding:

```php
$contentField = $fields->fieldByName('Root.Main.HTML');
$contentField->setEditorConfig('newsletter');
```

## Limit elements
It is important to understand the difference between a block in your newsletter
compared to a block in your regular page layout. A block utilized in a newsletter
campaign must not use external css, unlike a regular block. It is therefore
necessary to limit the elements used for newsletter to only appear on the
campaign. For this, follow the docs of the elemental module:
https://github.com/silverstripe/silverstripe-elemental#limit-allowed-elements
