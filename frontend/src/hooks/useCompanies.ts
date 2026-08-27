import { useQuery } from "@tanstack/react-query";
import { getCompanies } from "../api/companies.ts";

export const useCompanies = () => {
    return useQuery({
        queryKey: ['companies'],
        queryFn: getCompanies,
    })
}