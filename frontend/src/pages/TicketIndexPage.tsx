import { useQuery } from '@tanstack/react-query'
import { getTickets } from "../api/tickets.ts";
import TicketPanel from "../components/ui/TicketPanel.tsx";

export default function TicketIndexPage() {
    const { data: tickets, isLoading, isError } = useQuery({
        queryKey: ['tickets'],
        queryFn: getTickets,
    })

    if(isLoading) return <p>Loading...</p>
    if(isError) return <p>Something went wrong.</p>

    return (
        <table className="table border-collapse border-gray-400 px-4">
            <thead className="bg-gray-100">
                <tr>
                    <th className="border border-gray-300">Summary</th>
                    <th className="border border-gray-300">Status</th>
                    <th className="border border-gray-300">Priority</th>
                    <th className="border border-gray-300">Created</th>
                    <th className="border border-gray-300">Company</th>
                    <th className="border border-gray-300">Assigned User</th>
                </tr>
            </thead>
            <tbody>
            {tickets?.map(ticket => (
                <TicketPanel key={ticket.id} ticket={ticket} />
            ))}
            </tbody>
        </table>
    )
}