<?php

// Charger les fichiers nécessaires
require_once( __DIR__ . '/Menus/PrimaryMenuWalker.php' );
require_once( __DIR__ . '/Menus/PrimaryMenuItem.php' );
require_once( __DIR__ . '/Forms/BaseFormController.php' );
require_once( __DIR__ . '/Forms/ContactFormController.php' );
require_once( __DIR__ . '/Forms/Sanitizers/BaseSanitizer.php' );
require_once( __DIR__ . '/Forms/Sanitizers/TextSanitizer.php' );
require_once( __DIR__ . '/Forms/Sanitizers/EmailSanitizer.php' );
require_once( __DIR__ . '/Forms/Validators/BaseValidator.php' );
require_once( __DIR__ . '/Forms/Validators/RequiredValidator.php' );
require_once( __DIR__ . '/Forms/Validators/EmailValidator.php' );
require_once( __DIR__ . '/Forms/Validators/AcceptedValidator.php' );

// Lancer la session PHP pour pouvoir passer des variables de page en page
add_action( 'init', 'dw_boot_theme', 1 );

function dw_boot_theme() {
	load_theme_textdomain( 'dw', __DIR__ . '/locales' );

	if ( ! session_id() ) {
		session_start();
	}
}

// Désactiver l'éditeur "Gutenberg" de Wordpress
add_filter( 'use_block_editor_for_post', '__return_false' );

// Activer les images sur les articles
add_theme_support( 'post-thumbnails' );

// Enregistrer un seul custom post-type pour "nos voyages"
register_post_type( 'trip', [
	'label'         => 'Voyages',
	'labels'        => [
		'name'          => 'Voyages',
		'singular_name' => 'Voyage',
	],
	'description'   => 'Tous les articles qui décrivent un voyage',
	'public'        => true,
	'has_archive' => true,
	'menu_position' => 5,
	'menu_icon'     => 'dashicons-airplane',
	'supports'      => [ 'title', 'editor', 'thumbnail' ],
	'rewrite'       => [ 'slug' => 'voyages' ],
] );

// Enregistrer un custom post-type pour les messages de contact
register_post_type( 'message', [
	'label'         => 'Messages de contact',
	'labels'        => [
		'name'          => 'Messages de contact',
		'singular_name' => 'Message de contact',
	],
	'description'   => 'Les messages envoyés par le formulaire de contact.',
	'public'        => false,
	'show_ui'       => true,
	'menu_position' => 15,
	'menu_icon'     => 'dashicons-buddicons-pm',
	'capabilities'  => [
		'create_posts'       => false,
		'read_post'          => true,
		'read_private_posts' => true,
		'edit_posts'         => true,
	],
	'map_meta_cap'  => true,
] );

// Register custom taxonomy
register_taxonomy( 'country', [ 'trip' ], [
	'labels'       => [
		'name'          => 'Pays',
		'singular_name' => 'Pays',
	],
	'description'  => 'Tous les pays que nous avons visités.',
	'public'       => true,
	'hierarchical' => true,
] );

// Récupérer les trips via une requête Wordpress
function dw_get_trips( $count = 20 ) {
	// 1. on instancie l'objet WP_Query
	$trips = new WP_Query( [
		'post_type'      => 'trip',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'posts_per_page' => $count,
	] );

	// 2. on retourne l'objet WP_Query
	return $trips;
}

// Récupérer les termes de la taxonomie 'pays'
function dw_get_countries() {
	return get_terms([
		'taxonomy' => 'country',
		'hide_empty' => false,
	]);
}

// Enregistrer les zones de menus

register_nav_menu( 'primary', 'Navigation principale (haut de page)' );
register_nav_menu( 'footer', 'Navigation de pied de page' );

// Fonction pour récupérer les éléments d'un menu sous forme d'un tableau d'objets

function dw_get_menu_items( $location ) {
	$items = [];

	// Récupérer le menu Wordpress pour $location
	$locations = get_nav_menu_locations();

	if ( ! ( $locations[ $location ] ?? false ) ) {
		return $items;
	}

	$menu = $locations[ $location ];

	// Récupérer tous les éléments du menu récupéré
	$posts = wp_get_nav_menu_items( $menu );

	// Formater chaque élément dans une instance de classe personnalisée
	// Boucler sur chaque $post
	foreach ( $posts as $post ) {
		// Transformer le WP_Post en une instance de notre classe personnalisée
		$item = new PrimaryMenuItem( $post );

		// Ajouter au tableau d'éléments de niveau 0.
		if ( ! $item->isSubItem() ) {
			$items[] = $item;
			continue;
		}

		// Ajouter $item comme "enfant" de l'item parent.
		foreach ( $items as $parent ) {
			if ( ! $parent->isParentFor( $item ) ) {
				continue;
			}

			$parent->addSubItem( $item );
		}
	}

	// Retourner un tableau d'éléments du menu formatés
	return $items;
}

// Gérer l'envoi de formulaire personnalisé

add_action( 'admin_post_submit_contact_form', 'dw_handle_submit_contact_form' );

function dw_get_contact_field_value( $field ) {
	if ( ! isset( $_SESSION['contact_form_feedback'] ) ) {
		return '';
	}

	return $_SESSION['contact_form_feedback']['data'][ $field ] ?? '';
}

function dw_get_contact_field_error( $field ) {
	if ( ! isset( $_SESSION['contact_form_feedback'] ) ) {
		return '';
	}

	if ( ! ( $_SESSION['contact_form_feedback']['errors'][ $field ] ?? null ) ) {
		return '';
	}

	return '<p>' . $_SESSION['contact_form_feedback']['errors'][ $field ] . '</p>';
}

// fonction qui charge les assets compilés et retourne leur chemin absolu

function dw_mix( $path ) {
	$path = '/' . ltrim( $path, '/' );

	if ( ! realpath( __DIR__ . '/public' . $path ) ) {
		return;
	}

	if ( ! ( $manifest = realpath( __DIR__ . '/public/mix-manifest.json' ) ) ) {
		return get_stylesheet_directory_uri() . '/public' . $path;
	}

	// Ouvrir le fichier mix-manifest.json
	$manifest = json_decode( file_get_contents( $manifest ), true );

	// Regarder si on a une clé qui correspond au fichier chargé dans $path
	if ( ! array_key_exists( $path, $manifest ) ) {
		return get_stylesheet_directory_uri() . '/public' . $path;
	}

	// Récupérer & retourner le chemin versionné
	return get_stylesheet_directory_uri() . '/public' . $manifest[ $path ];
}
