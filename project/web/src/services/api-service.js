
export default class ApiService {

    _api = {
        url: 'http://test.local/',
        form: {
            grant_type: 'client_credentials',
            client_id: 'myawesomeapp',
            client_secret: 'abc123'
        },
        header: {
            post: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        },
        tokenKeyByGrantType: {
            'client_credentials': 'clientToken',
            'password': 'userToken'
        },
        grantTypeByFormScope: {
            'default': 'client_credentials',
            'user': 'password'
        }
    };

    getTokenHeader = (token) => {
        return {Authorization: 'Bearer ' + token};
    };

    _getFormScope = (form) => {
        return form.getAttribute('data-form-scope');
    };
    getFormScope = (form) => {
        return form.getAttribute('data-form-scope');
    };
    getFormAction = (form) => {
        return form.getAttribute('action');
    };
    getFormMethod = (form) => {
        return form.getAttribute('method') || 'POST';
    };
    getGrantType = (form) => {
        let scope = this.getFormScope(form);
        return this._api.grantTypeByFormScope[scope] || this._api.grantTypeByFormScope.default;
    };
    getTokenKey = (grantType) => {
        return this._api.tokenKeyByGrantType[grantType] || '';
    };

    _serialize = (obj) => {
        var pairs = [];
        for (var prop in obj) {
            pairs.push(prop + '=' + obj[prop]);
        }
        return pairs.join('&');
    };

    sendPost = async (action, headers, data, method = 'POST') => {
        let url = this._api.url + action;
        let fetchOptions = {
            method: method,
            cache: 'no-cache',
            credentials: 'include',
            headers: headers,
            body: method == 'GET' ? null : this._serialize(data)
        };
        let response = await fetch(url, fetchOptions);

        if (!response.ok) {
            //throw new Error(`Fetch error ${response.status}`)
            let errorBody = await response.text().then(body => {
                return body;
            });
            throw new Error(errorBody);
        }

        return response.json();
    };

    getToken = async (form = '') => {
        let action = 'access_token';
        let headers = this._api.header.post;
        let data = this._api.form;
        if(form) {
            data.scope = this.getFormScope(form);
            data.grant_type = this.getGrantType(form);
            data = Object.assign({}, data, Object.fromEntries(new FormData(form).entries()));
        }
        return await this.sendPost(action, headers, data);
    };

    postData = async (form, token) => {
        let action = this.getFormAction(form);
        let headers = Object.assign({},
            this._api.header.post,
            this.getTokenHeader(token)
        );
        let data = Object.fromEntries(new FormData(form).entries());
        let method = this.getFormMethod(form);

        return await this.sendPost(action, headers, data, method);
    };
}