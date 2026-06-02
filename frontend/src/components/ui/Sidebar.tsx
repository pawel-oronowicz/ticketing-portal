import { Link } from 'react-router';
import { Building, FolderKanban, House, Users } from 'lucide-react'

export default function Sidebar() {
    return (
        <div className="drawer-side is-drawer-close:overflow-visible">
            <label htmlFor="my-drawer-4" aria-label="close sidebar" className="drawer-overlay"></label>
            <div data-theme="dracula" className="flex min-h-full flex-col items-start is-drawer-close:w-14 is-drawer-open:w-64">
                <ul className="menu w-full grow">
                    <li>
                        <Link to="/" className="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Homepage">
                            <House size={18} className="my-1.5 inline-block" />
                            <span className="is-drawer-close:hidden">Homepage</span>
                        </Link>
                    </li>

                    <li>
                        <Link to="/tickets" className="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Tickets">
                            <FolderKanban size={18} className="my-1.5 inline-block" />
                            <span className="is-drawer-close:hidden">Tickets</span>
                        </Link>
                    </li>

                    <li>
                        <Link to="/customers" className="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Customers">
                            <Users size={18} className="my-1.5 inline-block" />
                            <span className="is-drawer-close:hidden">Customers</span>
                        </Link>
                    </li>

                    <li>
                        <Link to="/companies" className="is-drawer-close:tooltip is-drawer-close:tooltip-right" data-tip="Companies">
                            <Building size={18} className="my-1.5 inline-block" />
                            <span className="is-drawer-close:hidden">Companies</span>
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    )
}