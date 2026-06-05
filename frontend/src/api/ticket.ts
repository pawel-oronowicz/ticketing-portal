import client from './client'
import type {Ticket} from '../types/ticket'

export const getTickets = async (): Promise<Ticket[]> => {
    const {data} = await client.get<Ticket[]>('/tickets')
    return data
}