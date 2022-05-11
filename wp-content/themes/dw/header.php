<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= wp_title('·', false, 'right') . get_bloginfo('name'); ?> </title>
    <link rel="stylesheet" type="text/css" href="<?= dw_mix('css/style.css'); ?>">
    <script type="text/javascript" src="<?= dw_mix('js/script.js'); ?>"></script>
    <?php wp_head(); ?>
</head>
<body>
<header class="header">
    <h1 class="header_title"><?= get_bloginfo('name'); ?></h1>
    <p class="header_tagline"><?= get_bloginfo('description'); ?></p>

    <nav class="header_nav nav">
        <h2 class="nav_title hidden"><?= __('Navigation principale', 'dw') ?></h2>
        <?php
        //        wp_nav_menu([
        //            'menu' => 'primary',
        //            'container_class' => 'nav__container',
        //            'menu_class' => 'nav__links',
        //            'menu_id' => 'navigation',
        //            'walker' => new PrimaryMenuWalker(),
        //        ]); ?>

        <ul class="nav__container">
            <?php foreach (dw_get_menu_items('primary') as $link): ?>
                <li class="<?= $link->getBemClasses('nav__item'); ?>">
                    <a href="<?= $link->url; ?>"
                        <?= $link->title ? ' title="' . $link->title . '"' : '';?>
                       class="nav__link">
                        <?= $link->label; ?>
                    </a>
                    <?php if ($link->hasSubItems()): ?>
                        <ul class="nav__subcontainer">
                            <?php foreach ($link->subitems as $sub): ?>
                                <li class="<?= $sub->getBemClasses('nav__item'); ?>">
                                    <a href="<?= $sub->url; ?>"
                                        <?= $sub->title ? ' title="' . $sub->title . '"' : '';?>
                                       class="nav__link">
                                        <?= $sub->label; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="nav__languages">
            <?php foreach(pll_the_languages(['raw' => true]) as  $code => $locale): ?>
                <a href="<?= $locale['url'] ?>" class="nav__locale" lang="<?= $locale['locale']?>" hreflang="<?= $locale['locale']?>" title="<?= $locale['name']?>">
                    <?= $code ?>
                </a>
            <?php endforeach; ?>
        </div>
    </nav>
</header>