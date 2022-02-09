<?php

use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;
use SilverStripe\Core\Manifest\ModuleLoader;

// call_user_func(function () {
//     $module = ModuleLoader::inst()->getManifest()->getModule('silverstripe/cms');
//
//     // Enable insert-link to internal pages
//     HTMLEditorConfig::get('newsletter')
//         ->enablePlugins([
//             'sslinkinternal' => $module
//                 ->getResource('client/dist/js/TinyMCE_sslink-internal.js'),
//             'sslinkanchor' => $module
//                 ->getResource('client/dist/js/TinyMCE_sslink-anchor.js'),
//         ]);
// });

$module = ModuleLoader::inst()->getManifest()->getModule('silverstripe/admin');
/** @var TinyMCEConfig $editorConfig */
$editorConfig = TinyMCEConfig::get('newsletter');
$editorConfig
    ->setContentCSS([])
    ->enablePlugins([
        'contextmenu' => null,
        'image' => null,
        'anchor' => null,
        'sslink' => $module->getResource('client/dist/js/TinyMCE_sslink.js'),
        'sslinkexternal' => $module->getResource('client/dist/js/TinyMCE_sslink-external.js'),
        'sslinkemail' => $module->getResource('client/dist/js/TinyMCE_sslink-email.js'),
    ])
    ->setOptions([
        'friendly_name' => 'Default Newsletter',
        'priority' => '50',
        'skin' => 'silverstripe',
        'body_class' => 'typography',
        'contextmenu' => "sslink anchor ssmedia ssembed inserttable | cell row column deletetable",
        'use_native_selects' => false,
        'valid_elements' => "@[id|class|style|title],a[id|rel|rev|dir|tabindex|accesskey|type|name|href|target|title"
            . "|class],-strong/-b[class],-em/-i[class],-strike[class],-u[class],#p[id|dir|class|align|style],-ol[class],"
            . "-ul[class],-li[class],br,img[id|dir|longdesc|usemap|class|src|border|alt=|title|width|height|align|data*],"
            . "-sub[class],-sup[class],-blockquote[dir|class],-cite[dir|class|id|title],"
            . "-table[cellspacing|cellpadding|width|height|class|align|summary|dir|id|style],"
            . "-tr[id|dir|class|rowspan|width|height|align|valign|bgcolor|background|bordercolor|style],"
            . "tbody[id|class|style],thead[id|class|style],tfoot[id|class|style],"
            . "#td[id|dir|class|colspan|rowspan|width|height|align|valign|scope|style],"
            . "-th[id|dir|class|colspan|rowspan|width|height|align|valign|scope|style],caption[id|dir|class],"
            . "-div[id|dir|class|align|style],-span[class|align|style],-pre[class|align],address[class|align],"
            . "-h1[id|dir|class|align|style],-h2[id|dir|class|align|style],-h3[id|dir|class|align|style],"
            . "-h4[id|dir|class|align|style],-h5[id|dir|class|align|style],-h6[id|dir|class|align|style],hr[class],"
            . "dd[id|class|title|dir],dl[id|class|title|dir],dt[id|class|title|dir]",
        'extended_valid_elements' => "img[class|src|alt|title|hspace|vspace|width|height|align|name"
            . "|usemap|data*],iframe[src|name|width|height|align|frameborder|marginwidth|marginheight|scrolling],"
            . "object[width|height|data|type],param[name|value],map[class|name|id],area[shape|coords|href|target|alt]",
        'formats' => [
            'aligncenter' => [ 'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', 'styles' => ['text-align' => 'center']],
            'alignleft' => [ 'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', 'styles' => ['text-align' => 'left']],
            'alignright' => [ 'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', 'styles' => ['text-align' => 'right']],
            'alignfull' => [ 'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', 'styles' => ['text-align' => 'justify']]
        ],
        'style_formats' => [
            [ 'title' => 'Align center', 'format' => 'aligncenter' ],
            [ 'title' => 'Align left', 'format' => 'alignleft' ],
            [ 'title' => 'Align right', 'format' => 'alignright' ],
            [ 'title' => 'Justify', 'format' => 'alignfull' ],
        ]
    ]);
// enable ability to insert anchors
$editorConfig->insertButtonsAfter('sslink', 'anchor');
$editorConfig->removeButtons('anchor');
