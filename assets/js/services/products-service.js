import axios from "axios";

/**
 * @param {string|null} categoryIri
 * @param {string|null} searchTerm
 * @returns {Promise}
 */
export function fetchProducts(categoryIri, searchTerm) {
    const params = {
        "enabled": "true",
        "category.enabled": "true"
    };
    if (categoryIri) {
        params.category = categoryIri;
    }
    if (searchTerm) {
        params.name = searchTerm;
    }
    return axios.get('/api/products', {
        params: params,
    });
}