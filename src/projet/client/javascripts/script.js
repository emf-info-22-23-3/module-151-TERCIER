document.addEventListener("DOMContentLoaded", function() {
    const volcanList = document.getElementById("volcanList");
    const searchInput = document.getElementById("search");
    const countrySelect = document.getElementById("country");
    const loginBtn = document.getElementById("login");
    const logoutBtn = document.getElementById("logout");

    let volcans = [
        { nom: "Etna", pays: "Italie", lieu: "Sicile", altitude: "3,357m", latitude: "37.75", longitude: "15.00" },
        { nom: "Merapi", pays: "Indonésie", lieu: "Java", altitude: "2,930m", latitude: "-7.54", longitude: "110.44" },
        { nom: "Piton de la Fournaise", pays: "France", lieu: "Réunion", altitude: "2,632m", latitude: "-21.23", longitude: "55.71" },
        { nom: "Stromboli", pays: "Italie", lieu: "Îles Éoliennes", altitude: "924m", latitude: "38.79", longitude: "15.21" }
    ];

    function afficherVolcans(filtreNom = "", filtrePays = "") {
        volcanList.innerHTML = "";

        let filtered = volcans.filter(volcan => 
            (filtreNom === "" || volcan.nom.toLowerCase().includes(filtreNom.toLowerCase())) &&
            (filtrePays === "" || volcan.pays === filtrePays)
        );

        filtered.forEach(volcan => {
            let volcanCard = document.createElement("div");
            volcanCard.classList.add("volcan-card");

            volcanCard.innerHTML = `
                <h3>${volcan.nom}</h3>
                <p><strong>Lieu :</strong> ${volcan.lieu}</p>
                <p><strong>Altitude :</strong> ${volcan.altitude}</p>
                <p><strong>Latitude :</strong> ${volcan.latitude}</p>
                <p><strong>Longitude :</strong> ${volcan.longitude}</p>
            `;

            volcanList.appendChild(volcanCard);
        });
    }

    // Écouteur pour la recherche
    searchInput.addEventListener("input", () => {
        afficherVolcans(searchInput.value, countrySelect.value);
    });

    // Écouteur pour la sélection du pays
    countrySelect.addEventListener("change", () => {
        afficherVolcans(searchInput.value, countrySelect.value);
    });

    /*// Simuler une connexion
    loginBtn.addEventListener("click", () => {
        alert("Connexion réussie !");
        loginBtn.disabled = true;
        logoutBtn.disabled = false;
    });

    // Simuler une déconnexion
    logoutBtn.addEventListener("click", () => {
        alert("Déconnexion réussie !");
        loginBtn.disabled = false;
        logoutBtn.disabled = true;
    });*/

    document.addEventListener("DOMContentLoaded", function () {
        const loginBtn = document.getElementById("login");
        const logoutBtn = document.getElementById("logout");
        const adminInput = document.getElementById("admin");
        const passwordInput = document.getElementById("password");
    
        function login() {
            const nom = adminInput.value;
            const pass = passwordInput.value;
    
            $.ajax({
                type: "POST",
                dataType: "json",
                url: BASE_URL,
                data: {
                    action: "Post_checkLogin",
                    Nom: nom,
                    Pass: pass
                },
                success: function (response) {
                    if (response.success) {
                        alert("Connexion réussie !");
                        sessionStorage.setItem("isLoggedIn", "true");
                        loginBtn.disabled = true;
                        logoutBtn.disabled = false;
                    } else {
                        alert("Échec de la connexion : " + response.message);
                    }
                },
                error: function () {
                    alert("Erreur lors de la tentative de connexion.");
                }
            });
        }
    
        function logout() {
            sessionStorage.removeItem("isLoggedIn");
            alert("Déconnexion réussie !");
            loginBtn.disabled = false;
            logoutBtn.disabled = true;
        }
    
        loginBtn.addEventListener("click", login);
        logoutBtn.addEventListener("click", logout);
    
        // Vérifier si l'utilisateur est déjà connecté
        if (sessionStorage.getItem("isLoggedIn") === "true") {
            loginBtn.disabled = true;
            logoutBtn.disabled = false;
        }
    });

    // Charger les volcans au démarrage
    afficherVolcans();
});
