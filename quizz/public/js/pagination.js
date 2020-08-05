var currentTab = 0; // La page actuelle est défini à 0
showTab(currentTab); // on affiche la page actuelle

// Bloquer le suivant si le formulaire n'est pas rempli !!
var check = document.getElementsByClassName('form-check-input');
for (let i=0 ; i<check.length;i++)
check[i].addEventListener("click",modifBtnSuite)

var textarea = document.getElementsByTagName('textarea');
for (let i = 0; i < textarea.length; i++) 
textarea[i].addEventListener("click", modifBtnSuite)

var input = document.getElementsByTagName('input');
for (let i = 0; i < input.length; i++)
    input[i].addEventListener("click", modifBtnSuite)

function modifBtnSuite(){ 
    var suiteValide = document.getElementById("nextBtn");
    suiteValide.disabled = false;
    suiteValide.addEventListener("click", disabled)
}
function disabled(){
    var suiteValide = document.getElementById("nextBtn");
    suiteValide.disabled = true;
}

function showTab(n) {
    // Cette fonction affichera la page spécifié du formulaire ...
    var form = document.getElementsByClassName('form-group');
    form[n].style.display = "block";

    // ... et mise en place du bouton Suivant et cacher le bouton valider
    if (n == (form.length - 1)) {
        document.getElementById("validBtn").style.display = "inline";
        document.getElementById("nextBtn").style.display = "none";

    } else {
        document.getElementById("validBtn").style.display = "none";
        // document.getElementById("nextBtn").innerHTML = "Suivant";
    }

    // ... et exécutez une fonction qui affiche l'indicateur de pas:
    fixStepIndicator(n);
}

function nextPrev(n) {

    // Cette fonction déterminera quel page afficher
    var form = document.getElementsByClassName("form-group");
    // Quitte la fonction si un champ de la page courante n'est pas valide:
    if (n == 1 && !validateForm()) return false;

    // Masquer la page actuelle:
    form[currentTab].style.display = "none";
    // Augmente ou diminue la page actuelle de 1:
    currentTab += n;
    // si vous avez atteint la fin du formulaire ...:
    if (currentTab >= form.length) {
        //... le formulaire est soumis:
        document.getElementsByClassName("form").submit();
        return false;
    }

    // Sinon, affichez la page correct:
    showTab(currentTab);

}

function validateForm() {

    // Cette fonction s'occupe de la validation des champs du formulaire
    var form, input, textarea, i, valid = true;
    form = document.getElementsByClassName("form-group");
    input = form[currentTab].getElementsByTagName("input");
    textarea = form[currentTab].getElementsByTagName("textarea");

    
    // Une boucle qui vérifie chaque champ d'entrée dans l'onglet courant:
    for (i = 0; i < input.length; i++) {

        // Si un champ est vide ...
        if (input[i].value == "") {
            // ajoute une classe "invalide" au champ:
            input[i].className += " invalid";
            // et définissez l'état valide actuel sur false:
            valid = false;
        }
    }

    for (i = 0; i < textarea.length; i++) {

        // Si un champ est vide ...
        if (textarea[i].value == "") {
            // ajoute une classe "invalide" au champ:
            textarea[i].className += " invalid";
            // et définissez l'état valide actuel sur false:
            valid = false;
        }
    }

    // Si l'état valide est vrai, marquez l'étape comme terminée et valide:
    if (valid) {
        document.getElementsByClassName("step")[currentTab].className += " finish";
    }
    return valid; // retourne le statut valide
}

function fixStepIndicator(n) {
    // Cette fonction supprime la classe "active" de toutes les étapes ...
    var i, form = document.getElementsByClassName("step");
    for (i = 0; i < form.length; i++) {
        form[i].className = form[i].className.replace(" active", "");
    }

    // ... et ajoute la classe "active" à l'étape courante:
    form[n].className += " active";
}