document.addEventListener("DOMContentLoaded", function () {
    const volcanList = document.getElementById("volcanList");
    const searchInput = document.getElementById("search");
    const countrySelect = document.getElementById("country");
    const loginBtn = document.getElementById("login");
    const logoutBtn = document.getElementById("logout");
    const adminInput = document.getElementById("admin");
    const passwordInput = document.getElementById("password");
    const errorMessage = document.getElementById("error-message");
    const BASE_URL = "http://127.0.0.1:8080/projet/server/server.php"
    let volcans = [];
    let pays = [];
    let paysMap = {};
    const addVolcanBtn = document.getElementById("add-volcan");
    const popup = document.getElementById("volcan-popup");
    const popupTitle = document.getElementById("popup-title");
    const popupSave = document.getElementById("popup-save");
    const popupClose = document.getElementById("popup-close");
    let editVolcanId = null; // Déclarer editVolcanId pour suivre l'ID du volcan à modifier

    function showPopup(volcan = null) {
        popup.style.display = "flex";

        if (volcan) {
            popupTitle.textContent = "Modifier le volcan";
            popupSave.textContent = "Enregistrer";
            document.getElementById("popup-nom").value = volcan.nom;
            document.getElementById("popup-lieu").value = volcan.lieu;
            document.getElementById("popup-altitude").value = volcan.altitude;
            document.getElementById("popup-latitude").value = volcan.latitude;
            document.getElementById("popup-longitude").value = volcan.longitude;
            editVolcanId = volcan.id; // Garder une trace de l'ID du volcan à modifier
        } else {
            popupTitle.textContent = "Ajouter un volcan";
            popupSave.textContent = "Ajouter";
            document.getElementById("popup-nom").value = "";
            document.getElementById("popup-lieu").value = "";
            document.getElementById("popup-altitude").value = "";
            document.getElementById("popup-latitude").value = "";
            document.getElementById("popup-longitude").value = "";
            editVolcanId = null; // Réinitialiser l'ID pour une nouvelle création
        }
    }

    function hidePopup() {
        popup.style.display = "none";
    }

    addVolcanBtn.addEventListener("click", () => showPopup());
    popupClose.addEventListener("click", hidePopup);

    function fetchVolcans() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: BASE_URL,
            data: { action: "Get_volcans" },
            success: function (response) {
                console.log("Réponse des volcans reçue : ", response);  // Debug
                if (Array.isArray(response)) { 
                    volcans = response.map(volcan => ({
                        id: volcan.pk_Volcan,  
                        nom: volcan.nom,
                        altitude: volcan.altitude,
                        latitude: volcan.latitude,
                        longitude: volcan.longitude,
                        pays: paysMap[volcan.pk_Pays] || "Inconnu" 
                    }));
                    console.log("Volcans après mapping : ", volcans);  // Debug
                    afficherVolcans();  // Appel pour afficher les volcans après récupération
                } else {
                    console.error("Réponse inattendue du serveur:", response);
                }
            },
            error: function (xhr, status, error) {
                console.error("Erreur AJAX:", status, error);
                alert("Une erreur est survenue : " + error);
            }
        });
    }
    

    function fetchPays() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: BASE_URL,
            data: { action: "Get_pays" },
            success: function (response) {
                if (Array.isArray(response)) {
                    paysMap = response.reduce((acc, pays) => {
                        acc[pays.pk_Pays] = pays.nom; // Associe ID → Nom
                        return acc;
                    }, {});
                    remplirListePays();
                    fetchVolcans(); // Charger les volcans après avoir les pays
                } else {
                    console.error("Réponse inattendue du serveur:", response);
                }
            },
            error: function (xhr, status, error) {
                console.error("Erreur AJAX:", status, error);
                alert("Une erreur est survenue : " + error);
            }
        });
    }

    fetchPays();

    function remplirListePays() {
        countrySelect.innerHTML = `<option value="">Tous les pays</option>`;

        // Utilisation correcte des pays depuis paysMap
        Object.values(paysMap).forEach(paysNom => {
            let option = document.createElement("option");
            option.value = paysNom;
            option.textContent = paysNom;
            countrySelect.appendChild(option);
        });
    }

    function afficherVolcans(filtreNom = "", filtrePays = "") {
        volcanList.innerHTML = "";
        const filtreNomMinuscule = filtreNom.toLowerCase();
        let isLoggedIn = sessionStorage.getItem("isLoggedIn") === "true";

        let filtered = volcans.filter(volcan =>
            (filtreNom === "" || volcan.nom.toLowerCase().includes(filtreNomMinuscule)) &&
            (filtrePays === "" || volcan.pays === filtrePays)
        );

        if (filtered.length === 0) {
            volcanList.innerHTML = `<p>Aucun volcan trouvé.</p>`;
        }

        filtered.forEach(volcan => {
            let volcanCard = document.createElement("div");
            volcanCard.classList.add("volcan-card");

            volcanCard.innerHTML = `
                <h3>${volcan.nom}</h3>
                <p><strong>Pays :</strong> ${volcan.pays}</p>
                <p><strong>Altitude :</strong> ${volcan.altitude} m</p>
                <p><strong>Latitude :</strong> ${volcan.latitude}</p>
                <p><strong>Longitude :</strong> ${volcan.longitude}</p>
            `;

            if (isLoggedIn) {
                let modifyBtn = document.createElement("button");
                modifyBtn.textContent = "Modifier";
                modifyBtn.style.backgroundColor = "#ffc107";
                modifyBtn.onclick = () => showPopup(volcan);

                let deleteBtn = document.createElement("button");
                deleteBtn.textContent = "Supprimer";
                deleteBtn.style.backgroundColor = "#dc3545";
                deleteBtn.onclick = () => deleteVolcan(volcan.id);

                volcanCard.appendChild(modifyBtn);
                volcanCard.appendChild(deleteBtn);
            }

            volcanList.appendChild(volcanCard);
        });
    }

    function closePopup() {
        popup.style.display = "none";
    }

    function saveVolcan() {
        const data = {
            action: editVolcanId ? "Update_volcan" : "Add_volcan",
            nom: document.getElementById("popup-nom").value.trim(),
            lieu: document.getElementById("popup-lieu").value.trim(),
            altitude: document.getElementById("popup-altitude").value.trim(),
            latitude: document.getElementById("popup-latitude").value.trim(),
            longitude: document.getElementById("popup-longitude").value.trim(),
            pays: document.getElementById("popup-pays").value // Ajout de la clé pays
        };

        if (editVolcanId) {
            data.id = editVolcanId;
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: BASE_URL,
            data: data,
            success: function (response) {
                if (response.status === "success") {
                    alert(editVolcanId ? "Volcan modifié !" : "Volcan ajouté !");
                    fetchVolcans();
                    closePopup();
                } else {
                    alert("Erreur : " + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("Erreur AJAX:", status, error);
                alert("Une erreur est survenue : " + error);
            }
        });
    }


    popupSave.addEventListener("click", saveVolcan);
    popupClose.addEventListener("click", closePopup);

    function deleteVolcan(id) {
        if (!confirm("Voulez-vous vraiment supprimer ce volcan ?")) return;

        $.ajax({
            type: "POST",
            dataType: "json",
            url: BASE_URL,
            data: { action: "Delete_volcan", id: id },
            success: function (response) {
                if (response.status === "success") {
                    alert("Volcan supprimé !");
                    fetchVolcans();
                } else {
                    alert("Erreur : " + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("Erreur AJAX:", status, error);
                alert("Une erreur est survenue : " + error);
            }
        });
    }

    searchInput.addEventListener("input", () => {
        afficherVolcans(searchInput.value, countrySelect.value);
    });

    countrySelect.addEventListener("change", () => {
        afficherVolcans(searchInput.value, countrySelect.value);
    });


    // let volcans = [
    //     { nom: "Etna", pays: "Italie", lieu: "Sicile", altitude: "3,357m", latitude: "37.75", longitude: "15.00" },
    //     { nom: "Merapi", pays: "Indonésie", lieu: "Java", altitude: "2,930m", latitude: "-7.54", longitude: "110.44" },
    //     { nom: "Piton de la Fournaise", pays: "France", lieu: "Réunion", altitude: "2,632m", latitude: "-21.23", longitude: "55.71" },
    //     { nom: "Stromboli", pays: "Italie", lieu: "Îles Éoliennes", altitude: "924m", latitude: "38.79", longitude: "15.21" }
    // ];

    // function afficherVolcans(filtreNom = "", filtrePays = "") {
    //     volcanList.innerHTML = "";

    //     let filtered = volcans.filter(volcan => 
    //         (filtreNom === "" || volcan.nom.toLowerCase().includes(filtreNom.toLowerCase())) &&
    //         (filtrePays === "" || volcan.pays === filtrePays)
    //     );

    //     filtered.forEach(volcan => {
    //         let volcanCard = document.createElement("div");
    //         volcanCard.classList.add("volcan-card");

    //         volcanCard.innerHTML = `
    //             <h3>${volcan.nom}</h3>
    //             <p><strong>Lieu :</strong> ${volcan.lieu}</p>
    //             <p><strong>Altitude :</strong> ${volcan.altitude}</p>
    //             <p><strong>Latitude :</strong> ${volcan.latitude}</p>
    //             <p><strong>Longitude :</strong> ${volcan.longitude}</p>
    //         `;

    //         volcanList.appendChild(volcanCard);
    //     });
    // }

    // searchInput.addEventListener("input", () => {
    //     afficherVolcans(searchInput.value, countrySelect.value);
    // });

    // countrySelect.addEventListener("change", () => {
    //     afficherVolcans(searchInput.value, countrySelect.value);
    // });

    //----------------login----------------

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
            success: function (response) {
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
            error: function () {
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
