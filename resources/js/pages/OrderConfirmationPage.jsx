import React, { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import EmptyState from '../components/ui/EmptyState';
import ErrorState from '../components/ui/ErrorState';
import LoadingSkeleton from '../components/ui/LoadingSkeleton';
import { fetchOrderById, getErrorMessage } from '../services/api';

export default function OrderConfirmationPage() {
    const { orderId } = useParams();
    const [order, setOrder] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    const loadOrder = async () => {
        setLoading(true);
        setError('');

        try {
            const payload = await fetchOrderById(orderId);
            setOrder(payload?.data ?? null);
        } catch (loadError) {
            setError(getErrorMessage(loadError, 'Unable to load order confirmation.'));
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        loadOrder();
    }, [orderId]);

    if (loading) {
        return <LoadingSkeleton rows={4} />;
    }

    if (error) {
        return <ErrorState message={error} onRetry={loadOrder} />;
    }

    if (!order) {
        return <EmptyState title="Order not found" description="No order exists for this confirmation route." />;
    }

    return (
        <section className="rounded-2xl border border-emerald-200 bg-emerald-50 p-8">
            <h1 className="text-3xl font-black text-emerald-900">Order Confirmed</h1>
            <p className="mt-2 text-emerald-800">Thanks for your order. Your purchase is now in progress.</p>

            <div className="mt-6 grid gap-4 rounded-xl border border-emerald-200 bg-white p-5 md:grid-cols-3">
                <div>
                    <p className="text-xs uppercase tracking-wide text-slate-500">Order ID</p>
                    <p className="text-lg font-semibold text-slate-900">#{order.id}</p>
                </div>
                <div>
                    <p className="text-xs uppercase tracking-wide text-slate-500">Status</p>
                    <p className="text-lg font-semibold text-slate-900">{order.status}</p>
                </div>
                <div>
                    <p className="text-xs uppercase tracking-wide text-slate-500">Total</p>
                    <p className="text-lg font-semibold text-slate-900">${order.total}</p>
                </div>
            </div>

            <Link
                to="/"
                className="mt-6 inline-flex rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
            >
                Continue Shopping
            </Link>
        </section>
    );
}
