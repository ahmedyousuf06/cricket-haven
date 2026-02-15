import React from 'react';

export default function Footer() {
    return (
        <footer className="bg-[#1660e8] pt-14 text-white">
            <div className="mx-auto w-full max-w-[1240px] px-8 pb-10">
                <div className="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <h3 className="text-[38px] font-black tracking-tight">STRIKER</h3>
                        <p className="mt-5 max-w-[300px] text-[20px] leading-[1.6] text-[#d6e3ff]">
                            Equipping the next generation of cricketing legends with world-class gear since 2015.
                        </p>
                    </div>

                    <div>
                        <h4 className="text-[26px] font-bold">Shop</h4>
                        <ul className="mt-5 space-y-3 text-[20px] text-[#d6e3ff]">
                            <li>Cricket Bats</li>
                            <li>Batting Gloves</li>
                            <li>Protection</li>
                            <li>Teamwear</li>
                            <li>Accessories</li>
                        </ul>
                    </div>

                    <div>
                        <h4 className="text-[26px] font-bold">Support</h4>
                        <ul className="mt-5 space-y-3 text-[20px] text-[#d6e3ff]">
                            <li>Order Status</li>
                            <li>Shipping &amp; Returns</li>
                            <li>Size Guides</li>
                            <li>Warranty</li>
                            <li>Contact Us</li>
                        </ul>
                    </div>

                    <div>
                        <h4 className="text-[26px] font-bold">Stay Updated</h4>
                        <p className="mt-5 text-[20px] text-[#d6e3ff]">Subscribe for exclusive drops and tips.</p>
                        <form className="mt-4 flex rounded-lg bg-white/20 p-1">
                            <input
                                type="email"
                                placeholder="email@example.com"
                                className="h-12 flex-1 bg-transparent px-3 text-[16px] text-white placeholder:text-[#d6e3ff] focus:outline-none"
                            />
                            <button type="submit" className="rounded-md bg-white px-5 text-[16px] font-semibold text-[#1660e8]">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>

                <div className="mt-12 border-t border-white/20 pt-7 text-[14px] text-[#d6e3ff] md:flex md:items-center md:justify-between">
                    <p>© 2024 Striker Cricket Store. All rights reserved.</p>
                    <div className="mt-3 flex gap-6 md:mt-0">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                    </div>
                </div>
            </div>
        </footer>
    );
}
