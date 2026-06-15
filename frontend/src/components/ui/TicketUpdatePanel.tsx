import type { TicketUpdate } from "../../types/ticketUpdate.ts";
import {Clock} from "lucide-react";
import {formatDateTime} from "../../utils/date.ts";

export default function TicketUpdatePanel({ update }: { update: TicketUpdate }) {
    return (
        <div className="card border-t border-gray-300 odd:bg-white even:bg-gray-50 w-full rounded-none">
            <div className="card-body text-base">
                <span className="flex items-center gap-x-4">
                    <span className="font-semibold">{update.created_by_user?.name}</span>
                    <span className="flex items-center gap-x-1 text-sm text-base-content/60"><Clock size={12} />{formatDateTime(update.created_at)}</span>
                </span>

                <p>{update.text}</p>
            </div>
        </div>
    )
}