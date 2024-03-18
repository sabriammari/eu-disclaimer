
// Création d'un cookie pour notre projet
/*
    La fonction accepte trois paramètres :
    nomCookie: Le nom du cookie à créer.
    valeurCookie: La valeur associée au cookie.
    dureeJours: La durée en millisecondes pendant laquelle le cookie doit être actif.
 */
function creerUnCookie(nomCookie, valeurCookie, dureeJours) {
    if (dureeJours) {

        // Une instance de Date est créée pour représenter le moment actuel.
        var date = new Date();

        // Mettre le nombre de millisecondes que vous voulez pour que le cookie expire ici en exemple nous avons mis 10 secondes (à modifier selon vos préférences.)
        date.setTime(date.getTime() + (dureeJours * 10 * 1000));

        // La variable expire est créée en utilisant la chaîne "; expires=" suivie de la valeur de la date d'expiration formatée en utilisant toGMTString().
        var expire = "; expires=" + date.toGMTString();
    } else {
        var expire = "";
    }

    // La propriété document.cookie est utilisée pour créer le cookie en concaténant le nom du cookie, sa valeur, la chaîne expire, et la spécification du chemin "; path=/".
    document.cookie = nomCookie + "=" + valeurCookie + expire + "; path=/";
}


// Création d'une fonction pour lire le cookie
function lireUnCookie(nomCookie) {

    // On crée une variable en ajoutant le nom du cookie à la chaîne "=" pour former le format attendu du nom du cookie dans les cookies.
    var nomFormate = nomCookie + "=";  // Format attendu du nom du cookie suivi d'un '='
    
    // On tente de récupérer sa valeur à partir des cookies stockés dans la propriété document.cookie.
    // La variable tableauCookies est créé en séparant la chaîne des cookies en un tableau en utilisant le point-virgule comme délimiteur.
    var tableauCookies = document.cookie.split(';');  // Sépare la chaîne des cookies en un tableau

    // La boucle for parcourt chaque élément dans le tableau des cookies.
    for (var i = 0; i < tableauCookies.length; i++) {
        var cookieTrouve = tableauCookies[i];

        // Supprime les espaces en début de cookie
        // La condition while vérifie si le cookie commence par un espace.
        while (cookieTrouve.charAt(0) == ' ') {

            // Si c'est le cas, il extrait la valeur du cookie (après le nom formaté) en utilisant substring().
            cookieTrouve = cookieTrouve.substring(1, cookieTrouve.length);
        }

        // Vérifie si le cookie commence par le nom attendu
        if (cookieTrouve.indexOf(nomFormate) == 0) {

            // Retourne la valeur du cookie (après le nom formaté)
            return cookieTrouve.substring(nomFormate.length, cookieTrouve.length);
        }
    }

    // Si le cookie n'est pas trouvé, retourne null
    return null;
}



// Masque l'élément ayant l'ID "cancelDisclaimer" c'est à dire le bouton Sortir
$("#cancelDisclaimer").hide();

// Masque l'élément ayant l'ID "actionDisclaimer" c'est à dire le bouton Oui
$("#actionDisclaimer").hide();

