import React from 'react';

export default function EmptyState({ title, description, action }) {
    return (
        <section className="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center">
            <h2 className="text-xl font-semibold text-slate-900">{title}</h2>
            <p className="mt-2 text-sm text-slate-600">{description}</p>
            {action ? <div className="mt-4">{action}</div> : null}
        </section>
    );
}
