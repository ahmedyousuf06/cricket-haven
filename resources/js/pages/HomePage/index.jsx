import React from 'react';
import CategorySection from './components/CategorySection';
import Footer from './components/Footer';
import Header from './components/Header';
import HeroSection from './components/HeroSection';
import TestimonialsSection from './components/TestimonialsSection';
import TrendingSection from './components/TrendingSection';

const hero = {
    title: 'Dominate Every Innings.',
    description:
        'Experience the power of Grade 1+ English Willow. Used by international professionals, crafted for your game.',
    primaryAction: 'Shop Collection',
    secondaryAction: 'Browse Categories',
    image: 'https://images.unsplash.com/photo-1624880357913-a8539238245b?auto=format&fit=crop&w=1200&q=80',
    imageAlt: 'Cricketer playing a powerful shot',
};

const categories = [
    { id: 1, name: 'Bats', image: 'https://images.unsplash.com/photo-1593341646782-e0b495cff86d?auto=format&fit=crop&w=450&q=80' },
    { id: 2, name: 'Gloves', image: 'https://images.unsplash.com/photo-1624526267942-ab0ff8a3e972?auto=format&fit=crop&w=450&q=80' },
    { id: 3, name: 'Helmets', image: 'https://images.unsplash.com/photo-1644632927142-a2489f2ccdd4?auto=format&fit=crop&w=450&q=80' },
    { id: 4, name: 'Pads', image: 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=450&q=80' },
    { id: 5, name: 'Footwear', image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=450&q=80' },
];

const trendingProducts = [
    { id: 1, name: 'GM Diamond 808', rating: 142, price: '$349.00', badge: 'NEW', image: 'https://images.unsplash.com/photo-1612874742237-6526221588e3?auto=format&fit=crop&w=700&q=80' },
    { id: 2, name: 'Masuri Vision Series', rating: 129, price: '$189.00', image: 'https://images.unsplash.com/photo-1614632537190-23e4146777db?auto=format&fit=crop&w=700&q=80' },
    { id: 3, name: 'Kookaburra Kahuna Pro', rating: 145, price: '$85.00', oldPrice: '$100.00', badge: '-15%', image: 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?auto=format&fit=crop&w=700&q=80' },
    { id: 4, name: 'Adidas Vector 22', rating: 98, price: '$145.00', image: 'https://images.unsplash.com/photo-1543508282-6319a3e2621f?auto=format&fit=crop&w=700&q=80' },
];

const testimonials = [
    {
        id: 1,
        quote: 'The quality of the English Willow is unmatched. Shipping was fast and the bat arrived knocked-in and ready to play.',
        name: 'Arjun Patel',
        role: 'Club Captain',
        avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80',
    },
    {
        id: 2,
        quote: 'Excellent customer service. They helped me size my pads perfectly. The gear feels premium and durable.',
        name: 'David Miller',
        role: 'Semi-Pro Player',
        avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
    },
    {
        id: 3,
        quote: 'Best selection of helmets in the market. I feel safe and confident at the crease now. Highly recommended.',
        name: 'Sarah Khan',
        role: 'Opening Bat',
        avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80',
    },
];

export default function HomePage() {
    // Replace these static constants with API payloads from /api/v1 once frontend integration begins.
    return (
        <div className="bg-[#f2f5fa] text-[#162238]">
            <Header />
            <HeroSection hero={hero} />
            <CategorySection categories={categories} />
            <TrendingSection products={trendingProducts} />
            <TestimonialsSection testimonials={testimonials} />
            <Footer />
        </div>
    );
}
