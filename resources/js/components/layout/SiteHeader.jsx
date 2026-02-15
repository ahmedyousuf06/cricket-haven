import React from 'react';
import { Link, NavLink } from 'react-router-dom';
import { useCart } from '../../contexts/CartContext';

const navItems = [
    { to: '/', label: 'Home' },
    { to: '/category/all', label: 'Category' },
    { to: '/checkout', label: 'Checkout' },
];

export default function SiteHeader() {
    const { itemCount } = useCart();

    return (
        <header className="border-b border-slate-200 bg-white">
            <div className="mx-auto flex w-full max-w-7xl items-center justify-between gap-6 px-6 py-4">
                <Link to="/" className="text-2xl font-bold tracking-tight text-slate-900">
                    Cricket Haven
                </Link>

                <nav className="flex items-center gap-5" aria-label="Primary">
                    {navItems.map((item) => (
                        <NavLink
                            key={item.to}
                            to={item.to}
                            className={({ isActive }) =>
                                `text-sm font-medium ${isActive ? 'text-slate-950' : 'text-slate-600 hover:text-slate-900'}`
                            }
                        >
                            {item.label}
                        </NavLink>
                    ))}
                    <NavLink
                        to="/cart"
                        className={({ isActive }) =>
                            `rounded-md px-3 py-2 text-sm font-semibold ${
                                isActive ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-800 hover:bg-slate-200'
                            }`
                        }
                    >
                        Cart ({itemCount})
                    </NavLink>
                </nav>
            </div>
        </header>
    );
}
