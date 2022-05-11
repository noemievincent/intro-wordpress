<?php get_header(); ?>

<main class="layout">
    <section class="layout__trips trips">
        <h2 class="trips__title"><?= __('Tous mes voyages', 'dw'); ?></h2>
        <nav class="trips__countries">
            <h3 class="sro">Filtrer par pays</h3>
            <?php foreach(dw_get_countries() as $country) : ?>
                <a href="?filter-country=<?= $country->slug; ?>" class="trips__country"><?= $country->name; ?></a>
            <?php endforeach; ?>
        </nav>
        <div class="trips__container">
            <?php 
            if(have_posts()): while(have_posts()): the_post();
                dw_include('trip');
            endwhile; else: ?>
            <p class="trips__empty"><?= __('Il n’y a pas de voyages à vous raconter...', 'dw'); ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>