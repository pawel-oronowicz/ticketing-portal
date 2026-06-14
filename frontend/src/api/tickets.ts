import client from './client'
import type {Ticket, UpdateTicketFormData} from '../types/ticket'

export const getTickets = async (): Promise<Ticket[]> => {
    const { data } = await client.get<Ticket[]>('/tickets')
    return data
}

export const getTicket = async (id: number): Promise<Ticket> => {
    const { data } = await client.get<Ticket>(`/tickets/${id}`)
    return data
}

export const updateTicket = async (ticketId: number, formData: UpdateTicketFormData): Promise<Ticket> => {
    const { data } = await client.put<Ticket>(`/tickets/${ticketId}`, formData)
    return data
}