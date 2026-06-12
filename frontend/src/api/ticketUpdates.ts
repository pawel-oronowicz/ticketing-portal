import client from './client'
import type { TicketUpdate } from '../types/ticketUpdate'

export const getTicketUpdates = async (ticketId: number): Promise<TicketUpdate[]> => {
    const { data } = await client.get<TicketUpdate[]>(`/tickets/${ticketId}/updates`)
    return data
}