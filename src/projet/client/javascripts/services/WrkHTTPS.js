/*
 * Classe WrkHTTPS : Couche de service AJAX uniformisée.
 *
 * @author tercicer colin
 * @version 3.0 / 04.05.2025
 */

class WrkHTTPS {
    constructor(baseURL) {
        this.baseURL = baseURL;
    }

    // Méthode privée générique pour uniformiser les requêtes AJAX
    #request(method, action, data = {}, options = {}) {
        const ajaxOptions = {
            type: method,
            url: this.baseURL,
            dataType: options.dataType || 'json',
            xhrFields: {
                withCredentials: true //  indispensable
            },
            ...options.ajaxExtras // permet d'ajouter d'autres options si besoin
        };

        if (method === 'GET') {
            ajaxOptions.data = { action, ...data };
        } else if (options.json) {
            ajaxOptions.contentType = 'application/json';
            ajaxOptions.data = JSON.stringify({ action, ...data });
        } else {
            ajaxOptions.data = { action, ...data };
        }

        return $.ajax(ajaxOptions);
    }

    fetchVolcans(paysMap) {
        return this.#request('GET', 'Get_volcans')
            .then(response => response.map(volcan => ({
                id: volcan.pk_Volcan,
                nom: volcan.nom,
                altitude: volcan.altitude,
                latitude: volcan.latitude,
                longitude: volcan.longitude,
                pays: paysMap[volcan.pk_Pays] || "Inconnu"
            })));
    }

    fetchPays() {
        return this.#request('GET', 'Get_pays')
            .then(response => response.reduce((acc, pays) => {
                acc[pays.pk_Pays] = pays.nom;
                return acc;
            }, {}));
    }

    fetchVolcansFiltered(nom = '', pays = '') {
        return this.#request('GET', 'Get_volcans_filtered', { nom, pays });
    }

    addVolcan(data) {
        return this.#request('POST', 'Add_volcan', data, { json: true });
    }

    saveVolcan(data) {
        return this.#request('POST', 'Update_volcan', data, { json: true });
    }

    deleteVolcan(id) {
        return this.#request('DELETE', 'Delete_volcan', { id }, { json: true });
    }

    login(nom, pass) {
        return this.#request('POST', 'Post_checkLogin', { Nom: nom, Pass: pass }, { json: true });
    }
}
