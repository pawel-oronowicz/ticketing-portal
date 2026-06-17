import client from './client'
import type { TicketUpdate } from '../types/ticketUpdate'
import type {PostTicketUpdateFormData, Ticket} from "../types/ticket.ts";

export const getTicketUpdates = async (ticketId: number): Promise<TicketUpdate[]> => {
    const { data } = await client.get<TicketUpdate[]>(`/tickets/${ticketId}/updates`)
    return data
}

export const createTicketUpdate = async (ticketId: number, formData: PostTicketUpdateFormData): Promise<Ticket> => {
    const { data } = await client.post<Ticket>(`/tickets/${ticketId}/updates`, formData)
    return data
}