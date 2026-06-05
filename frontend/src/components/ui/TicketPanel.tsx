import { formatDateTime } from '../../utils/date'
import type { Ticket } from '../../types/ticket'
import {Clock} from "lucide-react";

export default function TicketPanel({ ticket }: { ticket: Ticket }) {
    return (
        <tr className="bg-base-100 shadow-sm hover:bg-base-200 transition-shadow cursor-pointer">
            <td className="py-2 border border-gray-200">
                <div className="flex flex-col gap-2">
                        <div className="font-medium text-lg">{ticket.subject}</div>
                        <div className="flex text-sm text-base-content/50 gap-x-4">
                            <span>#{ticket.id}</span>
                            <span className="flex items-center gap-x-1"><Clock size={12} />{formatDateTime(ticket.created_at)}</span>
                        </div>
                </div>
            </td>
            <td className="border border-gray-200 text-center">
                <div>
                    <span className={ticket.status.is_finalised ? "badge badge-success" : "badge badge-neutral"}>{ticket.status.label}</span>
                </div>
            </td>
            <td className="border border-gray-200">
                <div>
                    <span>{ticket.priority.label}</span>
                </div>
            </td>
            <td className="border border-gray-200">
                <div>
                    <div>{ticket.created_by_user?.name}</div>
                </div>
            </td>
            <td className="border border-gray-200">
                <div>
                    <span>{ticket.company.name}</span>
                </div>
            </td>
            <td className="border border-gray-200">
                <div>
                    <span>{ticket.assigned_user?.name}</span>
                </div>
            </td>
        </tr>
    )
}