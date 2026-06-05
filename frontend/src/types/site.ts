import type {Company} from "./company.ts";
import type {Country} from "./country.ts";

export interface Site {
    id: number
    name: string
    company: Company
    is_default: boolean
    address_line1: string
    address_line2: string
    address_line3: string
    postcode: string
    city: string
    region: string
    country: Country
    created_at: string
    updated_at: string
}