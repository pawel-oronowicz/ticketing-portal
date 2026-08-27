import client from './client'
import type {CreateTicketFormData, Ticket, UpdateTicketFormData} from '../types/ticket'

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

export const createTicket = async (formData: CreateTicketFormData): Promise<Ticket> => {
    const { data } = await client.post<Ticket>(`/tickets`, formData)
    return data
}