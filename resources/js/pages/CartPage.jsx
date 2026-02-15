import React from 'react';
import { Link } from 'react-router-dom';
import EmptyState from '../components/ui/EmptyState';
import ErrorState from '../components/ui/ErrorState';
import LoadingSkeleton from '../components/ui/LoadingSkeleton';
import { useCart } from '../contexts/CartContext';

export default function CartPage() {
    const { items, subtotal, loading, error, updateItemQty, removeItem, clear } = useCart();

    if (loading && items.length === 0) {
        return <LoadingSkeleton rows={4} />;
    }

    return (
        <section className="space-y-6">
            <header className="flex items-center justify-between">
                <h1 className="text-3xl font-black text-slate-950">Your Cart</h1>
                {items.length > 0 ? (
                    <button
                        type="button"
                        className="rounded-md border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700"
                        onClick={clear}
                    >
                        Clear Cart
                    </button>
                ) : null}
            </header>

            {error ? <ErrorState message={error} /> : null}

            {items.length === 0 ? (
                <EmptyState
                    title="Cart is empty"
                    description="Add products before proceeding to checkout."
                    action={
                        <Link to="/" className="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white">
                            Continue Shopping
                        </Link>
                    }
                />
            ) : (
                <div className="grid gap-6 lg:grid-cols-[2fr_1fr]">
                    <section className="space-y-4">
                        {items.map((item) => (
                            <article
                                key={item.id}
                                className="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-slate-200 bg-white p-4"
                            >
                                <div>
                                    <h2 className="text-lg font-semibold text-slate-900">
                                        {item?.product_variant?.product?.name ?? item?.product_name_snapshot ?? 'Product'}
                                    </h2>
                                    <p className="text-sm text-slate-600">SKU: {item?.product_variant?.sku ?? item?.sku_snapshot}</p>
                                </div>

                                <div className="flex items-center gap-2">
                                    <label htmlFor={`qty-${item.id}`} className="text-sm text-slate-600">
                                        Qty
                                    </label>
                                    <input
                                        id={`qty-${item.id}`}
                                        type="number"
                                        min="1"
                                        value={item.qty}
                                        onChange={(event) => updateItemQty(item.id, Number(event.target.value))}
                                        className="w-20 rounded-md border border-slate-300 px-2 py-1"
                                    />
                                </div>

                                <div className="text-right">
                                    <p className="text-lg font-bold text-slate-900">${item.unit_price}</p>
                                    <button
                                        type="button"
                                        onClick={() => removeItem(item.id)}
                                        className="mt-1 text-sm font-medium text-rose-700"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </article>
                        ))}
                    </section>

                    <aside className="h-fit rounded-xl border border-slate-200 bg-white p-5">
                        <h2 className="text-lg font-semibold text-slate-900">Summary</h2>
                        <div className="mt-3 flex items-center justify-between text-sm text-slate-600">
                            <span>Subtotal</span>
                            <span className="text-base font-bold text-slate-900">${subtotal.toFixed(2)}</span>
                        </div>
                        <Link
                            to="/checkout"
                            className="mt-5 block rounded-md bg-slate-900 px-4 py-3 text-center text-sm font-semibold text-white"
                        >
                            Proceed to Checkout
                        </Link>
                    </aside>
                </div>
            )}
        </section>
    );
}
