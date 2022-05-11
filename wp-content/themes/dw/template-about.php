<?php /* Template Name: About page template */ ?>
<?php get_header(); ?>
<?php if(have_posts()): while (have_posts()): the_post(); ?>
    <main class="layout about">
        <h2 class="about__title"><?= get_the_title(); ?></h2>
        <div class="about__content">
            <section class="about__hobbies">
                <h2><?= __('Mes loisirs', 'dw'); ?></h2>
                <div class="wysiwyg">
                    <?= get_field('hobbies'); ?>
                </div>
            </section>
            <section class="about__presentation">
                <h2><?= __('Qui suis-je ?', 'dw'); ?></h2>
                <div class="wysiwyg">
                    <?= get_field('presentation'); ?>
                </div>
            </section>
        </div>
    </main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>