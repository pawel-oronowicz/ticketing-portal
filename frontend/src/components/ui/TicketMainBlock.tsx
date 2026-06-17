import { useQuery } from '@tanstack/react-query'
import {getTicketUpdates, createTicketUpdate} from '../../api/ticketUpdates.ts'
import TicketUpdatePanel from "./TicketUpdatePanel.tsx";
import {useForm} from "react-hook-form";
import type {PostTicketUpdateFormData, Ticket} from "../../types/ticket.ts";
import {useState} from "react";

export default function TicketMainBlock({ ticket }: { ticket: Ticket }) {
    const { data: updates, isLoading } = useQuery({
        queryKey: ['ticketUpdates', ticket.id],
        queryFn: () => getTicketUpdates(ticket.id),
    })
    const [errorMessage, setErrorMessage] = useState<string | null>(null)
    const [successMessage, setSuccessMessage] = useState<string | null>(null)

    const {
        register,
        handleSubmit,
        formState: { isSubmitting },
    } = useForm<PostTicketUpdateFormData>()

    const onSubmit = async (data: PostTicketUpdateFormData) => {
        setErrorMessage(null)
        setSuccessMessage(null)
        try {
            await createTicketUpdate(ticket.id, data)
            setSuccessMessage('Response successfully posted')
        } catch (error) {
            setErrorMessage('Something went wrong. Please try again')
        }
    }

    if (isLoading) return <p>Loading...</p>
    if (!updates) return null

    return (
        <div className="border border-gray-200 col-span-3">
            <span className="flex ml-4 py-2 gap-x-2">
                <span className="flex text-sm items-center text-base-content/60">#{ticket.id}</span>
                <h2 className="py-4 text-xl font-semibold">{ticket.subject}</h2>
            </span>

            {updates.map(update => (
                <TicketUpdatePanel key={update.id} update={update} />
            ))}

            <form onSubmit={handleSubmit(onSubmit)}>
                <div className="py-8 px-4 w-full">
                    <textarea {...register('text')} className="textarea w-full h-48" placeholder="Type your response here..."></textarea>
                </div>

                {errorMessage && (
                    <div className="alert alert-error mx-4 mb-4">
                        {errorMessage}
                    </div>
                )}

                {successMessage && (
                    <div className="alert alert-success mx-4 mb-4">
                        {successMessage}
                    </div>
                )}

                <div className="ml-4 mb-4">
                    <button type="submit" disabled={isSubmitting} className="btn btn-success w-24">
                        {isSubmitting ? 'Submitting...' : 'Submit'}
                    </button>
                </div>
            </form>
        </div>
    )
}