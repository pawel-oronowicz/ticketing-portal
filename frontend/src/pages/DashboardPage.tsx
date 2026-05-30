import { useForm } from 'react-hook-form'
import { useNavigate } from 'react-router'
import Navbar from '../components/ui/Navbar.tsx'

export default function DashboardPage() {
    return (
        <div>
            <Navbar />
            <p>You are now logged in!</p>
        </div>
    )
}
