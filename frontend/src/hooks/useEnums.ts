import { useQuery } from "@tanstack/react-query";
import { getEnums } from "../api/enums.ts";

export const useEnums = () => {
    return useQuery({
        queryKey: ['enums'],
        queryFn: getEnums,
        staleTime: Infinity,
    })
}