import React from 'react';

export default function CategorySection({ categories }) {
    return (
        <section className="bg-[#e6ebf3] py-14">
            <div className="mx-auto w-full max-w-[1240px] px-8">
                <div className="mb-8 flex items-center justify-between">
                    <h2 className="text-[48px] font-black tracking-[-0.02em] text-[#1660e8]">Gear Up by Category</h2>
                    <a href="#" className="text-[16px] font-semibold text-[#1660e8]">
                        View all categories &rarr;
                    </a>
                </div>

                <div className="grid gap-5 md:grid-cols-3 lg:grid-cols-5">
                    {categories.map((category) => (
                        <article key={category.id} className="group relative overflow-hidden rounded-2xl bg-white">
                            <img src={category.image} alt={category.name} className="h-[290px] w-full object-cover" />
                            <div className="absolute inset-0 bg-gradient-to-t from-black/45 via-black/0 to-black/0" />
                            <h3 className="absolute bottom-4 left-4 text-[30px] font-bold text-white">{category.name}</h3>
                        </article>
                    ))}
                </div>
            </div>
        </section>
    );
}
