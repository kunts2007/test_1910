import React from 'react';
import { Redirect } from 'react-router-dom';

const ProfilePage = ({ isLoggedIn, data }) => {

  if (isLoggedIn) {
    return (
      <div className="jumbotron text-center">
        <h3>Profile</h3>
        <div>Username: {data.username}</div>
        <div>Created: {data.created}</div>
      </div>
    );
  }

  return <Redirect to="/login/" />;

};

export default ProfilePage;
