import { createContext, useContext, useState, type ReactNode } from 'react'

interface User {
    id: number
    name: string
    email: string
}

interface AuthContextType {
    user: User | null
    token: string | null
    setAuth: (user: User, token: string) => void
    clearAuth: () => void
    isLoggedIn: boolean
}

const AuthContext = createContext<AuthContextType | null>(null)

export function AuthProvider({ children }: { children: ReactNode }) {
    const [token, setToken] = useState<string | null>(
        localStorage.getItem('token')
    )
    const [user, setUser] = useState<User | null>(() => {
        const stored = localStorage.getItem('user')
        return stored ? JSON.parse(stored) : null
    })

    const setAuth = (user: User, token: string) => {
        localStorage.setItem('token', token)
        localStorage.setItem('user', JSON.stringify(user))
        setToken(token)
        setUser(user)
    }

    const clearAuth = () => {
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        setToken(null)
        setUser(null)
    }

    return (
        <AuthContext.Provider value={{
            user,
            token,
            setAuth,
            clearAuth,
            isLoggedIn: !!token,
        }}>
            {children}
        </AuthContext.Provider>
    )
}

export function useAuth() {
    const context = useContext(AuthContext)
    if (!context) throw new Error('useAuth must be used within AuthProvider')
    return context
}