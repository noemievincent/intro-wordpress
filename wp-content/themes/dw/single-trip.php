<?php get_header(); ?>
<?php if(have_posts()): while (have_posts()): the_post(); ?>
    <main class="layout singleTrip">
        <h2 class="singleTrip__title"><?= get_the_title(); ?></h2>
        <dl class="singleTrip__taxonomies">
            <dt class="singleTrip__taxonomy">Pays&nbsp;:</dt>
            <dd class="singleTrip__terms">
                <?= implode(', ', array_map(fn($term) => $term->name, get_the_terms(get_the_ID(),'country'))) ?>
            </dd>
        </dl>
        <figure class="singleTrip__fig">
            <?= get_the_post_thumbnail(null, 'medium_large', ['class' => 'singleTrip__thumb']); ?>
        </figure>
        <div class="singleTrip__content">
            <?php the_content(); ?>
        </div>
        <aside class="singleTrip__details">
            <h3 class="singleTrip__subtitle"><?= __('Détails du voyage', 'dw') ?></h3>
            <dl class="singleTrip__definition">
                <dt class="singleTrip__label"><?= __('Date de départ', 'dw') ?></dt>
                <dd class="singleTrip__data">
                    <time class="singleTrip__time" datetime="<?php date('c', strtotime(get_field('departure_date', false, false)));?>"><?= ucwords(date_i18n('d F Y', strtotime(get_field('departure_date', false, false)))); ?></time>
                </dd>
                <dt class="singleTrip__label"><?= __('Date de retour', 'dw') ?></dt>
                <dd class="singleTrip__data">
                    <?php if (get_field('return_date')):?>
                    <time class="singleTrip__time" datetime="<?php date('c', strtotime(get_field('return_date', false, false)));?>"><?= ucwords(date_i18n('d F Y', strtotime(get_field('return_date', false, false)))); ?></time>
                    <?php else: ?>
                    <span class="singleTrip__empty"><?= __('Aucune date de retour prévue pour le moment.', 'dw') ?></span>
                    <?php endif; ?>
                </dd>
            </dl>
        </aside>
    </main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>
