import React from 'react';

export default function LoadingSkeleton({ rows = 3 }) {
    return (
        <div className="space-y-3" aria-live="polite" aria-busy="true">
            {Array.from({ length: rows }).map((_, index) => (
                <div
                    key={`skeleton-${index}`}
                    className="h-16 animate-pulse rounded-xl border border-slate-200 bg-slate-100"
                />
            ))}
        </div>
    );
}
