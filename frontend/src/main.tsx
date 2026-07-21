import { createRoot } from 'react-dom/client'
import './index.css'
import App from './App.tsx'
import { BrowserRouter } from 'react-router'
import { AuthProvider } from './context/AuthContext'

createRoot(document.getElementById('root')!).render(
    <BrowserRouter basename={import.meta.env.PROD ? '/ticketing-portal' : '/'}>
        <AuthProvider>
            <App />
        </AuthProvider>
    </BrowserRouter>
)