<?php get_header(); ?>

<main class="layout">
	<section class="layout__trips trips">
		<h2 class="trips__title"><?= __('Mes voyages', 'dw') ?></h2>
        <nav class="trips__countries">
            <h3 class="sro"><?= __('Filtrer par pays', 'dw') ?></h3>
            <?php foreach (dw_get_countries() as $country) : ?>
                <a href="?filter-country=<?= $country->slug ?>" class="trips__country"><?= $country->name ?></a>
            <?php endforeach; ?>
        </nav>
		<div class="trips__container">
			<?php if (have_posts()): while (have_posts()): the_post();?>
				<article class="trip">
					<a href="<?= get_the_permalink(); ?>" class="trip__link"><?= __('Lire le récit de voyage ', 'dw') ?>"<?= get_the_title(); ?>"</a>
					<div class="trip__card">
						<header class="trip__head">
							<h3 class="trip__title"><?= get_the_title(); ?></h3>
							<p class="trip__meta"><time class="trip__time" datetime="<?= date('c', strtotime(get_field('departure_date', false, false))); ?>"><?= ucwords(date_i18n('F, Y', strtotime(get_field('departure_date', false, false)))); ?></time></p>
						</header>
						<figure class="trip__fig">
							<?= get_the_post_thumbnail(null, 'thumbnail', ['class' => 'trips__thumb']); ?>
						</figure>
					</div>
				</article>
			<?php endwhile; else: ?>
				<p class="trips__empty"><?= __('Il n’y a pas de voyages à vous raconter...', 'dw') ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>

