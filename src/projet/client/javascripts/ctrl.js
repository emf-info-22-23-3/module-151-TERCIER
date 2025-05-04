/*
 * Fichier principal de contrôle de l'application (MVC - Contrôleur).
 * Gère l'affichage de la liste des volcans, les filtres, l'authentification,
 * ainsi que l'ajout, la modification et la suppression des volcans.
 * 
 * @author tercicer colin
 * @version 2.0 / 27.04.2025
 */

document.addEventListener("DOMContentLoaded", async () => {
    // Initialisation
    const BASE_URL = "http://localhost:8080/projet/server/server.php"; //local
    //const BASE_URL = "http://tercierc.emf.informatique.ch/151/projet/server/server.php"; //Cpanel
    const wrk = new WrkHTTPS(BASE_URL);

    // Sélection des éléments DOM
    const volcanList = document.getElementById("volcanList");
    const searchInput = document.getElementById("search");
    const countrySelect = document.getElementById("country");
    const loginBtn = document.getElementById("login");
    const logoutBtn = document.getElementById("logout");
    const adminInput = document.getElementById("admin");
    const passwordInput = document.getElementById("password");
    const errorMessage = document.getElementById("error-message");
    const addVolcanBtn = document.getElementById("add-volcan");
    const popup = document.getElementById("volcan-popup");
    const popupTitle = document.getElementById("popup-title");
    const popupSave = document.getElementById("popup-save");
    const popupClose = document.getElementById("popup-close");

    // Variables d'état
    let paysMap = {};
    let volcans = [];
    let isLoggedIn = sessionStorage.getItem("isLoggedIn") === "true";
    let editVolcanId = null;

    // Charge les pays et les volcans au démarrage
    async function chargerPaysEtVolcans() {
        paysMap = await wrk.fetchPays();
        remplirListePays();
        volcans = await wrk.fetchVolcans(paysMap);
        afficherVolcans();
    }

    // Remplit le sélecteur de pays
    function remplirListePays() {
        countrySelect.innerHTML = `<option value="">Tous les pays</option>`;
        Object.values(paysMap).forEach(nom => {
            const opt = document.createElement("option");
            opt.value = nom;
            opt.textContent = nom;
            countrySelect.appendChild(opt);
        });
    }

    // Affiche la liste des volcans filtrés (ou non)
    function afficherVolcans(filtreNom = "", filtrePays = "") {
        volcanList.innerHTML = "";
        const nom = filtreNom.toLowerCase();

        let filtres = volcans.filter(v =>
            (!filtreNom || v.nom.toLowerCase().includes(nom)) &&
            (!filtrePays || v.pays === filtrePays)
        );

        if (filtres.length === 0) volcanList.innerHTML = "<p>Aucun volcan trouvé.</p>";

        filtres.forEach(v => {
            const card = document.createElement("div");
            card.classList.add("volcan-card");
            card.innerHTML = `
                <h3>${v.nom}</h3>
                <p><strong>Pays :</strong> ${v.pays}</p>
                <p><strong>Altitude :</strong> ${v.altitude} m</p>
                <p><strong>Latitude :</strong> ${v.latitude}</p>
                <p><strong>Longitude :</strong> ${v.longitude}</p>
            `;
            if (isLoggedIn) {
                // Disable and hide fields after login
                adminInput.readOnly = true;
                passwordInput.style.display = "none";
                loginBtn.disabled = true;
                logoutBtn.disabled = false;
                errorMessage.style.display = "none";

                // Si connecté, ajouter les boutons modifier/supprimer
                let mBtn = document.createElement("button");
                mBtn.textContent = "Modifier";
                mBtn.classList.add("button", "modify-btn"); // Ajout des classes CSS
                mBtn.onclick = () => {
                    editVolcanId = v.id;
                    showPopup(v);
                };

                addVolcanBtn.style.display = "block";  // Afficher le bouton si l'utilisateur est connecté
                addVolcanBtn.addEventListener("click", () => showPopup());
                //popupClose.addEventListener("click", hidePopup);

                let dBtn = document.createElement("button");
                dBtn.textContent = "Supprimer";
                dBtn.classList.add("button", "delete-btn"); // Ajout des classes CSS
                dBtn.onclick = async () => {
                    if (confirm("Confirmer suppression ?")) {
                        await wrk.deleteVolcan(v.id);
                        await chargerPaysEtVolcans();
                    }
                };
                card.appendChild(mBtn);
                card.appendChild(dBtn);
            }
            volcanList.appendChild(card);
        });
    }

    // Affiche la popup d'ajout/modification de volcan
    function showPopup(volcan = null) {
        popup.style.display = "flex";
        popupTitle.textContent = volcan ? "Modifier le volcan" : "Ajouter un volcan";
        popupSave.textContent = volcan ? "Enregistrer" : "Ajouter";
        document.getElementById("popup-nom").value = volcan?.nom || "";
        document.getElementById("popup-lieu").value = volcan?.pays || "";
        document.getElementById("popup-altitude").value = volcan?.altitude || "";
        document.getElementById("popup-latitude").value = volcan?.latitude || "";
        document.getElementById("popup-longitude").value = volcan?.longitude || "";
    }

    // Ferme la popup
    popupClose.addEventListener("click", () => popup.style.display = "none");

    // Sauvegarde un volcan (nouveau ou modifié) à partir de la popup
    popupSave.addEventListener("click", async () => {
        const volcan = {
            nom: document.getElementById("popup-nom").value.trim(),
            altitude: parseFloat(document.getElementById("popup-altitude").value),
            latitude: parseFloat(document.getElementById("popup-latitude").value),
            longitude: parseFloat(document.getElementById("popup-longitude").value),
            pays: Object.keys(paysMap).find(key => paysMap[key] === document.getElementById("popup-lieu").value)
        };

        if (popupSave.textContent === "Ajouter") {
            await wrk.addVolcan(volcan);
        } else {
            await wrk.saveVolcan({ id: editVolcanId, ...volcan });
        }

        popup.style.display = "none";
        await chargerPaysEtVolcans();
    });

    // Mise à jour de l'affichage des volcans en fonction des filtres
    searchInput.addEventListener("input", actualiserVolcansFiltres);
    countrySelect.addEventListener("change", actualiserVolcansFiltres);

    async function actualiserVolcansFiltres() {
        const nom = searchInput.value.trim();
        const paysNom = countrySelect.value;
        const paysId = Object.keys(paysMap).find(key => paysMap[key] === paysNom) || '';
    
        const response = await wrk.fetchVolcansFiltered(nom, paysId);
        volcans = response.map(v => ({
            id: v.pk_Volcan,
            nom: v.nom,
            altitude: v.altitude,
            latitude: v.latitude,
            longitude: v.longitude,
            pays: v.pk_Pays
        }));
        afficherVolcans();
    }

    // Gestion du login
    loginBtn.addEventListener("click", async () => {
        const nom = adminInput.value.trim();
        const pass = passwordInput.value.trim();
        if(nom == null || nom == "" && pass == null || pass == ""){
            errorMessage.textContent = "veuillez remplire tout les champs"
            errorMessage.style.display = "block";
        }else{
            try {
                const res = await wrk.login(nom, pass);
                if (res.status === "success") {
                    alert("Connexion réussie !");
                    sessionStorage.setItem("isLoggedIn", "true");
                    isLoggedIn = true;
                    afficherVolcans();

                    // Disable and hide fields after login
                    adminInput.readOnly = true;
                    passwordInput.style.display = "none";
                    loginBtn.disabled = true;
                    logoutBtn.disabled = false;
                    errorMessage.style.display = "none";
                } else {
                    errorMessage.textContent = res.message;
                    errorMessage.style.display = "block";
                }
            } catch {
                errorMessage.textContent = "Erreur serveur.";
            }
            
        }
    });

    // Gestion du logout
    logoutBtn.addEventListener("click", () => {
        sessionStorage.removeItem("isLoggedIn");
        adminInput.readOnly = false;
        adminInput.textContent = "";
        passwordInput.style.display = "inline-block";
        passwordInput.textContent = "";
        loginBtn.disabled = false;
        logoutBtn.disabled = true;
        addVolcanBtn.style.display = "none";
        isLoggedIn = false;
        afficherVolcans();
    });

    // Chargement initial
    await chargerPaysEtVolcans();
});
