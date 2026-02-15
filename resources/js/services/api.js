import axios from 'axios';

const apiClient = axios.create({
    baseURL: '/api/v1',
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
});

// Home API placeholders for future dynamic integration.
export async function fetchHomeProducts(params = {}) {
    const response = await apiClient.get('/products', { params });
    return response.data;
}

export async function fetchHomeCategories(params = {}) {
    const response = await apiClient.get('/categories', { params });
    return response.data;
}

export default apiClient;
