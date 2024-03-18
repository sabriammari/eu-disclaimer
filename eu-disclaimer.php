<?php

/**
 * Plugin Name: eu-disclaimer
 * Plugin URI: https://fr.wordpress.org/plugins/
 * Description: Plugin sur la législation des produits à base de nicotine.
 * Version: 1.0
 * Author: Sabri AFPA
 * Author URI: http://www.afpa.fr
 * Licence: (Lien de la licence)
 */

// Création de la fonction pour ajouter le menu
// Définition de la fonction ajouterAuMenu
function ajouterAuMenu() {
    // Définition des paramètres pour l'ajout d'un élément de menu personnalisé

    // Identifiant unique de la page
    $page = 'eu-disclaimer';

    // Texte affiché dans le menu
    $menu = 'eu-disclaimer';

    // Capacité requise pour afficher cet élément de menu
    $capability = 'edit_pages';

    // Slug utilisé dans l'URL pour accéder à cette page
    $slug = 'eu-disclaimer';

    // Fonction qui sera appelée pour afficher le contenu de la page
    $function = 'disclaimerFonction';

    // Icône optionnelle affichée à côté de l'élément de menu
    $icon = '';

    // Position dans le menu
    $position = 80;

    // Vérifie si l'utilisateur est dans l'interface d'administration
    if (is_admin()) {
        // Si l'utilisateur est dans l'interface d'administration,
        // ajoute un élément de menu personnalisé à l'interface

        // Appelle la fonction add_menu_page() pour ajouter l'élément de menu
        add_menu_page($page, $menu, $capability, $slug, $function, $icon, $position);
    }
}

// Hook pour ajouter l'action 'admin_menu' <- emplacement / AjouterAuMenu <- fonction à appeler / <- priorité.
add_action("admin_menu", "ajouterAuMenu", 10);

// Fonction à appeler lorsque l'on clique sur le menu.
function disclaimerFonction() {
    // Inclut le fichier de vue pour le contenu du menu
    require_once ('../wp-content/plugins/eu-disclaimer/views/disclaimer-menu.php');
}

// Inclut le fichier de la classe de gestion de table
require_once ('Model/Repository/DisclaimerGestionTable.php');

// Si la classe existe, instancie la classe de gestion de table
if (class_exists("DisclaimerGestionTable")) {
    $gerer_table = new DisclaimerGestionTable();
}

// Si l'instance de la classe existe, enregistre les hooks d'activation et de désactivation
if (isset($gerer_table)) {
    register_activation_hook(__FILE__, array($gerer_table, 'creerTable'));
    register_deactivation_hook(__FILE__, array($gerer_table, 'supprimerTable'));
}

// Ajoute des scripts JavaScript à la fin du body
add_action('init', 'inserer_js_dans_footer');
function inserer_js_dans_footer() {
    if (!is_admin()) {
        // Enregistre et ajoute les scripts JS
        
        // Ajoute la librairie JavaScript jQuery 3.7.0
        wp_register_script('jQuery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js', array(), null, true);
        wp_enqueue_script('jQuery');

        // Ajoute la librairie JavaScript jQuery-modal 0.9.1
        wp_register_script('jQuery_modal', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js', array('jQuery'), null, true);
        wp_enqueue_script('jQuery_modal');

        // Ajoute la librairie JavaScript Bootstrap 5.3.1
        wp_register_script('Bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js', array(), null, true);
        wp_enqueue_script('Bootstrap');

        // Ajoute le script JavaScript du fichier créé dans le dossier 'assets/js'
        wp_register_script('jQuery_eu', plugins_url('assets/js/eu-disclaimer.js', __FILE__), array('jquery'), '1.1', true);
        wp_enqueue_script('jQuery_eu');
    }
}

// Ajoute des styles CSS dans la section head
add_action('wp_head', 'ajouter_css', 1);
function ajouter_css() {
    if (!is_admin()) {
        // Enregistre et ajoute les styles CSS

        // Ajoute la librairie CSS jQuery-modal 0.9.1
        wp_register_style('modal', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css', array(), null, false);
        wp_enqueue_style('modal');

        // Ajoute la librairie CSS Bootstrap 5.3.1
        wp_register_style('Bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css', array(), null, false);
        wp_enqueue_style('Bootstrap');

        // Ajoute le style du fichier créé dans le dossier 'assets/css'
        // Enregistre un fichier CSS pour utilisation ultérieure
        wp_register_style('eu-disclaimer.css', plugins_url('assets/css/eu-disclaimer-css.css', __FILE__), array(), null, false);

        // Enfile le fichier CSS enregistré pour qu'il soit ajouté à la page
        wp_enqueue_style('eu-disclaimer.css');

    }
}

// Utilisation du hook wp_body_open
add_action('wp_body_open', 'afficherModalDansBody');
function afficherModalDansBody() {
    // Instancie la classe de gestion de table et affiche le contenu modal
    $disclaimerGestionTable = new DisclaimerGestionTable();
    echo $disclaimerGestionTable->AfficherDonneeModal();
}
