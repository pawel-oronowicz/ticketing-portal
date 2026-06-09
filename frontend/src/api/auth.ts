import client from './client'
import type {AuthResponse, LoginCredentials, RegisterCredentials} from '../types/auth'

export const login = async (credentials: LoginCredentials): Promise<AuthResponse> => {
    const { data } = await client.post<AuthResponse>('/login', credentials)
    return data
}

export const signup = async (credentials: RegisterCredentials): Promise<AuthResponse> => {
    const { data } = await client.post<AuthResponse>('/register', credentials)
    return data
}
