import type {Company} from "./company.ts";
import type {User} from "./user.ts";
import type {Site} from "./site.ts";
import { z } from 'zod'

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
    is_internal: boolean
}

export const createTicketSchema = z.object({
    subject: z.string().trim().nonempty('Subject is required'),
    description: z.string().trim().nonempty('Description is required'),
    priority: z.string().nonempty('Priority is required'),
    company_id: z.coerce.number<number>().gt(0, { message: 'Company is required' }),
    site_id: z.coerce.number<number>().gt(0, { message: 'Site is required' }),
    assigned_user_id: z.string().nullable().transform(val => val === '' || val === null ? null : Number(val)),
})

export type CreateTicketFormData = z.output<typeof createTicketSchema>