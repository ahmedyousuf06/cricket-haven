import React, { createContext, useContext, useEffect, useMemo, useState } from 'react';
import {
    addCartItem,
    clearCart,
    fetchCart,
    getErrorMessage,
    removeCartItem,
    updateCartItem,
} from '../services/api';
import { useAuth } from './AuthContext';

const CartContext = createContext(null);

export function CartProvider({ children }) {
    const { isAuthenticated } = useAuth();
    const [cart, setCart] = useState(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');

    const refreshCart = async () => {
        if (!isAuthenticated) {
            setCart(null);
            setError('');
            return null;
        }

        setLoading(true);
        setError('');

        try {
            const payload = await fetchCart();
            const nextCart = payload.data ?? null;
            setCart(nextCart);
            return nextCart;
        } catch (fetchError) {
            setError(getErrorMessage(fetchError, 'Failed to load cart.'));
            return null;
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        refreshCart();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [isAuthenticated]);

    const addItem = async (productVariantId, qty = 1, bundleId = null) => {
        setLoading(true);
        setError('');

        try {
            const payload = await addCartItem({
                product_variant_id: productVariantId,
                qty,
                bundle_id: bundleId,
            });
            setCart(payload.data ?? null);
            return payload;
        } catch (addError) {
            const message = getErrorMessage(addError, 'Failed to add item to cart.');
            setError(message);
            throw new Error(message);
        } finally {
            setLoading(false);
        }
    };

    const updateItemQty = async (itemId, qty) => {
        setLoading(true);
        setError('');

        try {
            await updateCartItem(itemId, { qty });
            await refreshCart();
        } catch (updateError) {
            const message = getErrorMessage(updateError, 'Failed to update cart item.');
            setError(message);
            throw new Error(message);
        } finally {
            setLoading(false);
        }
    };

    const removeItem = async (itemId) => {
        setLoading(true);
        setError('');

        try {
            await removeCartItem(itemId);
            await refreshCart();
        } catch (removeError) {
            const message = getErrorMessage(removeError, 'Failed to remove cart item.');
            setError(message);
            throw new Error(message);
        } finally {
            setLoading(false);
        }
    };

    const clear = async () => {
        setLoading(true);
        setError('');

        try {
            await clearCart();
            setCart((previous) => (previous ? { ...previous, items: [] } : null));
        } catch (clearError) {
            const message = getErrorMessage(clearError, 'Failed to clear cart.');
            setError(message);
            throw new Error(message);
        } finally {
            setLoading(false);
        }
    };

    const items = cart?.items ?? [];
    const itemCount = items.reduce((total, item) => total + Number(item.qty || 0), 0);
    const subtotal = items.reduce((total, item) => total + Number(item.unit_price || 0) * Number(item.qty || 0), 0);

    const value = useMemo(
        () => ({
            cart,
            items,
            itemCount,
            subtotal,
            loading,
            error,
            refreshCart,
            addItem,
            updateItemQty,
            removeItem,
            clear,
        }),
        [cart, error, itemCount, items, loading, subtotal]
    );

    return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
}

export function useCart() {
    const context = useContext(CartContext);

    if (!context) {
        throw new Error('useCart must be used inside CartProvider');
    }

    return context;
}
