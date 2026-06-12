import client from './client'
import type {User} from '../types/user.ts'

export const getEngineers = async (): Promise<User[]> => {
    const { data } = await client.get<User[]>('/users?role=engineer')
    return data
}