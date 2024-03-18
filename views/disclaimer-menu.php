<?php

// Déclaration d'une variable pour suivre l'état de l'opération d'insertion à la base de données
$insertion_reussie = false;

// Opération d'insertion d'un nouveau message_disclaimer et d'une nouvelle url_redirection
// Vérifie si les valeurs 'message_disclaimer' et 'url_redirection' existent dans la soumission du formulaire
if (!empty($_POST['message_disclaimer']) && !empty($_POST['url_redirection'])) {
    // Si les valeurs sont présentes dans la soumission du formulaire

    // Crée une instance de DisclaimerOptions
    $text = new DisclaimerOptions();

    // Sécurise les valeurs contre les attaques XSS en utilisant 'htmlspecialchars()'
    $text->setMessage_disclaimer(htmlspecialchars($_POST['message_disclaimer']));
    $text->setRedirection_ko(htmlspecialchars($_POST['url_redirection']));

    // Insère les valeurs sécurisées dans une table à l'aide de DisclaimerGestionTable::insererDansTable()
    DisclaimerGestionTable::insererDansTable($text->getMessage_disclaimer(), $text->getRedirection_ko());

    // Indique que l'insertion dans la table a réussi
    $insertion_reussie = true;


    // Condition de la bonne ou mauvaise procédure lors du changement des données de la base de données
    if ($insertion_reussie) {
        echo '
        <div style="background-color: green; color: white; margin: 25px; padding: 10px; text-align: center; width: 65%; margin-left: auto; margin-right: auto;">
            Le nouveau "message du disclaimer" ainsi que la nouvelle "URL de redirection" ont été modifiés dans la base de données avec succès.
        </div>
        ';
    } else {
        echo '
        <div style="background-color: red; color: white; margin: 25px; padding: 10px; text-align: center; width: 65%; margin-left: auto; margin-right: auto;">
            Le nouveau "message du disclaimer" ainsi que la nouvelle "URL de redirection" n\'ont pas pu être modifier dans la base de données, il s\'est produit une erreur !
        </div>
        ';
    }

}

// Requête pour récupérer les données de la table 'wp_disclaimer_options'
// L'objet WordPress pour les requêtes SQL

// Instance globale de la classe de gestion de la base de données de WordPress
global $wpdb;

// Nom de la table de base de données préfixé par le préfixe de la base de données de WordPress
$tableDisclaimer = $wpdb->prefix.'disclaimer_options';

// Exécute une requête pour obtenir une seule ligne de la table $tableDisclaimer
$resultats = $wpdb->get_row("SELECT * FROM $tableDisclaimer");

// Vérifier si la requête a retourné des résultats
if ($resultats) {
    // Si des résultats sont retournés, utiliser les valeurs de la base de données

    // Récupère la valeur de 'message_disclaimer' depuis la ligne retournée
    $message_disclaimer = $resultats->message_disclaimer;

    // Récupère la valeur de 'redirection_ko' depuis la ligne retournée
    $redirection_ko = $resultats->redirection_ko;
}


?>

<!-- Début création de la page admin pour le changement du message_disclaimer et de l'url_redirection dans la base de données -->
<div style="text-align: center;">
    <h1>EU DISCLAIMER</h1>
    <br>
    <h2>Configuration</h2>
    <br>
    <form action="" method="post" novalidate="novalidate" style="margin-left: auto; margin-right: auto; width: 50%;">
        <table class="form-table">
            <tr>
                <th scope="row" style="text-align: end;">
                    <label for="blogname">Message du disclaimer</label>
                </th>
                <td style="text-align: start;">
                    <input type="text" name="message_disclaimer" id="message_disclaimer" placeholder="<?php echo esc_attr($message_disclaimer); ?>" class="regular-text" required>
                </td>
            </tr>
            <tr>
                <th scope="row" style="text-align: end;">
                    <label for="blogname">URL de redirection</label>
                </th>
                <td style="text-align: start;">
                    <input type="url" name="url_redirection" id="url_redirection" placeholder="<?php echo esc_attr($redirection_ko); ?>" class="regular-text" required>
                </td>
            </tr>
        </table>
        <p class="submit" style="text-align: center;">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Enregistrer les modifications">
        </p>
    </form>
    <br>
    <p>
        Exemple: La législation nous impose de vous informer sur la novicité des produits à base de nicotine, vous devez avoir plus de 18 ans pour consulter ce site !
    </p>
    <br>
    <h3>Centre AFPA / session DWWM</h3>
    <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'assets/img/layout_set_logo.png'; ?>" width="10%">
</div>
<!-- Fin création de la page admin pour le changement du message_disclaimer et de l'url_redirection dans la base de données -->

<script>

    // Sélection des éléments du DOM
    const message_disclaimer = document.getElementById('message_disclaimer');
    const url_redirection = document.getElementById('url_redirection');
    const submit = document.getElementById('submit');

    // Fonction pour vérifier si les champs sont vides
    function verifierChamps() {
        if (message_disclaimer.value === '' || url_redirection.value === '') {
            submit.disabled = true; // Désactiver le bouton
        } else {
            submit.disabled = false; // Activer le bouton
        }
    }

    // Écouteurs d'événements pour les champs de texte
    message_disclaimer.addEventListener('input', verifierChamps);
    url_redirection.addEventListener('input', verifierChamps);

    // Appel initial pour vérifier l'état initial des champs
    verifierChamps();

</script>