import './App.css'

import { Routes, Route } from 'react-router'
import ProtectedRoute from './components/ProtectedRoute'
import RegisterPage from './pages/auth/RegisterPage'
import LoginPage from './pages/auth/LoginPage'
import DashboardPage from "./pages/DashboardPage.tsx";

export default function App() {
    return (
        <Routes>
            <Route path="/register" element={<RegisterPage />} />
            <Route path="/login" element={<LoginPage />} />
            <Route path="/" element={
                <ProtectedRoute>
                    <DashboardPage />
                </ProtectedRoute>
            } />
        </Routes>
    )
}
