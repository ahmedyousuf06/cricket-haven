import React from 'react';

export default function ErrorState({ message, onRetry }) {
    return (
        <section className="rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-900" role="alert">
            <p className="font-medium">{message || 'An unexpected error occurred.'}</p>
            {onRetry ? (
                <button
                    type="button"
                    onClick={onRetry}
                    className="mt-3 rounded-md bg-rose-700 px-3 py-2 text-sm font-medium text-white"
                >
                    Retry
                </button>
            ) : null}
        </section>
    );
}
