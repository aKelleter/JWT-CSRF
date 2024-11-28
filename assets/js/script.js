console.log(document.cookie);

/**
 * Récupère la valeur d'un cookie en fonction de son nom
 * 
 * @param {*} name 
 */
function getCookie(name) {
    let cookie = document.cookie.match("(^|;|\s*)" + name + "\s*=\s*([^|;]+)");
    //DEBUG//console.log(cookie?.pop()); 
    return cookie?.pop() || '';  
}

//DEBUG//console.log(getCookie('csrf_token'));

document.querySelector("#request_token").addEventListener("click", function() {
    //DEBUG//console.log(getCookie('csrf_token'));

    // On récupère le csrf token dans le cookie
    const csrfToken = getCookie('csrf_token')

    // On l'envoie pour vérification
    fetch('check.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken // On modifie le token pour voir l'erreur
        },
        body: JSON.stringify({
            'message': 'Données sécurisées'
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        // ... faire quelque chose avec les données
    })
    .catch(error => {
        console.error('Erreur :', error);
    });
});

