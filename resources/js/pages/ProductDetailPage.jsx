import React, { useEffect, useMemo, useState } from 'react';
import { useParams } from 'react-router-dom';
import EmptyState from '../components/ui/EmptyState';
import ErrorState from '../components/ui/ErrorState';
import LoadingSkeleton from '../components/ui/LoadingSkeleton';
import { useCart } from '../contexts/CartContext';
import { fetchProductById, getErrorMessage } from '../services/api';

export default function ProductDetailPage() {
    const { productId } = useParams();
    const [product, setProduct] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const { addItem } = useCart();

    const activeVariant = useMemo(() => product?.variants?.[0] ?? null, [product]);

    const loadProduct = async () => {
        setLoading(true);
        setError('');

        try {
            const payload = await fetchProductById(productId);
            setProduct(payload?.data ?? null);
        } catch (loadError) {
            setError(getErrorMessage(loadError, 'Unable to load product details.'));
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        loadProduct();
    }, [productId]);

    const handleAddToCart = async () => {
        if (activeVariant?.id) {
            await addItem(activeVariant.id, 1);
        }
    };

    if (loading) {
        return <LoadingSkeleton rows={5} />;
    }

    if (error) {
        return <ErrorState message={error} onRetry={loadProduct} />;
    }

    if (!product) {
        return <EmptyState title="Product not found" description="No product exists for this route." />;
    }

    return (
        <article className="grid gap-8 lg:grid-cols-[2fr_1fr]">
            <section className="rounded-2xl border border-slate-200 bg-white p-6">
                <div className="aspect-[4/3] rounded-xl bg-gradient-to-br from-slate-100 to-slate-200" />
                <h1 className="mt-5 text-3xl font-black text-slate-950">{product.name}</h1>
                <p className="mt-2 text-slate-600">{product.description}</p>

                <dl className="mt-6 grid gap-3 text-sm lg:grid-cols-2">
                    <div>
                        <dt className="font-semibold text-slate-700">Brand</dt>
                        <dd className="text-slate-600">{product?.brand?.name ?? 'N/A'}</dd>
                    </div>
                    <div>
                        <dt className="font-semibold text-slate-700">Category</dt>
                        <dd className="text-slate-600">{product?.category?.name ?? 'N/A'}</dd>
                    </div>
                    <div>
                        <dt className="font-semibold text-slate-700">Vendor</dt>
                        <dd className="text-slate-600">{product?.vendor?.name ?? 'N/A'}</dd>
                    </div>
                    <div>
                        <dt className="font-semibold text-slate-700">SKU</dt>
                        <dd className="text-slate-600">{activeVariant?.sku ?? 'N/A'}</dd>
                    </div>
                </dl>
            </section>

            <aside className="rounded-2xl border border-slate-200 bg-white p-6">
                <p className="text-sm text-slate-600">Price</p>
                <p className="mt-1 text-4xl font-black text-slate-950">${activeVariant?.price ?? '0.00'}</p>
                <p className="mt-2 text-sm text-slate-600">Stock: {activeVariant?.stock ?? 0}</p>

                <button
                    type="button"
                    onClick={handleAddToCart}
                    disabled={!activeVariant?.id}
                    className="mt-6 w-full rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-700 disabled:cursor-not-allowed disabled:bg-slate-400"
                >
                    Add to Cart
                </button>
            </aside>
        </article>
    );
}
