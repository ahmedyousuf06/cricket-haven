import React, { useEffect, useState } from 'react';
import EmptyState from '../components/ui/EmptyState';
import ErrorState from '../components/ui/ErrorState';
import LoadingSkeleton from '../components/ui/LoadingSkeleton';
import ProductCard from '../components/product/ProductCard';
import { useCart } from '../contexts/CartContext';
import { fetchProducts, getErrorMessage } from '../services/api';

export default function HomePage() {
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const { addItem } = useCart();

    const loadProducts = async () => {
        setLoading(true);
        setError('');

        try {
            const payload = await fetchProducts({ per_page: 12 });
            setProducts(payload?.data ?? []);
        } catch (loadError) {
            setError(getErrorMessage(loadError, 'Unable to load products.'));
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        loadProducts();
    }, []);

    const handleAddToCart = async (product) => {
        const variantId = product?.variants?.[0]?.id;

        if (!variantId) {
            return;
        }

        await addItem(variantId, 1);
    };

    return (
        <section className="space-y-6">
            <header>
                <h1 className="text-4xl font-black tracking-tight text-slate-950">Cricket E-Commerce Frontend</h1>
                <p className="mt-2 text-slate-600">
                    React + Vite scaffold is ready. Upload your first PNG and the UI will be matched screen-by-screen.
                </p>
            </header>

            {loading ? <LoadingSkeleton rows={6} /> : null}
            {!loading && error ? <ErrorState message={error} onRetry={loadProducts} /> : null}

            {!loading && !error && products.length === 0 ? (
                <EmptyState title="No products yet" description="No products are currently available from the API." />
            ) : null}

            {!loading && !error && products.length > 0 ? (
                <div className="grid gap-5 lg:grid-cols-3 md:grid-cols-2">
                    {products.map((product) => (
                        <ProductCard key={product.id} product={product} onAddToCart={handleAddToCart} />
                    ))}
                </div>
            ) : null}
        </section>
    );
}
