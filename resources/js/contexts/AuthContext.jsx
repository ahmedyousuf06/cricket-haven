import React, { createContext, useContext, useEffect, useMemo, useState } from 'react';
import {
    fetchCurrentUser,
    getErrorMessage,
    login as loginRequest,
    logout as logoutRequest,
    register as registerRequest,
    setAuthToken,
} from '../services/api';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
    const [token, setToken] = useState(() => localStorage.getItem('auth_token'));
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(Boolean(token));

    useEffect(() => {
        setAuthToken(token);

        if (!token) {
            setUser(null);
            setLoading(false);
            return;
        }

        let mounted = true;

        fetchCurrentUser()
            .then((payload) => {
                if (mounted) {
                    setUser(payload.user ?? null);
                }
            })
            .catch(() => {
                if (mounted) {
                    localStorage.removeItem('auth_token');
                    setToken(null);
                    setUser(null);
                }
            })
            .finally(() => {
                if (mounted) {
                    setLoading(false);
                }
            });

        return () => {
            mounted = false;
        };
    }, [token]);

    const login = async (credentials) => {
        const payload = await loginRequest(credentials);
        const nextToken = payload.access_token;

        localStorage.setItem('auth_token', nextToken);
        setToken(nextToken);
        setUser(payload.user ?? null);

        return payload;
    };

    const register = async (registrationData) => {
        const payload = await registerRequest(registrationData);
        const nextToken = payload.access_token;

        localStorage.setItem('auth_token', nextToken);
        setToken(nextToken);
        setUser(payload.user ?? null);

        return payload;
    };

    const logout = async () => {
        try {
            await logoutRequest();
        } catch {
            // Token can be already invalidated server-side.
        }

        localStorage.removeItem('auth_token');
        setToken(null);
        setUser(null);
        setAuthToken(null);
    };

    const value = useMemo(
        () => ({
            user,
            token,
            loading,
            isAuthenticated: Boolean(token && user),
            isAdmin: user?.role === 'admin',
            isBuyer: user?.role === 'buyer',
            login,
            register,
            logout,
            getErrorMessage,
        }),
        [loading, token, user]
    );

    return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuth() {
    const context = useContext(AuthContext);

    if (!context) {
        throw new Error('useAuth must be used inside AuthProvider');
    }

    return context;
}
