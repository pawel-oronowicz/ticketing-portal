import client from './client'
import type {Company} from '../types/company'

export const getCompanies = async (): Promise<Company[]> => {
    const { data } = await client.get<Company[]>('/companies')
    return data
}