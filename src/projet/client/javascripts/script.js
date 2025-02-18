document.addEventListener("DOMContentLoaded", function() {
    const volcanList = document.getElementById("volcanList");
    const searchInput = document.getElementById("search");
    const countrySelect = document.getElementById("country");
    const loginBtn = document.getElementById("login");
    const logoutBtn = document.getElementById("logout");
    const adminInput = document.getElementById("admin");
    const passwordInput = document.getElementById("password");
    const errorMessage = document.getElementById("error-message");
    const BASE_URL = "http://127.0.0.1:8080/projet/server/server.php"

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

    searchInput.addEventListener("input", () => {
        afficherVolcans(searchInput.value, countrySelect.value);
    });

    countrySelect.addEventListener("change", () => {
        afficherVolcans(searchInput.value, countrySelect.value);
    });

    function login() {
        const nom = adminInput.value.trim();
        const pass = passwordInput.value.trim();
        
        if (!nom || !pass) {
            errorMessage.textContent = "Veuillez remplir tous les champs.";
            errorMessage.style.display = "block";
            return;
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: BASE_URL, // Assurez-vous que BASE_URL est défini !
            data: {
                action: "Post_checkLogin",
                Nom: nom,
                Pass: pass
            },
            success: function(response) {
                if (response.status === "success") {
                    alert("Connexion réussie !");
                    sessionStorage.setItem("isLoggedIn", "true");
    
                    // Disable and hide fields after login
                    adminInput.readOnly = true;
                    passwordInput.style.display = "none";
                    loginBtn.disabled = true;
                    logoutBtn.disabled = false;
                    errorMessage.style.display = "none"; // Hide error message on success
                } else {
                    errorMessage.textContent = response.message || "Nom d'utilisateur ou mot de passe incorrect.";
                    errorMessage.style.display = "block";
                }
            },
            error: function() {
                errorMessage.textContent = "Erreur de connexion au serveur.";
                errorMessage.style.display = "block";
            }
        });
    }

    function logout() {
        sessionStorage.removeItem("isLoggedIn");
        alert("Déconnexion réussie !");

        // Réactiver et réafficher les champs après déconnexion
        adminInput.readOnly = false;
        passwordInput.style.display = "inline-block";
        loginBtn.disabled = false;
        logoutBtn.disabled = true;
    }

    loginBtn.addEventListener("click", login);
    logoutBtn.addEventListener("click", logout);

    // Vérifier si l'utilisateur est déjà connecté au chargement de la page
    if (sessionStorage.getItem("isLoggedIn") === "true") {
        adminInput.readOnly = true;
        passwordInput.style.display = "none";
        loginBtn.disabled = true;
        logoutBtn.disabled = false;
    }

    afficherVolcans();
});
