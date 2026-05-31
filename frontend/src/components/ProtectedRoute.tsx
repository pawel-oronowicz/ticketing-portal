import { Navigate } from 'react-router'
import type {ReactNode} from "react";
import { useAuth } from '../context/AuthContext'

export default function ProtectedRoute({ children }: { children: ReactNode }) {
    const { isLoggedIn } = useAuth()

    if (!isLoggedIn) {
        return <Navigate to="/login" replace />
    }

    return <>{children}</>
}