// Lorsque l'utilisateur cliquera sur le bouton Calculer votre âge.
$("#calculateAge").on("click", function() {

    // Obtenir la date actuelle
    var currentDate = new Date();

    // Obtenir l'année actuelle
    var currentYear = currentDate.getFullYear();

    // Déclarer les variables qui représentent les valeurs des champs que l'utilisateur a renseigné
    var dayOfBirth = parseInt($("#dayOfBirth").val());
    var monthOfBirth = parseInt($("#monthOfBirth").val());
    var yearOfBirth = parseInt($("#yearOfBirth").val());

    // Création d'un objet Date à partir de la date de naissance fournie
    var birthDate = new Date(yearOfBirth, monthOfBirth - 1, dayOfBirth); // Soustrayant 1 du mois car les mois en JavaScript sont indexés à partir de 0 (janvier = 0)

    // Calcul de la différence en millisecondes entre la date actuelle et la date de naissance
    var ageInMilliseconds = currentDate - birthDate;

    // Création d'un nouvel objet Date à partir de la différence en millisecondes pour obtenir une date qui représente l'âge
    var ageDate = new Date(ageInMilliseconds);

    // Obtenir l'année de l'âge à partir de l'objet Date de l'âge en utilisant getUTCFullYear()
    // Soustraire 1970 car la méthode getUTCFullYear() renvoie l'année à partir de 1970 (qui est utilisé comme référence pour l'heure UNIX)
    var years = ageDate.getUTCFullYear() - 1970;

    // Vérifier que les champs sont remplis
    if (isNaN(dayOfBirth) || isNaN(monthOfBirth) || isNaN(yearOfBirth)) {
        $("#ageResult").text("Oops, vous devez entrer votre date de naissance !");

    // Vérifier que les valeurs ne sont PAS valides
    } else if (dayOfBirth < 1 || dayOfBirth > 31 || monthOfBirth < 1 || monthOfBirth > 12 || yearOfBirth < 1900 || yearOfBirth > currentYear) {
        $("#ageResult").text("Oops, veuillez entrer une date valide !");
    } else {

        // Vérifie si l'âge est supérieur ou égal à 18 ans
        if (years >= 18) {

            // Si l'âge est supérieur ou égal à 18 ans
            // Met à jour le contenu de l'élément ayant l'ID "ageResult"
            $("#ageResult").text("Vous avez plus de 18 ans, voulez-vous continuer sur notre site ?");

            // Affiche le bouton ayant l'ID "cancelDisclaimer" c'est à dire le bouton Sortir
            $("#cancelDisclaimer").show();

            // Met à jour le contenu de l'élément ayant l'ID "cancelDisclaimer"
            $("#cancelDisclaimer").text("Non");

            // Change le href du lien du bouton ayant l'ID "cancelDisclaimer" c'est à dire le bouton Sortir
            $("#cancelDisclaimer").prop("href", "http://www.google.fr/")

            // Affiche le bouton ayant l'ID "actionDisclaimer" c'est à dire le bouton Oui
            $("#actionDisclaimer").show();
        } else {

            // Si l'âge est inférieur à 18 ans
            // Met à jour le contenu de l'élément ayant l'ID "ageResult"
            $("#ageResult").text("Désolé, vous avez moins de 18 ans, vous allez être redirigé, merci de votre visite.");

            // Affiche le bouton ayant l'ID "cancelDisclaimer" c'est à dire le bouton Sortir
            $("#cancelDisclaimer").show();

            // Masque le bouton ayant l'ID "actionDisclaimer" c'est à dire le bouton Oui
            $("#actionDisclaimer").hide();
        }
    }
});



// Action lors du click du bouton Oui
$("#actionDisclaimer").on("click", accepterLeDisclaimer)

// Création d'une fonction que l'on va associer au Oui de notre modal par le biais de onclick
// Définition de la fonction accepterLeDisclaimer
function accepterLeDisclaimer() {

    // Crée un cookie appelé 'eu-disclaimer-cookie' avec la valeur 'AnaNDabarrRassi' et une durée de 10 secondes.
    creerUnCookie('eu-disclaimer-cookie', 'AnaNDabarrRassi', 1);

    // Lit le contenu du cookie 'eu-disclaimer-cookie'
    var cookie = lireUnCookie('eu-disclaimer-cookie');

    // // Affiche une alerte sur l'écran du cookie 'eu-disclaimer-cookie'
    // alert(cookie);

    // Récupérer le bouton qui ferme le modal
    var btn = $(".close")[0];
    // Change le style du modal pour l'afficher en utilisant la propriété CSS "display", passant de display: none à display: block
    $("#infoModal").css("display", "block");

    // Lorsque l'utilisateur clique sur le bouton, fermer le modal
    $(btn).on("click", function() {

        // change le style du modal pour le cacher en utilisant la propriété CSS "display", passant de display: block à display: none
        $("#infoModal").css("display", "none");
    });

    // Lorsque l'utilisateur clique n'importe où en dehors du modal cela fermera le modal
    $(window).on("click", function(event) {

        // Vérifie si l'élément sur lequel l'utilisateur a cliqué est le modal lui-même
        if (event.target === $("#infoModal")[0]) {

            // Si l'élément sur lequel l'utilisateur a cliqué est le modal,
            // change le style du modal pour le cacher en utilisant la propriété CSS "display", passant de display: block à display: none
            $("#infoModal").css("display", "none");
        }
    });
}



// Condition : Vérifie si la valeur du cookie 'eu-disclaimer-cookie' n'est pas égale à 'AnaNDabarrRassi'
if (lireUnCookie('eu-disclaimer-cookie') != 'AnaNDabarrRassi') {

    // Si la valeur du cookie n'est pas égale à 'AnaNDabarrRassi'
    // Initialise le modal de la librairie jQuery-Modal avec certaines options de comportement
    $("#monModal").modal({

        // Désactive la fermeture du modal en appuyant sur la touche "Escape"
        escapeClose: false,

        // Désactive la fermeture du modal en cliquant à l'extérieur de celui-ci
        clickClose: false,

        // Désactive l'affichage du bouton de fermeture dans le coin du modal
        showClose: false
    });
}
