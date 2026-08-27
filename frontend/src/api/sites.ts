import type {Site} from "../types/site.ts";
import client from "./client.ts";

export const getSitesByCompany = async (companyId: number): Promise<Site[]> => {
    const { data } = await client.get<Site[]>(`/companies/${companyId}/sites`)
    return data
}