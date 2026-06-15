import { useQuery } from '@tanstack/react-query'
import { getTicketUpdates } from '../../api/ticketUpdates.ts'
import TicketUpdatePanel from "./TicketUpdatePanel.tsx";

export default function TicketMainBlock({ ticketId, ticketSubject }: { ticketId: number, ticketSubject: string }) {
    const { data: updates, isLoading } = useQuery({
        queryKey: ['ticketUpdates', ticketId],
        queryFn: () => getTicketUpdates(ticketId),
    })

    if (isLoading) return <p>Loading...</p>
    if (!updates) return null

    return (
        <div className="border border-gray-200 col-span-3">
            <span className="flex ml-4 py-2 gap-x-2">
                <span className="flex text-sm items-center text-base-content/60">#{ticketId}</span>
                <h2 className="py-4 text-xl font-semibold">{ticketSubject}</h2>
            </span>
            {updates.map(update => (
                <TicketUpdatePanel key={update.id} update={update} />
            ))}
        </div>
    )
}