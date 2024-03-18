<?php
// Définition du chemin d'accès à la classe DisclaimerOptions
define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Inclure la classe DisclaimerOptions
include(MY_PLUGIN_PATH . '../Entity/DisclaimerOptions.php');

// Déclaration de la classe de gestion de la table
class DisclaimerGestionTable
{

    // Méthode pour créer la table
    public function creerTable()
    {
        // Instanciation de la classe DisclaimerOptions
        $message = new DisclaimerOptions();

        // Alimenter l'objet message avec les valeurs par défaut grâce au setter
        $message->setMessage_disclaimer("Au regard de la loi européenne, vous devez nous confirmer que vous avez plus de 18 ans pour visiter ce site.");
        $message->setRedirection_ko("https://cnct.fr/les-avertissements-sanitaires-obligatoires-sur-les-unites-de-conditionnement-des-produits-du-tabac-et-produits-du-vapotage/");

        // Global $wpdb pour accéder à la base de données
        global $wpdb;

        // Nom de la table
        $tableDisclaimer = $wpdb->prefix . 'disclaimer_options';

        // Création de la table si elle n'existe pas
        if ($wpdb->get_var("SHOW TABLES LIKE '$tableDisclaimer'") != $tableDisclaimer) {
            /**
             * id_disclaimer INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY : Définit une colonne 'id_disclaimer' de type entier non signé (INT UNSIGNED) qui ne peut pas être nulle (NOT NULL). Cette colonne est définie comme clé primaire et utilise l'auto-incrémentation (AUTO_INCREMENT) pour générer des valeurs uniques.
             * message_disclaimer TEXT NOT NULL : Définit une colonne 'message_disclaimer' de type texte (TEXT) qui ne peut pas être nulle (NOT NULL).
             * redirection_ko TEXT NOT NULL : Définit une colonne 'redirection_ko' de type texte (TEXT) qui ne peut pas être nulle (NOT NULL).
             * ENGINE=InnoDB : Spécifie le moteur de stockage InnoDB pour la table. InnoDB est un moteur de stockage transactionnel pris en charge par MySQL.
             * DEFAULT CHARSET=utf8mb4 : Spécifie le jeu de caractères par défaut pour la table comme étant 'utf8mb4', ce qui prend en charge une gamme étendue de caractères Unicode.
             * COLLATE=utf8mb4_unicode_ci : Spécifie le jeu de règles de comparaison (collation) comme étant 'utf8mb4_unicode_ci', qui prend en charge les comparaisons de chaînes Unicode de manière insensible à la casse.
             */
            $sql = "CREATE TABLE $tableDisclaimer (
                id_disclaimer INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                message_disclaimer TEXT NOT NULL,
                redirection_ko TEXT NOT NULL
            )
            ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ";

            // Création de la table et insertion du message par défaut
            if (!$wpdb->query($sql)) {
                die("Une erreur est survenue, contactez le développeur du plugin...");
            }
            // Insère des données dans la table 'disclaimer_options' en utilisant l'objet $wpdb
            $wpdb->insert(
                $wpdb->prefix . 'disclaimer_options',
                // Nom complet de la table avec le préfixe
                array(
                    'message_disclaimer' => $message->getMessage_disclaimer(),
                    // Colonne message_disclaimer
                    'redirection_ko' => $message->getRedirection_ko(),
                    // Colonne redirection_ko
                ),
                array(
                    '%s',
                    // Type de données pour la colonne message_disclaimer
                    '%s' // Type de données pour la colonne redirection_ko
                )
            );

            // Exécute la requête SQL pour supprimer la table
            $wpdb->query($sql);
        }
    }

    // Méthode pour supprimer la table
    public function supprimerTable()
    {
        // Instance globale de la classe de gestion de la base de données de WordPress
        global $wpdb;

        // Nom de la table 'disclaimer_options' avec le préfixe de la base de données de WordPress
        $table_disclaimer = $wpdb->prefix . "disclaimer_options";

        // Requête SQL pour supprimer complètement la table
        $sql = "DROP TABLE $table_disclaimer";

        // Exécute la requête SQL pour supprimer la table
        $wpdb->query($sql);

    }

    // Méthode statique pour insérer dans la table
    public static function insererDansTable($contenu, $url)
    {
        // Instance globale de la classe de gestion de la base de données de WordPress
        global $wpdb;

        // Nom de la table 'disclaimer_options' avec le préfixe de la base de données de WordPress
        $table_disclaimer = $wpdb->prefix . 'disclaimer_options';

        // Requête SQL préparée pour mettre à jour les valeurs dans la table
        $sql = $wpdb->prepare(
            "UPDATE $table_disclaimer SET message_disclaimer = '%s', redirection_ko = '%s' WHERE id_disclaimer = %s",
            $contenu,
            $url,
            1
        );

        // Exécute la requête SQL préparée
        $wpdb->query($sql);

    }

    // Méthode pour afficher le contenu modal
    public function AfficherDonneeModal()
    {
        // Instance globale de la classe de gestion de la base de données de WordPress
        global $wpdb;

        // Requête SQL pour sélectionner toutes les colonnes de la table 'disclaimer_options' avec le préfixe de la base de données
        $query = "SELECT * FROM {$wpdb->prefix}disclaimer_options";

        // Récupère une seule ligne résultante de la requête
        $row = $wpdb->get_row($query);

        // Récupère la valeur de la colonne 'message_disclaimer' de la ligne récupérée
        $message_disclaimer = $row->message_disclaimer;

        // Récupère la valeur de la colonne 'redirection_ko' de la ligne récupérée
        $lien_redirection = $row->redirection_ko;

        // Construction du contenu modal
        return '
        <div id="monModal" class="modal position-absolute top-50 start-50 translate-middle w-100 h-auto my-auto text-center border border-warning">
            <p class="mt-3 fs-3 fw-bold"><span class="fs-1 fst-italic">Vapobar</span><br> vous souhaite la bienvenue !</p>
            <p class="mb-3">' . $message_disclaimer . '</p>
            <form id="ageCalculatorForm">
                <p>Veuillez renseigner votre <span class="fw-bold">date de naissance</span></p>
                <div class="row">
                    <div class="col mb-3">
                        <label for="dayOfBirth" class="form-label">Jour</label>
                        <input type="text" placeholder="JJ" name="dayOfBirth" class="form-control text-center border border-primary" id="dayOfBirth" required>
                    </div>
                    <div class="col mb-3">
                        <label for="monthOfBirth" class="form-label">Mois</label>
                        <input type="text" placeholder="MM" name="monthOfBirth" class="form-control text-center border border-primary" id="monthOfBirth" required>
                    </div>
                    <div class="col mb-4">
                        <label for="yearOfBirth" class="form-label">Année</label>
                        <input type="text" placeholder="AAAA" name="yearOfBirth" class="form-control text-center border border-primary" id="yearOfBirth" required>
                    </div>
                </div>
                <button type="button" class="btn btn-warning" id="calculateAge">Vérifier votre âge</button>
                <p id="ageResult" class="mt-3"></p>
            </form>

            <div class="row justify-content-center mb-3">
                <a href="' . $lien_redirection . '" type="button" class="col-4 btn-red me-2" id="cancelDisclaimer">Sortir</a>
                <a href="" name="submit" rel="modal:close" class="col-4 btn-green ms-2" id="actionDisclaimer">Oui</a>
            </div>
        </div>


        <div id="infoModal" class="modalInfo">
            <div class="modal-content rounded rounded-3 d-flex position-fixed top-50 start-50 translate-middle text-center w-50">
                <p class="fs-5">
                    La nicotine contenue dans ces produits crée une forte dépendance.
                    <br>
                    Son utilisation par les non-fumeurs n’est pas recommandée.
                    <br>
                    SI VOUS NE FUMEZ PAS, NE VAPOTEZ PAS. 
                </p>
                <a href="" class="close btn-green text-bg-success w-25 mx-auto">D\'accord</a>
            </div>
        </div>
        ';
    }
}
