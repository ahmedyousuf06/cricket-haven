import axios from 'axios';

const apiClient = axios.create({
    baseURL: '/api/v1',
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
});

export function setAuthToken(token) {
    if (token) {
        apiClient.defaults.headers.common.Authorization = `Bearer ${token}`;
        return;
    }

    delete apiClient.defaults.headers.common.Authorization;
}

export function getErrorMessage(error, fallback = 'Something went wrong. Please try again.') {
    return error?.response?.data?.message || fallback;
}

export async function fetchProducts(params = {}) {
    const response = await apiClient.get('/products', { params });
    return response.data;
}

export async function fetchProductById(productId) {
    const response = await apiClient.get(`/products/${productId}`);
    return response.data;
}

export async function fetchCategories(params = {}) {
    const response = await apiClient.get('/categories', { params });
    return response.data;
}

export async function fetchCart() {
    const response = await apiClient.get('/cart');
    return response.data;
}

export async function addCartItem(payload) {
    const response = await apiClient.post('/cart/items', payload);
    return response.data;
}

export async function updateCartItem(itemId, payload) {
    const response = await apiClient.put(`/cart/items/${itemId}`, payload);
    return response.data;
}

export async function removeCartItem(itemId) {
    const response = await apiClient.delete(`/cart/items/${itemId}`);
    return response.data;
}

export async function clearCart() {
    const response = await apiClient.delete('/cart');
    return response.data;
}

export async function createOrder(payload) {
    const response = await apiClient.post('/orders', payload);
    return response.data;
}

export async function fetchOrders(params = {}) {
    const response = await apiClient.get('/orders', { params });
    return response.data;
}

export async function fetchOrderById(orderId) {
    const response = await apiClient.get(`/orders/${orderId}`);
    return response.data;
}

export async function login(payload) {
    const response = await apiClient.post('/auth/login', payload);
    return response.data;
}

export async function register(payload) {
    const response = await apiClient.post('/auth/register', payload);
    return response.data;
}

export async function fetchCurrentUser() {
    const response = await apiClient.get('/auth/user');
    return response.data;
}

export async function logout() {
    const response = await apiClient.post('/auth/logout');
    return response.data;
}

export default apiClient;
