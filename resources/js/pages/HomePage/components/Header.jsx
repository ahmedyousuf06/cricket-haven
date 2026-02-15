import React from 'react';

const navItems = ['Bats', 'Balls', 'Protection', 'Shoes', 'Sale'];

function IconSearch() {
    return (
        <svg viewBox="0 0 24 24" className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            <circle cx="11" cy="11" r="7" />
            <path d="m20 20-3.5-3.5" />
        </svg>
    );
}

function IconUser() {
    return (
        <svg viewBox="0 0 24 24" className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            <circle cx="12" cy="8" r="4" />
            <path d="M4 21c1.5-4 5-6 8-6s6.5 2 8 6" />
        </svg>
    );
}

function IconCart() {
    return (
        <svg viewBox="0 0 24 24" className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            <path d="M3 4h2l2 11h11l2-8H7" />
            <circle cx="10" cy="19" r="1.4" />
            <circle cx="17" cy="19" r="1.4" />
        </svg>
    );
}

function IconTrophy() {
    return (
        <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            <path d="M8 4h8v2a4 4 0 0 1-8 0V4Z" />
            <path d="M8 5H5a2 2 0 0 0 2 4h1" />
            <path d="M16 5h3a2 2 0 0 1-2 4h-1" />
            <path d="M12 10v4" />
            <path d="M9 20h6" />
            <path d="M10 14h4v6h-4z" />
        </svg>
    );
}

export default function Header({ cartCount = 2 }) {
    return (
        <header className="bg-[#f5f7fb]">
            <div className="mx-auto flex h-[68px] w-full max-w-[1240px] items-center justify-between px-8">
                <a href="/" className="flex items-center gap-2 text-[#0f63f5]">
                    <IconTrophy />
                    <span className="text-[30px] font-black tracking-tight">STRIKER</span>
                </a>

                <nav className="hidden items-center gap-9 text-[14px] font-semibold text-[#0f63f5] md:flex">
                    {navItems.map((item) => (
                        <a key={item} href="#" className={item === 'Sale' ? 'text-[#f0b400]' : ''}>
                            {item}
                        </a>
                    ))}
                </nav>

                <div className="flex items-center gap-4 text-[#0f63f5]">
                    <button type="button" aria-label="Search">
                        <IconSearch />
                    </button>
                    <button type="button" aria-label="Account">
                        <IconUser />
                    </button>
                    <button type="button" aria-label="Cart" className="relative">
                        <IconCart />
                        <span className="absolute -right-2 -top-2 inline-flex h-4 min-w-4 items-center justify-center rounded-full bg-[#f0b400] px-1 text-[10px] font-bold text-white">
                            {cartCount}
                        </span>
                    </button>
                </div>
            </div>
        </header>
    );
}
