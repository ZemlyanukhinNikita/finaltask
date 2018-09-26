import React, {Component} from 'react';
import axios from "axios";
import {Redirect, BrowserRouter as Router, Link, Route} from 'react-router-dom';
import User from './User.js';
export default class Users extends Component {

    constructor(props) {
        super(props);
        this.state = { users: [] };
    }

    componentDidMount() {
        axios.get('api/users')
            .then(res => {
                const users = res.data;
                this.setState({ users });
            });
    }

    renderUsers() {
        const { users } = this.state;
        return users.map(user => <li key={user.id}>
                <Link to={'users/'+user.id}> {user.first_name}</Link>
                <Route
                    path={'users/'+user.id}
                    render={(props) => <User {...props} extra={user.id} />}
                />
        </li>)
    }

    render() {
        return (
            <Router>
            < ul> {this.renderUsers()}</ul>
            </Router>
        );
    }
}

