import React from 'react';

export default function TrendingSection({ products }) {
    return (
        <section className="py-20">
            <div className="mx-auto w-full max-w-[1240px] px-8">
                <div className="text-center">
                    <h2 className="text-[54px] font-black tracking-[-0.02em] text-[#1660e8]">Trending Gear</h2>
                    <p className="mt-3 text-[22px] text-[#6a7890]">Top-rated equipment chosen by our community</p>
                </div>

                <div className="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    {products.map((product) => (
                        <article key={product.id} className="overflow-hidden rounded-2xl border border-[#d8dfeb] bg-white">
                            <div className="relative h-[290px] bg-[#f2f5fa] p-4">
                                {product.badge ? (
                                    <span className="absolute left-4 top-4 rounded bg-[#1660e8] px-2 py-1 text-[11px] font-bold text-white">
                                        {product.badge}
                                    </span>
                                ) : null}
                                <img src={product.image} alt={product.name} className="h-full w-full object-contain" />
                            </div>

                            <div className="p-5">
                                <p className="text-[14px] text-[#8b97ab]">({product.rating})</p>
                                <h3 className="mt-2 text-[28px] font-bold leading-[1.1] text-[#1660e8]">{product.name}</h3>
                                <p className="mt-2 text-[34px] font-black text-[#1660e8]">
                                    {product.price}
                                    {product.oldPrice ? (
                                        <span className="ml-2 text-[24px] font-medium text-[#8a95a8] line-through">{product.oldPrice}</span>
                                    ) : null}
                                </p>
                                <button
                                    type="button"
                                    className="mt-5 w-full rounded-lg bg-[#1660e8] px-4 py-3 text-[18px] font-semibold text-white"
                                >
                                    Add to Cart
                                </button>
                            </div>
                        </article>
                    ))}
                </div>
            </div>
        </section>
    );
}
