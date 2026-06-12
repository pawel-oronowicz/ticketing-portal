import { formatDateTime } from '../../utils/date'
import type { Ticket } from '../../types/ticket'
import { useEnums } from "../../hooks/useEnums.ts";
import type {User} from "../../types/user.ts";

interface Props {
    ticket: Ticket
    engineers: User[] | undefined
}

export default function TicketRightSidebar({ticket, engineers}: Props) {
    const { data: enums, isLoading: enumsLoading } = useEnums()

    if (enumsLoading) return <p>Loading...</p>

    const ticketStatuses = enums?.ticket_statuses
    const ticketPriorities = enums?.ticket_priorities

    return (
        <div className="bg-white border border-gray-200 text-sm p-2">
            <div className="grid gap-y-2">
                <div
                    className={ticket.status.is_finalised ?
                        "badge badge-soft badge-success text-xl font-bold p-4" :
                        "badge badge-soft badge-info text-xl font-bold p-4"}>
                    {ticket.status.label}
                </div>

                <div className="gap-x-1">
                    <span className="fieldset-legend text-xs">Created</span>
                    <span>{formatDateTime(ticket.created_at)}</span>
                </div>

                <div className="gap-x-1">
                    <span className="fieldset-legend text-xs">Created by</span>
                    <span>{ticket.created_by_user?.name}</span>
                </div>

                <div className="gap-x-1">
                    <span className="fieldset-legend text-xs">Company</span>
                    <span>{ticket.company?.name}</span>
                </div>

                <div className="gap-x-1">
                    <fieldset className="fieldset">
                        <legend className="fieldset-legend">Assigned User</legend>
                        <select id="assignedUser" name="assignedUser" defaultValue={ticket.assigned_user?.id} className="select">
                            {engineers?.map(engineer => (
                                <option key={engineer.id} value={engineer.id}>{engineer.name}</option>
                            ))}
                        </select>
                    </fieldset>
                </div>

                <div>
                    <fieldset className="fieldset">
                        <legend className="fieldset-legend">Status</legend>
                        <select id="ticketStatus" name="ticketStatus" defaultValue={ticket.status.value} className="select">
                            {ticketStatuses.map((item: { value: number, label: string }) => (
                                <option key={item.value} value={item.value}>{item.label}</option>
                            ))}
                        </select>
                    </fieldset>
                </div>

                <div>
                    <fieldset className="fieldset">
                        <legend className="fieldset-legend">Priority</legend>
                        <select id="ticketPriority" name="ticketPriority" defaultValue={ticket.priority.value} className="select">
                            {ticketPriorities.map((item: { value: number, label: string }) => (
                                <option key={item.value} value={item.value}>{item.label}</option>
                            ))}
                        </select>
                    </fieldset>
                </div>
            </div>
        </div>
    )
}