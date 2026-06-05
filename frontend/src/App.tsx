import './App.css'

import {Routes, Route, Outlet} from 'react-router'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import ProtectedRoute from './components/ProtectedRoute'
import RegisterPage from './pages/auth/RegisterPage'
import LoginPage from './pages/auth/LoginPage'
import Layout from './components/ui/Layout.tsx'
import DashboardPage from "./pages/DashboardPage.tsx";
import CompanyIndexPage from "./pages/CompanyIndexPage.tsx";
import TicketIndexPage from "./pages/TicketIndexPage.tsx";

const queryClient = new QueryClient()

export default function App() {
    return (
        <QueryClientProvider client={queryClient}>
            <Routes>
                <Route path="/register" element={<RegisterPage />} />
                <Route path="/login" element={<LoginPage />} />
                <Route path="/" element={
                    <ProtectedRoute>
                        <Layout>
                            <Outlet />
                        </Layout>
                    </ProtectedRoute>
                }>
                    <Route path="/" element={<DashboardPage />} />
                    <Route path="/companies" element={<CompanyIndexPage />} />
                    <Route path="/tickets" element={<TicketIndexPage />} />
                </Route>
            </Routes>
        </QueryClientProvider>
    )
}
