<?php
    $title = strlen($config->title) ? $config->title : 'API Documentation';
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title><?= $title; ?></title>
        <link href="/source/stylesheets/screen.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="/source/stylesheets/print.css" rel="stylesheet" type="text/css" media="print" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="/source/javascripts/lib/_jquery_ui.js" type="text/javascript"></script>

        <script src="/source/javascripts/lib/_jquery.highlight.js" type="text/javascript"></script>
        <script src="/source/javascripts/lib/_lunr.js" type="text/javascript"></script>
        <script src="/source/javascripts/app/_search.js" type="text/javascript"></script>

        <script src="/source/javascripts/lib/_jquery.tocify.js" type="text/javascript"></script>
        <script src="/source/javascripts/lib/_imagesloaded.min.js" type="text/javascript"></script>
        <script src="/source/javascripts/app/_lang.js" type="text/javascript"></script>
        <link rel="stylesheet" href="/source/stylesheets/prism.css">
        <link rel="stylesheet" href="/source/stylesheets/prism_overrides.css">
        <script src="/source/javascripts/lib/prism.js"></script>

        <script src="/source/javascripts/app/_toc.js" type="text/javascript"></script>
        <script>
            $(function() {
                // Code blocks
                $('pre').hide();
                $('code').each(function(index, elem) {
                    var $elem = $(elem);
                    var elemClass = $elem.attr('class');

                    if (elemClass) {
                        var lang = elemClass.split('-')[1];
                        // $elem.addClass('highlight');
                        $elem.addClass(lang);
                        $elem.parent().addClass(lang);
                    } else {
                        $elem.addClass('language-none');
                    }
                });

                <?php if ($config->language_tabs): ?>
                    // @TODO: Why do we need this to be debounced?
                    window.setTimeout(function() {
                        setupLanguages([<?= '"' . implode('","', $config->language_tabs) . '"'; ?>]);
                    }, 0);
                <?php endif; ?>
            });
        </script>
    </head>

    <body class="index">
        <div class="tocify-wrapper">
            <img src="/source/images/logo.png">
            <?php if ($config->language_tabs): ?>
                <div class="lang-selector">
                    <?php foreach($config->language_tabs as $lang): ?>
                        <a href="#" data-language-name="<?= $lang; ?>"><?= $lang; ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($config->search): ?>
                <div class="search">
                    <input type="text" class="search" id="input-search" placeholder="Search">
                </div>
                <ul class="search-results"></ul>
            <?php endif; ?>

            <div id="toc"></div>

            <?php if ($config->toc_footers): ?>
                <ul class="toc-footer">
                    <?php foreach($config->toc_footers as $footer): ?>
                        <li><?= $footer; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="page-wrapper">
            <div class="dark-box"></div>
            <div class="content">
                <?php foreach($pages as $page => $content): ?>
                    <?= $content; ?>
                <?php endforeach; ?>
            </div>
            <div class="dark-box">
                <?php if ($config->language_tabs): ?>
                    <div class="lang-selector">
                        <?php foreach($config->language_tabs as $lang): ?>
                            <a href="#" data-language-name="<?= $lang; ?>"><?= $lang; ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>
