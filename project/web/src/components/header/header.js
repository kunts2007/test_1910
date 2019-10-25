import React from 'react';
import {Link} from 'react-router-dom';

const Header = ({isLoggedIn, logout}) => {

    let classDefHidden = 'nav-item nav-link ';
    let classDefVisible = 'nav-item nav-link ';
    if (!isLoggedIn) {
        classDefHidden += ' d-none';
    } else {
        classDefVisible = ' d-none';
    }

    return (
        <div className="navbar navbar-expand-lg navbar-light bg-light">
            <ul className="navbar-nav">
                <li className="nav-item nav-link">
                    <Link to="/">Home</Link>
                </li>
                <li className="nav-item nav-link">
                    <Link to="/signup/">Signup</Link>
                </li>
                <li className={classDefVisible}>
                    <Link to="/login/">Login</Link>
                </li>
                <li className={classDefHidden}>
                    <Link to="/profile/">Profile</Link>
                </li>
                <li className={classDefHidden}>
                    <Link to="/logout/" onClick={logout}>Logout</Link>
                </li>
            </ul>
        </div>
    );
};

export default Header;