import { useQuery } from "@tanstack/react-query";
import { getSystemSettings } from "../api/systemSettings.ts";

export const useSystemSettings = () => {
    return useQuery({
        queryKey: ['systemSettings'],
        queryFn: getSystemSettings,
        staleTime: 1000 * 60 * 60,
    })
}
