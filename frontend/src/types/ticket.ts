import type {Company} from "./company.ts";
import type {User} from "./user.ts";
import type {Site} from "./site.ts";

export interface TicketStatus {
    value: string
    label: string
    is_finalised: boolean
}

export interface TicketPriority {
    value: string
    label: string
}

export interface Ticket {
    id: number
    subject: string
    created_by_user: User
    assigned_user: User | null
    status: TicketStatus
    priority: TicketPriority
    company: Company
    site: Site | null
    created_at: string
    updated_at: string
}

export interface UpdateTicketFormData {
    status: string
    priority: string
    assigned_user_id: number
}

export interface PostTicketUpdateFormData {
    text: string
}