import { useQuery } from '@tanstack/react-query'
import { getTicket } from "../api/ticket.ts";
import {useParams} from "react-router";
import TicketRightSidebar from "../components/ui/TicketRightSidebar.tsx";

export default function TicketViewPage() {
    const { id } = useParams<{ id: string }>()

    const { data: ticket, isLoading, isError } = useQuery({
        queryKey: ['ticket', id],
        queryFn: () => getTicket(Number(id)),
    })

    if(isLoading) return <p>Loading...</p>
    if(isError) return <p>Something went wrong.</p>
    if (!ticket) return null

    return (
        <div>
            <TicketRightSidebar ticket={ticket} />
        </div>
    )
}