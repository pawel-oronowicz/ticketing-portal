import type {ReactNode} from 'react'
import Navbar from "./Navbar.tsx";
import Sidebar from "./Sidebar.tsx";

interface Props {
    children: ReactNode
}

export default function Layout({ children }: Props) {
    return (
        <div className="drawer lg:drawer-open">
            <input id="my-drawer-4" type="checkbox" className="drawer-toggle" />
            <div className="drawer-content">
                <Navbar />

                {/* Page content injected here */}
                <div className="p-4">
                    {children}
                </div>
            </div>

            <Sidebar />
        </div>
    )
}