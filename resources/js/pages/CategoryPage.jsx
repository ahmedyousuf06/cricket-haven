import React, { useEffect, useMemo, useState } from 'react';
import { useParams } from 'react-router-dom';
import ProductCard from '../components/product/ProductCard';
import EmptyState from '../components/ui/EmptyState';
import ErrorState from '../components/ui/ErrorState';
import LoadingSkeleton from '../components/ui/LoadingSkeleton';
import { useCart } from '../contexts/CartContext';
import { fetchCategories, fetchProducts, getErrorMessage } from '../services/api';

export default function CategoryPage() {
    const { slug } = useParams();
    const [products, setProducts] = useState([]);
    const [categories, setCategories] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const { addItem } = useCart();

    const activeCategory = useMemo(() => {
        if (slug === 'all') {
            return null;
        }

        return categories.find((category) => category.slug === slug) ?? null;
    }, [categories, slug]);

    const loadPageData = async () => {
        setLoading(true);
        setError('');

        try {
            const [categoryPayload, productPayload] = await Promise.all([
                fetchCategories({ per_page: 100 }),
                fetchProducts({ per_page: 100 }),
            ]);
            const nextCategories = categoryPayload?.data ?? [];
            setCategories(nextCategories);

            const allProducts = productPayload?.data ?? [];
            const matchCategory = nextCategories.find((category) => category.slug === slug);

            if (!slug || slug === 'all' || !matchCategory) {
                setProducts(allProducts);
            } else {
                setProducts(allProducts.filter((product) => product.category?.id === matchCategory.id));
            }
        } catch (loadError) {
            setError(getErrorMessage(loadError, 'Unable to load category products.'));
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        loadPageData();
    }, [slug]);

    const handleAddToCart = async (product) => {
        const variantId = product?.variants?.[0]?.id;

        if (variantId) {
            await addItem(variantId, 1);
        }
    };

    return (
        <section className="space-y-6">
            <header>
                <h1 className="text-3xl font-black text-slate-950">{activeCategory?.name ?? 'All Categories'}</h1>
                <p className="mt-1 text-slate-600">Browse category products and add them to cart.</p>
            </header>

            {loading ? <LoadingSkeleton rows={6} /> : null}
            {!loading && error ? <ErrorState message={error} onRetry={loadPageData} /> : null}

            {!loading && !error && products.length === 0 ? (
                <EmptyState
                    title="No products found"
                    description="This category does not contain any products yet."
                />
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
