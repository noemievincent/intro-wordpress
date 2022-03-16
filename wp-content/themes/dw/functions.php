<?php

// Charger les fichiers nécessaires
require_once(__DIR__ . '/Menus/PrimaryMenuWalker.php');
require_once(__DIR__ . '/Menus/PrimaryMenuItem.php');

// Désactiver l'éditeur "Gutenberg" de Wordpress
add_filter('use_block_editor_for_post', '__return_false');

// Activer les images sur les articles
add_theme_support('post-thumbnails');

// Enregistrer un custom post-type pour "nos voyages"
register_post_type('trip', [
    'label' => 'Voyages',
    'labels' => [
        'name' => 'Voyages',
        'singular_name' => 'Voyage',
    ],
    'description' => 'Tous les articles qui décrivent un voyage',
    'public' => true,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-airplane',
    'supports' => ['title', 'editor', 'thumbnail'],
    'rewrite' => ['slug' => 'voyages'],
]);

// Enregistrer un custom post-type pour les messages de contact
register_post_type('message', [
    'label' => 'Messages de contact',
    'labels' => [
        'name' => 'Messages de contact',
        'singular_name' => 'Message de contact',
    ],
    'description' => 'Les messages envoyés par le formulaire de contact',
    'public' => false,
    'show_ui' => true,
    'menu_position' => 15,
    'menu_icon' => 'dashicons-buddicons-pm',
    'capabilities' => [
        'create_posts' => false,
        'read_posts' => true,
        'read_private_posts' => true,
        'edit_posts' => true,
    ],
    'map_meta_cap' => true,
]);

// Récupérer les trips via une requête Wordpress
function dw_get_trips($count = 20)
{
    // 1. on instancie l'objet WP_Query
    $trips = new WP_Query([
        'post_type' => 'trip',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => $count,
    ]);
    // 2. on retourne l'objet WP_Query
    return $trips;
}

// Enregister les zones de menus
register_nav_menu('primary', 'Navigation principale (haut de page)');
register_nav_menu('footer', 'Navigation de pied de page');

// Fonction pour récupérer les éléments d'un menu sous forme d'un tableau d'objet
function dw_get_menu_items($location)
{
    $items = [];

    // Récupérer le menu WordPress pour $location
    $locations = get_nav_menu_locations();

    if (!($locations[$location] ?? false)) {
        return $items;
    }

    $menu = $locations[$location];
    // Récupérer tous les éléments du menu récupéré
    $posts = wp_get_nav_menu_items($menu);

    // Formater chaque élément dans une instance de classe personnalisée
    // Boucler sur chaque $post
    foreach ($posts as $post) {
        // Transformer le WP_Post en une instance de notre classe personnalisée
        $item = new PrimaryMenuItem($post);

        // Ajouter au tableau d'éléments de niveau 0
        if (!$item->isSubItem()) {
            $items[] = $item;
            continue;
        }

        // Ajouter $item comme "enfant" de l'item parent
        foreach ($items as $parent) {
            if (!$parent->isParentFor($item)) continue;

            $parent->addSubItem($item);
        }
    }

    // Retourner un tableau d'éléments du menu formatés
    return $items;
}

// Gérer l'envoi de formulaire personnalisé

add_action('admin_post_submit_contact_form', 'dw_handle_submit_contact_form');

function dw_handle_submit_contact_form()
{
    $nonce = $_POST['_wpnonce'];

    if (wp_verify_nonce($nonce, 'nonce_submit_contact')) {
        // TODO : retourner un message d'erreur "not authorized"
    }

    $data = dw_sanitize_contact_form_data();

    if ($errors = dw_validate_contact_form_data($data)) {
        // Pas OK
        return $errors;
    }

    // C'est OK

    $id = wp_insert_post([
        'post_title' => 'Message de ' . $data['firstname'] . ' ' . $data['lastname'],
        'post_type' => 'message',
        'post_content' => $data['message'],
        'post_status' => 'publish',
    ]);

    // Générer un email contenant l'url vers le post en question
    $feedback = 'Bonjour, vous avez un nouveau message.';
    $feedback .= ' Y accéder : ' . get_edit_post_link($id);

    // Envoyer l'email à l'admin
    wp_mail(get_bloginfo('admin_email'), 'Nouveau message !', $feedback);

    // TODO : retourner un message d'erreur "not authorized"
}

function dw_sanitize_contact_form_data()
{
    return [
        'firstname' => sanitize_text_field($_POST['firstname']),
        'lastname' => sanitize_text_field($_POST['lastname']),
        'email' => sanitize_email($_POST['email']),
        'phone' => sanitize_text_field($_POST['phone']),
        'message' => sanitize_text_field($_POST['message'] ?? ''),
        'rules' => sanitize_text_field($_POST['rules'] ?? ''),
    ];
}

function dw_validate_contact_form_data($data)
{
    $errors = [];
    $required = ['firstname', 'lastname', 'email', 'message'];
    $email = ['email'];
    $accepted = ['rules'];

    foreach ($data as $key => $value) {
        if (in_array($key, $required) && !$value) {
            $errors[$key] = 'required';
            continue;
        }

        if (in_array($key, $email) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[$key] = 'email';
            continue;
        }

        if (in_array($key, $accepted) && $value != '1') {
            $errors[$key] = 'accepted';
            continue;
        }
    }

    return $errors ? false : true;
}