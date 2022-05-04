<?php /* Template Name: Contact page template */ ?>
<?php get_header(); ?>
<?php if(have_posts()): while(have_posts()): the_post(); ?>
    <main class="layout contact">
        <h2 class="contact__title"><?= get_the_title(); ?></h2>
        <div class="contact__content">
            <?php the_content(); ?>
        </div>
        <?php if(! isset($_SESSION['contact_form_feedback']) || ! $_SESSION['contact_form_feedback']['success']) : ?>
            <form action="<?= get_home_url(); ?>/wp-admin/admin-post.php" method="POST" class="contact__form form" id="contact">
                <?php if(isset($_SESSION['contact_form_feedback'])) : ?>
                    <p><?= __('Oups ! Il y a des erreurs dans le formulaire', 'dw') ?></p>
                <?php endif; ?>
                <div class="form__field">
                    <label for="firstname" class="form__label"><?= __('Votre prénom', 'dw') ?></label>
                    <input type="text" name="firstname" id="firstname" class="form__input" value="<?= dw_get_contact_field_value('firstname'); ?>">
                    <?= dw_get_contact_field_error('firstname'); ?>
                </div>
                <div class="form__field">
                    <label for="lastname" class="form__label"><?= __('Votre nom', 'dw') ?></label>
                    <input type="text" name="lastname" id="lastname" class="form__input" value="<?= dw_get_contact_field_value('lastname'); ?>">
                    <?= dw_get_contact_field_error('lastname'); ?>
                </div>
                <div class="form__field">
                    <label for="email" class="form__label"><?= __('Votre adresse e-mail', 'dw') ?></label>
                    <input type="email" name="email" id="email" class="form__input" value="<?= dw_get_contact_field_value('email'); ?>">
                    <?= dw_get_contact_field_error('email'); ?>
                </div>
                <div class="form__field">
                    <label for="phone" class="form__label"><?= __('Votre numéro de téléphone', 'dw') ?></label>
                    <input type="tel" name="phone" id="phone" class="form__input" value="<?= dw_get_contact_field_value('phone'); ?>">
                    <?= dw_get_contact_field_error('phone'); ?>
                </div>
                <div class="form__field">
                    <label for="message" class="form__label"><?= __('Votre message', 'dw') ?></label>
                    <textarea name="message" id="message" cols="30" rows="10" class="form__input"><?= dw_get_contact_field_value('message'); ?></textarea>
                    <?= dw_get_contact_field_error('message'); ?>
                </div>
                <div class="form__field">
                    <label for="rules" class="form__checkbox">
                        <input type="checkbox" name="rules" id="rules" value="1" />
                        <span class="form__checklabel"><?= str_replace(':conditions', '<a href="#">' . __('conditions générals d’utilisations', 'dw') . '</a>', __('J’accepte les :conditions', 'dw')) ?></span>
                    </label>
                    <?= dw_get_contact_field_error('rules'); ?>
                </div>
                <div class="form__actions">
                    <?php wp_nonce_field('nonce_submit_contact'); ?>
                    <input type="hidden" name="action" value="submit_contact_form" />
                    <button class="form__button" type="submit"><?= __('Envoyer', 'dw') ?></button>
                </div>
            </form>
        <?php else : ?>
            <p id="contact"><?= __('Merci ! Votre message a bien été envoyé.', 'dw') ?></p>
            <?php unset($_SESSION['contact_form_feedback']); endif; ?>
    </main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>