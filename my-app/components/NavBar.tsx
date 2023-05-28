import Link from 'next/link'
import React from 'react'

const NavBar = () => {
    return (
        <>
            <nav className="navbar navbar-expand-lg bg-dark text-light" style={{height:"70px"}}>
                <div className="container-fluid">
                    <Link className="navbar-brand" href="#">
                        <h6 className="text-muted display-6 text-center py-3">The APP</h6>
                    </Link>
                    <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span className="navbar-toggler-icon"></span>
                    </button>
                    <div className="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul className="navbar-nav me-auto mb-2 mb-lg-0 text-light">
                            <li className="nav-item">
                                <Link className="nav-link active" aria-current="page" href="/users/dashboard">For you</Link>
                            </li>
                            <li className="nav-item">
                                <Link className="nav-link" href="#">Global posts</Link>
                            </li>
                            <li className="nav-item">
                                <Link href='' className="nav-link">Search</Link>
                            </li>
                            <li className="nav-item dropdown">
                                <Link className="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Dropdown
                                </Link>
                                <ul className="dropdown-menu">
                                    <li><Link className="dropdown-item" href="">Home</Link></li>
                                    <li><Link className="dropdown-item" href="#">Search</Link></li>
                                    <li><hr className="dropdown-divider" /></li>
                                    <li><Link className="dropdown-item" href="#">Something else here</Link></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </>
    )
}

export default NavBar