import React, {Component} from 'react';
import ApiService from '../../services/api-service';
import {ApiServiceProvider} from '../api-service-context';
import Header from '../header';

import {
    HomePage,
    SignupPage,
    LoginPage,
    ProfilePage
} from '../pages';

import {BrowserRouter as Router, Switch, Route, Redirect} from 'react-router-dom';

export default class App extends Component {

    state = {
        apiService: new ApiService(),
        clientToken: '',
        userToken: '',
        data: '',
        isLoggedIn: false,
        profile: ''
    };

    //чтение токена и state
    getToken = async (tokenKey) => {
        return this.state[tokenKey];
    };

    //получить новый токен
    getNewToken = async (tokenKey, form) => {
        let data = await this.state.apiService.getToken(form);
        if(!data.access_token.length) {
            return;
        }
        this.setToken(tokenKey, data.access_token);
        return data.access_token;
    };

    //сохранить токен в state
    setToken = (key, value) => {
        this.setState(() => {
            let data = {};
            data[key] = value;
            return data;
        });
    };
    onError = (error, tokenKey) => {
        let err = JSON.parse(error.message);

        //сброс токена при просрочке
        if(err.hint == "Access token is invalid") {
            this.setToken(tokenKey, '')
        }

        console.log(err);
        if(err.message) {
            alert(err.message);
        }
    };


    //процесс отправки формы
    submitForm = (event, callback) => {
        event.preventDefault();
        event.persist();
        let form = event.target;
        let $this = this;

        //получение ключа типа токена
        let grantType = this.state.apiService.getGrantType(form);
        let tokenKey = this.state.apiService.getTokenKey(grantType);

        this.getToken(tokenKey)

            //получение токена
            .then(function(token) {
                console.log('1. получение токена');
                return token || $this.getNewToken(tokenKey, form);
            })
            .catch(this.onError)

            //отправка формы
            .then(function(token) {
                console.log(token);
                console.log('2. отправка формы');
                return token
                    ? $this.state.apiService.postData(form, token)
                    : false;
            })
            .catch(err => this.onError(err, tokenKey))

            //обработка результата
            .then(function(data) {
                callback(data);
            });
    };

    submitSignUp = (event) => {
        let $this = this;
        return this.submitForm(event, function(data) {
            if(data=='OK') {
                alert('Успешная регистрация');
            } else {
                alert(data);
            }
        })
    };
    submitLogin = (event) => {
        let $this = this;
        this.submitForm(event, function(data) {
            if(data.profile) {
                $this.setState(() => {
                    return {profile: data.profile}
                });
            }
        })
    };
    logout = () => {
        this.setToken('userToken', '');
    };


    render() {

        const isLoggedIn = !!this.state.userToken;
        const {profile} = this.state;

        return (

                <ApiServiceProvider value={this.state.apiService}>
                    <Router>
                        <Header isLoggedIn={isLoggedIn} logout={this.logout} />
                        <Switch>
                            <Route
                                path="/signup/"
                                render={() => (
                                    <SignupPage submitForm={this.submitSignUp} />
                                )}/>
                            <Route
                                path="/login/"
                                render={() => (
                                    <LoginPage isLoggedIn={isLoggedIn} submitForm={this.submitLogin}/>
                                )}/>

                            <Route
                                path="/profile/"
                                render={() => (
                                    <ProfilePage isLoggedIn={isLoggedIn} data={profile}/>
                                )}/>

                            <Route path="/" component={HomePage}/>

                            <Route render={() => <h2>Not found</h2>}/>
                        </Switch>
                    </Router>
                </ApiServiceProvider>

        );
    }
}
