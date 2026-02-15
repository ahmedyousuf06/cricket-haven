import React from 'react';

export default function TestimonialsSection({ testimonials }) {
    return (
        <section className="pb-16 pt-6">
            <div className="mx-auto w-full max-w-[1240px] px-8">
                <h2 className="text-[52px] font-black tracking-[-0.02em] text-[#1660e8]">Trusted by Pros</h2>

                <div className="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {testimonials.map((testimonial) => (
                        <article key={testimonial.id} className="rounded-2xl border border-[#dde3ed] bg-white px-8 py-8">
                            <p className="min-h-[130px] text-[20px] leading-[1.55] text-[#5f6f88]">"{testimonial.quote}"</p>

                            <div className="mt-7 flex items-center gap-4">
                                <img src={testimonial.avatar} alt={testimonial.name} className="h-12 w-12 rounded-full object-cover" />
                                <div>
                                    <h3 className="text-[22px] font-bold text-[#1660e8]">{testimonial.name}</h3>
                                    <p className="text-[16px] text-[#7e8aa1]">{testimonial.role}</p>
                                </div>
                            </div>
                        </article>
                    ))}
                </div>
            </div>
        </section>
    );
}
