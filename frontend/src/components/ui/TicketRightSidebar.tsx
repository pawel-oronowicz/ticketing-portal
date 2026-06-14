import {formatDateTime} from '../../utils/date'
import type {Ticket, UpdateTicketFormData} from '../../types/ticket'
import {useEnums} from "../../hooks/useEnums.ts";
import type {User} from "../../types/user.ts";
import {useState} from "react";
import {useForm} from "react-hook-form";
import {updateTicket} from "../../api/tickets.ts";
import {useAuth} from "../../context/AuthContext.tsx";

interface Props {
    ticket: Ticket
    engineers: User[] | undefined
}

export default function TicketRightSidebar({ticket, engineers}: Props) {
    const { data: enums, isLoading: enumsLoading } = useEnums()
    const { isInternalUser } = useAuth()
    const [errorMessage, setErrorMessage] = useState<string | null>(null)
    const [successMessage, setSuccessMessage] = useState<string | null>(null)

    const {
        register,
        handleSubmit,
        formState: { isSubmitting },
    } = useForm<UpdateTicketFormData>({
        defaultValues: {
            status: ticket.status.value,
            priority: ticket.priority.value,
            assigned_user_id: ticket.assigned_user?.id
        }
    })

    const onSubmit = async (data: UpdateTicketFormData) => {
        setErrorMessage(null)
        setSuccessMessage(null)
        try {
            const response = await updateTicket(ticket.id, data)
            setSuccessMessage('Ticket successfully updated')
        } catch (error) {
            setErrorMessage('Something went wrong. Please try again')
        }
    }

    if (enumsLoading) return <p>Loading...</p>

    const ticketStatuses = enums?.ticket_statuses
    const ticketPriorities = enums?.ticket_priorities

    return (
        <div className="bg-white border border-gray-200 text-sm p-2">
            <div className="grid">
                <form onSubmit={handleSubmit(onSubmit)}>
                    <div
                        className={ticket.status.is_finalised ?
                            "badge badge-soft badge-success text-xl font-semibold p-4" :
                            "badge badge-soft badge-info text-xl font-semibold p-4"}>
                        {ticket.status.label}
                    </div>

                    <div className="gap-x-1 mt-4">
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

                    <div>
                        {isInternalUser ? (
                            <fieldset className="fieldset">
                                <legend className="fieldset-legend">Assigned User</legend>
                                <select {...register('assigned_user_id')} className="select">
                                    <option value="">-- Unassigned --</option>
                                    {engineers?.map(engineer => (
                                        <option key={engineer.id} value={engineer.id}>{engineer.name}</option>
                                    ))}
                                </select>
                            </fieldset>
                            ) :
                            <>
                                <span className="fieldset-legend text-xs">Assigned User</span>
                                <span>{ticket.assigned_user ? ticket.assigned_user.name : '-'}</span>
                            </>
                        }
                    </div>

                    <div>
                        {isInternalUser ? (
                            <fieldset className="fieldset">
                                <legend className="fieldset-legend">Status</legend>
                                <select {...register('status')} className="select">
                                    {ticketStatuses.map((item: { value: number, label: string }) => (
                                        <option key={item.value} value={item.value}>{item.label}</option>
                                    ))}
                                </select>
                            </fieldset>
                            ) :
                            <>
                                <span className="fieldset-legend text-xs">Status</span>
                                <span>{ticket.status.label}</span>
                            </>
                        }
                    </div>

                    <div>
                        {isInternalUser ? (
                            <fieldset className="fieldset">
                                <legend className="fieldset-legend">Priority</legend>
                                <select {...register('priority')} className="select">
                                    {ticketPriorities.map((item: { value: number, label: string }) => (
                                        <option key={item.value} value={item.value}>{item.label}</option>
                                    ))}
                                </select>
                            </fieldset>
                            ) :
                            <>
                                <span className="fieldset-legend text-xs">Priority</span>
                                <span>{ticket.priority.label}</span>
                            </>
                        }
                    </div>

                    {errorMessage && (
                        <div className="alert alert-error mt-4">
                            {errorMessage}
                        </div>
                    )}

                    {successMessage && (
                        <div className="alert alert-success mt-4">
                            {successMessage}
                        </div>
                    )}

                    {isInternalUser ? (
                        <div className="mt-8">
                            <button type="submit" disabled={isSubmitting} className="btn btn-success w-36 h-12">
                                {isSubmitting ? 'Saving...' : 'Save'}
                            </button>
                        </div>
                        ) : ''
                    }
                </form>
            </div>
        </div>
    )
}