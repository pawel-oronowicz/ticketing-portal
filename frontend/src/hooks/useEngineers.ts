import { useQuery } from "@tanstack/react-query";
import { getEngineers } from "../api/users.ts";

export const useEngineers = () => {
    return useQuery({
        queryKey: ['engineers'],
        queryFn: getEngineers,
        staleTime: 5 * 60 * 1000,
    })
}