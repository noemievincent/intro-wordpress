<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Intro Wordpress</title>
</head>
<body>
    <header class="header">
        <h1 class="header_title"><?= get_bloginfo('name'); ?></h1>
        <p class="header_tagline"><?= get_bloginfo('description'); ?></p>

        <nav class="header_nav nav">
            <h2 class="nav_title">Navigation principale</h2>
            <?php wp_nav_menu([
                    'menu' => 'primary',
                    'container_class' => 'nav__container',
                    'menu_class' => 'nav__links',
                    'menu_id' =>  'navigation',
                    'walker' => new PrimaryMenuWalker(),
                ]); ?>
        </nav>
    </header>
