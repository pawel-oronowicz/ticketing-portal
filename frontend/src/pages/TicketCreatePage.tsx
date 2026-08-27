import { useQuery } from '@tanstack/react-query';
import { useNavigate } from 'react-router'
import { useAuth } from "../context/AuthContext.tsx";
import { useEnums } from "../hooks/useEnums.ts";
import { useEngineers } from "../hooks/useEngineers.ts"
import { useCompanies } from '../hooks/useCompanies.ts'
import { useForm } from "react-hook-form";
import { createTicket } from "../api/tickets.ts";
import { createTicketSchema, type CreateTicketFormData } from '../types/ticket.ts'
import { getSitesByCompany } from "../api/sites.ts";
import { useState } from "react";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";

type FormDataInput = z.input<typeof createTicketSchema>

export default function TicketCreatePage() {
    const navigate = useNavigate()

    const { data: enums, isLoading: enumsLoading } = useEnums()
    const { data: engineers, isLoading: engineersLoading } = useEngineers()
    const { data: companies, isLoading: companiesLoading } = useCompanies()

    const { isInternalUser } = useAuth()
    const [errorMessage, setErrorMessage] = useState<string | null>(null)
    const [successMessage, setSuccessMessage] = useState<string | null>(null)

    const {
        register,
        handleSubmit,
        watch,
        formState: { errors, isSubmitting },
    } = useForm<FormDataInput, any, CreateTicketFormData>({
        resolver: zodResolver(createTicketSchema),
    })

    const selectedCompany = watch('company_id')

    const { data: sites, isLoading: sitesLoading } = useQuery({
        queryKey: ['sites', selectedCompany],
        queryFn: () => getSitesByCompany(selectedCompany),
        enabled: !!selectedCompany
    })

    const onSubmit = async (data: CreateTicketFormData) => {
        setErrorMessage(null)
        setSuccessMessage(null)
        try {
            const ticket = await createTicket(data)
            setSuccessMessage('Ticket successfully created')
            navigate('/tickets/' + ticket.id)
        } catch (error) {
            setErrorMessage('Something went wrong. Please try again')
        }
    }

    const isLoading = enumsLoading || engineersLoading || companiesLoading

    if (isLoading) return <p>Loading...</p>

    const ticketPriorities = enums?.ticket_priorities

    return (
        <form onSubmit={handleSubmit(onSubmit)}>
            <div className="space-y-8 bg-white border border-gray-200 p-4">
                <h1 className="text-base/7 font-semibold text-gray-900">Create a new Ticket</h1>

                <div className="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div className="sm:col-span-4">
                        <label htmlFor="subject" className="block text-sm/6 font-medium text-gray-900">
                            Subject <span className="text-red-600">*</span>
                        </label>
                        <div className="mt-2">
                            <div className="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <input
                                    {...register('subject')}
                                    type="text"
                                    autoComplete="off"
                                    className="block min-w-0 grow bg-white py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6"
                                />
                            </div>
                        </div>
                        {errors.subject && <p className="text-red-500 text-sm">{errors.subject.message}</p>}
                    </div>

                    <div className="col-span-full">
                        <label htmlFor="description" className="block text-sm/6 font-medium text-gray-900">
                            Description <span className="text-red-600">*</span>
                        </label>
                        <textarea
                            {...register('description')}
                            rows={3}
                            className="mt-2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                            defaultValue={''}
                        />
                        {errors.description && <p className="text-red-500 text-sm">{errors.description.message}</p>}
                        <p className="mt-3 text-sm/6 text-gray-600">Describe the issue in as much detail as possible.</p>
                    </div>

                    {isInternalUser ? (
                        <div className="col-span-full">
                            <label htmlFor="company_id" className="block text-sm/6 font-medium text-gray-900">
                                Company <span className="text-red-600">*</span>
                            </label>
                            <select {...register('company_id')} className="select mt-2">
                                <option key="0" value="">-- Select a Company --</option>
                                {companies?.map(company => (
                                    <option key={company.id} value={company.id}>{company.name}</option>
                                ))}
                            </select>
                            {errors.company_id && <p className="text-red-500 text-sm">{errors.company_id.message}</p>}
                        </div>
                        ) :
                        <>
                        </>
                    }

                    {isInternalUser && (
                        <div className="col-span-full">
                            <label htmlFor="site_id" className="block text-sm/6 font-medium text-gray-900">
                                Site <span className="text-red-600">*</span>
                            </label>
                            { sitesLoading ? (
                                <p className="mt-2 text-sm text-gray-500">Sites loading...</p>
                            ) : (
                                <>
                                    <select {...register('site_id')} className="select mt-2" disabled={!selectedCompany}>
                                        <option key="0" value="">-- Select a Site --</option>
                                        {sites?.map(site => (
                                            <option key={site.id} value={site.id}>{site.name}</option>
                                        ))}
                                    </select>

                                    {errors.site_id && (<p className="text-red-500 text-sm">{errors.site_id.message}</p>)}
                                </>
                            )}
                        </div>
                    )}

                    {isInternalUser ? (
                        <div className="col-span-full">
                            <label htmlFor="priority" className="block text-sm/6 font-medium text-gray-900">
                                Priority <span className="text-red-600">*</span>
                            </label>
                            <select {...register('priority')} className="select mt-2">
                                <option key="0" value="">-- Select a Priority --</option>
                                {ticketPriorities.map((item: { value: number, label: string }) => (
                                    <option key={item.value} value={item.value}>{item.label}</option>
                                ))}
                            </select>
                            {errors.priority && <p className="text-red-500 text-sm">{errors.priority.message}</p>}
                        </div>
                        ) :
                        <>
                        </>
                    }

                    {isInternalUser ? (
                        <div className="col-span-full">
                            <label htmlFor="assigned_user_id" className="block text-sm/6 font-medium text-gray-900">
                                Assigned User
                            </label>
                            <div className="mt-2">
                                <select {...register('assigned_user_id')} className="select">
                                    <option key="0" value="">-- Unassigned --</option>
                                    {engineers?.map(engineer => (
                                        <option key={engineer.id} value={engineer.id}>{engineer.name}</option>
                                    ))}
                                </select>
                            </div>
                            {errors.assigned_user_id && <p className="text-red-500 text-sm">{errors.assigned_user_id.message}</p>}
                        </div>
                        ) :
                        <>
                        </>
                    }

                </div>
            </div>

            <div className="mt-6 flex items-center gap-x-6">
                <button type="submit" disabled={isSubmitting} className="btn btn-success w-32">
                    {isSubmitting ? 'Creating...' : 'Create Ticket'}
                </button>

                {errorMessage && (
                    <div className="alert alert-error">
                        {errorMessage}
                    </div>
                )}

                {successMessage && (
                    <div className="alert alert-success">
                        {successMessage}
                    </div>
                )}
            </div>
        </form>
    )
}