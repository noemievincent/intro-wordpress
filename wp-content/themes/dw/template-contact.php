<?php /* Template Name: Contact page template */ ?>
<?php get_header(); ?>
<?php if(have_posts()): while (have_posts()): the_post(); ?>
    <main class="layout contact">
        <h2 class="contact__title"><?= get_the_title(); ?></h2>
        <div class="contact__content">
            <?php the_content(); ?>
        </div>
        <form class="contact__form" action="<?= get_home_url(); ?>/wp-admin/admin-post.php" method="POST">
            <div class="form__field">
                <label for="firstname" class="form__label">Votre prénom</label>
                <input type="text" name="firstname" id="firstname" class="form__input">
            </div>
            <div class="form__field">
                <label for="lastname" class="form__label">Votre nom</label>
                <input type="text" name="lastname" id="lastname" class="form__input">
            </div>
            <div class="form__field">
                <label for="email" class="form__label">Votre adresse e-mail</label>
                <input type="email" name="email" id="email" class="form__input">
            </div>
            <div class="form__field">
                <label for="phone" class="form__label">Votre téléphone</label>
                <input type="tel" name="phone" id="phone" class="form__input">
            </div>
            <div class="form__field">
                <label for="message" class="form__label">Votre message</label>
                <textarea name="message" id="message" cols="30" rows="10" class="form__input"></textarea>
            </div>
            <div class="form__field">
                <label for="rules" class="form__label">
                    <input type="checkbox" id="rules" value="1">
                    <span class="form__checklabel">J'accepte les <a href="#">conditions générales d'utilisations</a>.</span>
                </label>
            </div>
            <div class="form__actions">
                <?php wp_nonce_field('nonce_submit_contact'); ?>
                <input type="hidden" name="action" value="submit_contact_form"/>
                <button class="form__button" type="submit">Envoyer</button>
            </div>
        </form>
    </main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>
