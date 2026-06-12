import type { Ticket } from "./ticket.ts";
import type { User } from "./user.ts";

export interface TicketUpdate {
    id: number
    ticket: Ticket
    text: string
    is_internal: boolean
    created_by_user: User | null
    created_at: string
    updated_at: string
}