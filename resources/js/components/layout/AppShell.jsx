import React from 'react';
import SiteHeader from './SiteHeader';

export default function AppShell({ children }) {
    return (
        <div className="min-h-screen bg-slate-50 text-slate-900">
            <SiteHeader />
            <main className="mx-auto w-full max-w-7xl px-6 py-8">{children}</main>
        </div>
    );
}
