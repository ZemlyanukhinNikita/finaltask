
import React, { Component } from 'react';

const User = ({user}) => {

    const divStyle = {

    };

    if(!user) {
        return(<div style={divStyle}>  User Doesnt exist </div>);
    }

    return(
        <div style={divStyle}>
            <h3> Balance : {user.balance} </h3>
            <h3> Name : {user.first_name} </h3>
            <h3> Last Name : {user.last_name} </h3>

        </div>
    )
};
export default User;
