import axios from 'axios'

let ajaxConfig = {
    addHeader(k, v) {
        this.headers[k] = v;
        return this.getHeaders();
    },
    getHeaders() {
        return {headers: this.headers};
    },
    headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    }
};
let apiUrl = baseUrl + '/api';

export function createAxiosClient() {
    return {client: axios, ajaxConfig, apiUrl};
}
