import { useQuery } from '@tanstack/react-query'
import { getTicket } from "../api/tickets.ts";
import { useParams } from "react-router";
import { useEngineers } from "../hooks/useEngineers.ts"
import TicketRightSidebar from "../components/ui/TicketRightSidebar.tsx";
import TicketMainBlock from "../components/ui/TicketMainBlock.tsx";

export default function TicketViewPage() {
    const { id } = useParams<{ id: string }>()
    const { data: engineers } = useEngineers()

    const { data: ticket, isLoading, isError, error } = useQuery({
        queryKey: ['ticket', id],
        queryFn: () => getTicket(Number(id)),
    })

    if (isLoading) return <p>Loading...</p>
    if (isError) {
        const status = (error as any)?.response?.status
        if (status === 403 || status === 404) return <p>Ticket not found.</p>
        return <p>Something went wrong.</p>
    }
    if (!ticket) return null

    return (
        <div className="grid grid-cols-4 gap-4">
            <TicketMainBlock ticket={ticket} />
            <TicketRightSidebar ticket={ticket} engineers={engineers} />
        </div>
    )
}