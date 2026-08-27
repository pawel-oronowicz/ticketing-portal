import './App.css'

import {Routes, Route, Outlet} from 'react-router'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import ProtectedRoute from './components/ProtectedRoute'
import RegisterPage from './pages/auth/RegisterPage'
import LoginPage from './pages/auth/LoginPage'
import Layout from './components/ui/Layout.tsx'
import DashboardPage from "./pages/DashboardPage.tsx";
import CompanyIndexPage from "./pages/CompanyIndexPage.tsx";
import TicketCreatePage from "./pages/TicketCreatePage.tsx";
import TicketIndexPage from "./pages/TicketIndexPage.tsx";
import TicketViewPage from "./pages/TicketViewPage.tsx";

const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            retry: (failureCount, error: any) => {
                // Don't retry on 4xx errors
                if (error?.response?.status >= 400 && error?.response?.status < 500) {
                    return false
                }
                return failureCount < 3
            }
        }
    }
})

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
                    <Route path="/tickets/create" element={<TicketCreatePage />} />
                    <Route path="/tickets/:id" element={<TicketViewPage />} />
                </Route>
            </Routes>
        </QueryClientProvider>
    )
}
