import React from 'react';
import {Redirect} from 'react-router-dom';

const LoginPage = ({isLoggedIn, submitForm}) => {

    if (isLoggedIn) {
        return <Redirect to="/profile/"/>;
    }

    const divStyle = {
        width: '400px'
    };

    return (
        <form onSubmit={submitForm} method="GET" action="oauth/auth" data-form-scope="user">
            <h2>Login</h2>
            <div className="form-group" style={divStyle}>
                <div>Логин:</div>
                <input className="form-control" name="username" defaultValue='test3' />
                <div>Пароль:</div>
                <input className="form-control" name="password"  defaultValue='test3' />
                <button className="btn btn-primary">
                    Отправить
                </button>
            </div>
        </form>
    );
};

export default LoginPage;
