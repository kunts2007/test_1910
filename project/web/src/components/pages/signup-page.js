import React from 'react';


const SignupPage = ({submitForm}) => {

    const divStyle = {
        width: '400px'
    };

    return (
        <form onSubmit={submitForm} method="POST" action="oauth/signup" data-form-scope="signup">
            <h2>Sign Up</h2>
            <div className="form-group" style={divStyle}>
                <div>Логин:</div>
                <input className="form-control" name="username" />
                <div>Пароль:</div>
                <input className="form-control" name="password" />
                <button className="btn btn-primary">
                    Отправить
                </button>
            </div>
        </form>
    );
};

export default SignupPage;
