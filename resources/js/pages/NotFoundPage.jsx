import React from 'react';
import { Link } from 'react-router-dom';

export default function NotFoundPage() {
    return (
        <section className="rounded-2xl border border-slate-200 bg-white p-8 text-center">
            <p className="text-sm font-semibold uppercase tracking-wider text-slate-500">404</p>
            <h1 className="mt-2 text-3xl font-black text-slate-950">Page not found</h1>
            <p className="mt-2 text-slate-600">This route does not exist in the current frontend map.</p>
            <Link to="/" className="mt-5 inline-flex rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white">
                Back to Home
            </Link>
        </section>
    );
}
