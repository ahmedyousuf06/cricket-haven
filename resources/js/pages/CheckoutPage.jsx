import React, { useMemo, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import EmptyState from '../components/ui/EmptyState';
import ErrorState from '../components/ui/ErrorState';
import { useCart } from '../contexts/CartContext';
import { createOrder, getErrorMessage } from '../services/api';

const defaultForm = {
    fullName: '',
    email: '',
    address: '',
    city: '',
    zip: '',
};

export default function CheckoutPage() {
    const navigate = useNavigate();
    const { items, subtotal, clear } = useCart();
    const [formValues, setFormValues] = useState(defaultForm);
    const [errors, setErrors] = useState({});
    const [submitting, setSubmitting] = useState(false);
    const [submitError, setSubmitError] = useState('');

    const hasItems = items.length > 0;

    const orderItemsPayload = useMemo(
        () =>
            items.map((item) => ({
                product_variant_id: item.product_variant_id,
                qty: Number(item.qty),
            })),
        [items]
    );

    const validate = () => {
        const nextErrors = {};

        if (!formValues.fullName.trim()) nextErrors.fullName = 'Full name is required.';
        if (!formValues.email.trim()) nextErrors.email = 'Email is required.';
        if (!formValues.address.trim()) nextErrors.address = 'Address is required.';
        if (!formValues.city.trim()) nextErrors.city = 'City is required.';
        if (!formValues.zip.trim()) nextErrors.zip = 'ZIP code is required.';

        setErrors(nextErrors);
        return Object.keys(nextErrors).length === 0;
    };

    const handleChange = (event) => {
        const { name, value } = event.target;
        setFormValues((current) => ({ ...current, [name]: value }));
        setErrors((current) => ({ ...current, [name]: '' }));
    };

    const handleSubmit = async (event) => {
        event.preventDefault();

        if (!validate()) {
            return;
        }

        if (!hasItems) {
            setSubmitError('Your cart is empty.');
            return;
        }

        setSubmitting(true);
        setSubmitError('');

        try {
            const payload = await createOrder({ items: orderItemsPayload });
            const orderId = payload?.data?.id;

            await clear();
            navigate(`/order-confirmation/${orderId}`);
        } catch (error) {
            setSubmitError(getErrorMessage(error, 'Unable to create order.'));
        } finally {
            setSubmitting(false);
        }
    };

    if (!hasItems) {
        return (
            <EmptyState
                title="Nothing to checkout"
                description="Add products to cart before placing an order."
            />
        );
    }

    return (
        <section className="grid gap-6 lg:grid-cols-[2fr_1fr]">
            <form onSubmit={handleSubmit} className="space-y-4 rounded-2xl border border-slate-200 bg-white p-6">
                <h1 className="text-3xl font-black text-slate-950">Checkout</h1>
                <p className="text-sm text-slate-600">Complete your shipping details to place the order.</p>

                {submitError ? <ErrorState message={submitError} /> : null}

                {[
                    { name: 'fullName', label: 'Full Name' },
                    { name: 'email', label: 'Email', type: 'email' },
                    { name: 'address', label: 'Address' },
                    { name: 'city', label: 'City' },
                    { name: 'zip', label: 'ZIP Code' },
                ].map((field) => (
                    <div key={field.name}>
                        <label htmlFor={field.name} className="mb-1 block text-sm font-medium text-slate-700">
                            {field.label}
                        </label>
                        <input
                            id={field.name}
                            name={field.name}
                            type={field.type ?? 'text'}
                            value={formValues[field.name]}
                            onChange={handleChange}
                            className="w-full rounded-md border border-slate-300 px-3 py-2"
                        />
                        {errors[field.name] ? <p className="mt-1 text-sm text-rose-700">{errors[field.name]}</p> : null}
                    </div>
                ))}

                <button
                    type="submit"
                    disabled={submitting}
                    className="w-full rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white disabled:cursor-not-allowed disabled:bg-slate-400"
                >
                    {submitting ? 'Placing Order...' : 'Place Order'}
                </button>
            </form>

            <aside className="h-fit rounded-2xl border border-slate-200 bg-white p-6">
                <h2 className="text-lg font-semibold text-slate-900">Order Summary</h2>
                <ul className="mt-4 space-y-2 text-sm text-slate-700">
                    {items.map((item) => (
                        <li key={item.id} className="flex justify-between gap-3">
                            <span>{item?.product_variant?.product?.name ?? 'Product'} x {item.qty}</span>
                            <span>${(Number(item.unit_price) * Number(item.qty)).toFixed(2)}</span>
                        </li>
                    ))}
                </ul>
                <div className="mt-4 border-t border-slate-200 pt-3">
                    <div className="flex justify-between text-sm text-slate-600">
                        <span>Subtotal</span>
                        <span className="font-semibold text-slate-900">${subtotal.toFixed(2)}</span>
                    </div>
                </div>
            </aside>
        </section>
    );
}
