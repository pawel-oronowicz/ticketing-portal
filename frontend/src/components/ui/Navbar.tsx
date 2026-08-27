import { Link } from 'react-router';
import { useAuth } from "../../context/AuthContext.tsx";
import { ChevronDown, LogIn, LogOut, PanelRightClose, SquarePlus, User } from 'lucide-react'

export default function Navbar() {
    const { isLoggedIn, user, clearAuth } = useAuth()

    return (
        <nav data-theme="dracula" className="navbar bg-base-100 shadow-sm">
            <div className="flex-1">
                <label htmlFor="my-drawer-4" aria-label="open sidebar" className="btn btn-square btn-ghost">
                    <PanelRightClose className="my-1.5 inline-block size-4" />
                </label>
                <Link to="/" className="btn btn-ghost text-xl">Ticketing Portal</Link>
            </div>
            <div className="flex-none align-middle">
                <div className="dropdown dropdown-bottom dropdown-end mr-16">
                    <div tabIndex={0} role="button" className="btn m-1 btn-soft">
                        <SquarePlus size={20} /> New <ChevronDown size={20} />
                    </div>
                    <ul className="menu dropdown-content bg-base-100 rounded-box z-1 mt-3 w-32 p-2 shadow">
                        <li><Link to="/tickets/create">Ticket</Link></li>
                    </ul>
                </div>

                <div className="dropdown dropdown-bottom dropdown-end">
                    <div tabIndex={0} role="button" className="btn m-1 btn-ghost btn-circle">
                        <User />
                    </div>
                    <ul className="menu dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                        {isLoggedIn ? (
                            <>
                                <li className="px-1 my-2 text-base-content/70">Welcome, {user?.name}</li>
                                <li><button onClick={clearAuth}><LogOut size={16} />Log Out</button></li>
                            </>
                        ) :
                            <>
                                <li><Link to="/register"><SquarePlus size={16} />Register</Link></li>
                                <li><Link to="/login"><LogIn size={16} />Log In</Link></li>
                            </>
                        }
                    </ul>
                </div>
            </div>
        </nav>
    )
}