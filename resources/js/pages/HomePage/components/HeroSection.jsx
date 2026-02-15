import React from 'react';

export default function HeroSection({ hero }) {
    return (
        <section className="mx-auto w-full max-w-[1240px] px-8 pb-[74px] pt-[70px]">
            <div className="grid items-center gap-12 lg:grid-cols-[1.05fr_1fr]">
                <div>
                    <h1 className="max-w-[560px] text-[74px] font-black leading-[0.96] tracking-[-0.03em] text-[#1660e8]">
                        {hero.title}
                    </h1>
                    <p className="mt-8 max-w-[560px] text-[28px] leading-[1.42] text-[#5f6f88]">{hero.description}</p>

                    <div className="mt-12 flex flex-wrap gap-4">
                        <button
                            type="button"
                            className="min-w-[240px] rounded-xl bg-[#1660e8] px-8 py-5 text-[22px] font-semibold text-white"
                        >
                            {hero.primaryAction}
                        </button>
                        <button
                            type="button"
                            className="min-w-[240px] rounded-xl border border-[#d6dce6] bg-white px-8 py-5 text-[22px] font-semibold text-[#1660e8]"
                        >
                            {hero.secondaryAction}
                        </button>
                    </div>
                </div>

                <div className="rounded-xl bg-white p-2 shadow-[0_8px_30px_rgba(15,39,84,0.08)]">
                    <img
                        src={hero.image}
                        alt={hero.imageAlt}
                        className="h-[510px] w-full rounded-lg object-cover"
                    />
                </div>
            </div>
        </section>
    );
}
