<?php get_header(); ?>

<main class="layout">
    <section class="results">
        <h2><?= __('Articles correspondant à votre recherche', 'dw'); ?></h2>
        <div class="results__container">
            <?php
            if(have_posts()): while(have_posts()): the_post();
                dw_include('post', ['modifier' => 'search']);
            endwhile; else: ?>
                <div class="results__empty">
                    <p><?= __('Il n\'y a pas d\'articles pour votre recherche.', 'dw'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <section class="results">
        <h2><?= __('Voyages correspondant à votre recherche', 'dw'); ?></h2>
        <?php 
        if(($trips = dw_get_trips(3, get_search_query()))->have_posts()): while($trips->have_posts()): $trips->the_post();
            dw_include('trip');
        endwhile; else: ?>
        <p class="results__empty"><?= __('Il n\'y a pas de voyages correspondant à votre recherche...', 'dw'); ?></p>
        <?php endif; ?>
    </section>
</main>

<?php get_footer(); ?>