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
    let editVolcanPaysId = null; // Variable pour stocker l'ID du pays
    let isLoggedIn;

    function toggleAddVolcanButton() {
        // Afficher ou masquer le bouton Ajouter un volcan en fonction de l'état de connexion
        if (isLoggedIn) {
            
        } else {
            
        }
    }

    function showPopup(volcan = null) {
        popup.style.display = "flex";
    
        if (volcan) {
            popupTitle.textContent = "Modifier le volcan";
            popupSave.textContent = "Enregistrer";
            document.getElementById("popup-nom").value = volcan.nom;
            document.getElementById("popup-lieu").value = volcan.pays;
            document.getElementById("popup-altitude").value = volcan.altitude;
            document.getElementById("popup-latitude").value = volcan.latitude;
            document.getElementById("popup-longitude").value = volcan.longitude;
            editVolcanId = volcan.id; // Garder une trace de l'ID du volcan à modifier
            editVolcanPaysId = Object.keys(paysMap).find(key => paysMap[key] === volcan.pays); // Assigner l'ID du pays
        } else {
            popupTitle.textContent = "Ajouter un volcan";
            popupSave.textContent = "Ajouter";
            document.getElementById("popup-nom").value = "";
            document.getElementById("popup-lieu").value = "";
            document.getElementById("popup-altitude").value = "";
            document.getElementById("popup-latitude").value = "";
            document.getElementById("popup-longitude").value = "";
            editVolcanId = null; // Réinitialiser l'ID pour une nouvelle création
            editVolcanPaysId = null; // Réinitialiser l'ID du pays pour une nouvelle création
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
        isLoggedIn = sessionStorage.getItem("isLoggedIn") === "true";

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
                modifyBtn.classList.add("button", "modify-btn"); // Ajout des classes CSS
                modifyBtn.onclick = () => {
                    editVolcanId = volcan.id;  // Remplir editVolcanId avec l'ID du volcan à modifier
                    showPopup(volcan); // Afficher le popup pour modifier ce volcan
                };
            
                addVolcanBtn.style.display = "block";  // Afficher le bouton si l'utilisateur est connecté

                let deleteBtn = document.createElement("button");
                deleteBtn.textContent = "Supprimer";
                deleteBtn.classList.add("button", "delete-btn"); // Ajout des classes CSS
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

    let id;

    function saveVolcan() {

        if (!editVolcanId) {
            alert("Aucun volcan à modifier.");
            return;
        }

        const data = {
            action: "Update_volcan",
            id : editVolcanId,
            nom: document.getElementById("popup-nom").value.trim(),
            lieu: document.getElementById("popup-lieu").value.trim(),
            altitude: document.getElementById("popup-altitude").value.trim(),
            latitude: document.getElementById("popup-latitude").value.trim(),
            longitude: document.getElementById("popup-longitude").value.trim(),
            pays: Object.keys(paysMap).find(key => paysMap[key] === volcan.pays)  // Ajout de la clé pays
        };

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

    popupSave.addEventListener("click", function() {
        if (popupSave.textContent === "Modifier le volcan") {
            saveVolcan();  // Appelle la fonction saveVolcan() si le texte est "Modifier le volcan"
        } else {
            addVolcan();   // Sinon, appelle la fonction addVolcan()
        }
    });

    popupClose.addEventListener("click", closePopup);

    function deleteVolcan(id) {
        if (!confirm("Voulez-vous vraiment supprimer ce volcan ?")) return;

        // Préparer les données à envoyer dans le corps de la requête
        const data = JSON.stringify({ id: id });

        $.ajax({
            type: "DELETE",
            dataType: "json",
            url: BASE_URL,
            contentType: "application/json",  // Indiquer que les données sont en JSON
            data: data,  // Passer les données JSON dans le corps de la requête
            success: function (response) {
                if (response.status === "success") {
                    alert("Volcan supprimé !");
                    fetchVolcans();  // Recharger la liste des volcans après la suppression
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

    function addVolcan() {
        const data = {
            nom: document.getElementById("popup-nom").value.trim(),
            altitude: document.getElementById("popup-altitude").value.trim(),
            latitude: document.getElementById("popup-latitude").value.trim(),
            longitude: document.getElementById("popup-longitude").value.trim(),
            pays: editVolcanPaysId // ID du pays sélectionné
        };
    
        if (!data.nom || !data.altitude || !data.latitude || !data.longitude || !data.pays) {
            alert("Tous les champs doivent être remplis.");
            return;
        }
    
        // Envoi de la requête PUT pour ajouter un volcan
        $.ajax({
            type: "PUT",
            dataType: "json",
            url: BASE_URL,
            contentType: "application/json",  // Indiquer que les données sont en JSON
            data: JSON.stringify(data),  // Convertir les données en JSON
            success: function(response) {
                if (response.status === "success") {
                    alert("Volcan ajouté avec succès !");
                    fetchVolcans();  // Recharger les volcans
                    hidePopup();  // Fermer le popup
                } else {
                    alert("Erreur : " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Erreur AJAX:", status, error);
                alert("Une erreur est survenue : " + error);
            }
        });
    }
    

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
                    isLoggedIn = true;
                    
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
                afficherVolcans();
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
        adminInput.textContent = "";
        passwordInput.style.display = "inline-block";
        passwordInput.textContent = "";
        loginBtn.disabled = false;
        logoutBtn.disabled = true;
        isLoggedIn = false;
        addVolcanBtn.style.display = "none";   // Cacher le bouton si l'utilisateur n'est pas connecté

        afficherVolcans();
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
    // Initialisation du bouton "Ajouter un volcan" au chargement de la page
    toggleAddVolcanButton();
});
