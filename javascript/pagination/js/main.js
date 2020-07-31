var letterList = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
let numberOfItems = 5; //nombre d'élements afficher sur la page
let first = 0; //commencement de la pagination
let actualPage = 1;
let maxPages = Math.ceil(letterList.length / numberOfItems);
showList();

// variable button
var premiere = document.getElementById("firstPage");
var precedente = document.getElementById("previous");
var suivante = document.getElementById("nextPage");
var derniere = document.getElementById("lastPage");

// boutton
premiere.addEventListener("click",firstPage)
precedente.addEventListener("click", previous)
suivante.addEventListener("click", nextPage)
derniere.addEventListener("click", lastPage)


// Création de notre array et l’afficher
function showList() {
    let tableList = "";
    for (let i = first; i < first + numberOfItems; i++) {
        // on affiche que le contenu du tableau
        if (i < letterList.length) {
            tableList += `<tr><td>${letterList[i]}</td></tr>`;
            
        }
    }
    document.getElementById('letterList').innerHTML = tableList;
    showPageInfo();
}

// Afficher les prochaines lettres
function nextPage() {
    if (first + numberOfItems <= letterList.length) {
        first += numberOfItems;
        actualPage++;
        showList();
    }
}

// Retourner en arrière
function previous() {
    if (first - numberOfItems >= 0) {
        first -= numberOfItems
        actualPage--;
        showList();
    }
}

// Afficher la première page
function firstPage() {
    first = 0
    actualPage = 1;
    showList();
}

// Afficher la dernière page
function lastPage() {
    first = (maxPages * numberOfItems) - numberOfItems;
    actualPage = maxPages;
    showList();
}

// Afficher les informations de page
function showPageInfo() {
    document.getElementById('pageInfo').innerHTML = `Page ${actualPage} / ${maxPages}`;
}