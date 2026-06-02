import { useQuery } from '@tanstack/react-query'
import { getCompanies } from '../api/company'

export default function CompanyPage() {
    const { data: companies, isLoading, isError } = useQuery({
        queryKey: ['companies'],
        queryFn: getCompanies,
    })

    if(isLoading) return <p>Loading...</p>
    if(isError) return <p>Something went wrong.</p>

    return (
        <div className="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
            <table className="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                </tr>
                </thead>
                <tbody>
                {companies?.map(company => (
                    <tr key={company.id} className="hover:bg-base-200">
                        <td>{company.id}</td>
                        <td>{company.name}</td>
                    </tr>
                ))}

                </tbody>
            </table>
        </div>
    )
}