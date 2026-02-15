import React from 'react';
import { Link } from 'react-router-dom';

export default function ProductCard({ product, onAddToCart }) {
    const firstVariant = product?.variants?.[0];
    const price = firstVariant?.price ?? '0.00';

    return (
        <article className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div className="aspect-[4/3] bg-gradient-to-br from-slate-100 to-slate-200" aria-hidden="true" />
            <div className="space-y-4 p-5">
                <div>
                    <h3 className="text-lg font-semibold text-slate-900">{product.name}</h3>
                    <p className="mt-1 text-sm text-slate-600">{product?.category?.name ?? 'Uncategorized'}</p>
                </div>

                <div className="flex items-center justify-between gap-3">
                    <p className="text-lg font-bold text-slate-900">${price}</p>
                    <div className="flex gap-2">
                        <Link
                            to={`/products/${product.id}`}
                            className="rounded-md border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
                        >
                            View
                        </Link>
                        <button
                            type="button"
                            onClick={() => onAddToCart(product)}
                            className="rounded-md bg-slate-900 px-3 py-2 text-sm font-medium text-white hover:bg-slate-700"
                        >
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </article>
    );
}
