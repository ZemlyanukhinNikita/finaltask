import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import User from "./User";
import {Link,BrowserRouter as Router } from 'react-router-dom';
/* Main Component */
class Main extends Component {

    constructor() {

        super();
        //Initialize the state in the constructor
        this.state = {
            users: [],
            currentUser: null
        }
    }
    /*componentDidMount() is a lifecycle method
     * that gets called after the component is rendered
     */
    componentDidMount() {
        /* fetch API in action */
        fetch('/api/users')
            .then(response => {
                return response.json();
            })
            .then(users => {
                //Fetched product is stored in the state
                this.setState({ users });
            });
    }

    handleClick(user) {
        //handleClick is used to set the state
        this.setState({currentUser:user});

    }

    renderUsers() {
        return this.state.users.map(user => {
            return (
                //this.handleClick() method is invoked onClick.
                <li onClick={
                    () =>this.handleClick(user)} key={user.id} >
                    <Link to={"/users/" + user.id}>{ user.first_name }</Link>
                </li>
            );
        })
    }

    render() {
        return ( <div>
            <div>
                <h3> All Users </h3>
                <Router>
                <ul>
                    { this.renderUsers() }
                </ul>
                </Router>
            </div>

            <User user={this.state.currentUser} />
        </div>
    );
    }
}
if (document.getElementById('example')) {
    ReactDOM.render(<Main />, document.getElementById('example'));
}
