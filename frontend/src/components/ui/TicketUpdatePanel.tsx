import type { TicketUpdate } from "../../types/ticketUpdate.ts";

export default function TicketUpdatePanel({ update }: { update: TicketUpdate }) {
    return (
        <div>
            {update.text}
        </div>
    )
}