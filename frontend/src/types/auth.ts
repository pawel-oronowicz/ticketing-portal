export interface User {
    id: number
    name: string
    email: string
    role: 'admin' | 'engineer' | 'user'
}

export interface RegisterCredentials {
    name: string
    email: string
    password: string
    password_confirmation: string
}

export interface LoginCredentials {
    email: string
    password: string
}

export interface AuthResponse {
    token: string
    user: User
}