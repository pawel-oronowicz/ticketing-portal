import { useQuery } from '@tanstack/react-query'
import { getTicketUpdates } from '../../api/ticketUpdates.ts'
import TicketUpdatePanel from "./TicketUpdatePanel.tsx";

export default function TicketMainBlock({ ticketId }: { ticketId: number }) {
    const { data: updates, isLoading } = useQuery({
        queryKey: ['ticketUpdates', ticketId],
        queryFn: () => getTicketUpdates(ticketId),
    })

    if (isLoading) return <p>Loading...</p>
    if (!updates) return null

    return (
        <div className="bg-white border border-gray-200 p-2 col-span-3">
            {updates.map(update => (
                <TicketUpdatePanel key={update.id} update={update} />
            ) )}
        </div>
    )
}