import React, { Component } from 'react';
import { Layout, ProfilePreview } from '../components';
import PageContainer from '../containers/PageContainer';
import Axios from 'axios';
import { Spinner } from 'react-bootstrap';

const { REACT_APP_API_BASE_URL } = process.env;

class Visitors extends Component {
  state = {
    users: null,
  }

  componentDidMount = async () => {
    const response = await Axios.get(
      `${REACT_APP_API_BASE_URL}/profile/visitors`,
      {
        withCredentials: true,
      }
    )

    this.setState({ users: response.data });
  }

  render = () => {
    const { global } = this.props;

    const { users } = this.state;

    if (users === null || global.currentUser.data === null) {
      return (
        <Layout global={global}>
          <Spinner animation="border" variant="info" />
        </Layout>
      );
    }

    const visits = global.currentUser.data.sentVisits.map(
      visit => visit.visitedId
    );

    return (
      <Layout global={global}>
        <h1>Dernières visites sur votre profil</h1>
        <ul className="grid-4">
          {users.map( (user, index) =>
            <li key={index}>
              <ProfilePreview user={user} visited={visits.includes(user.id)} showLink />
            </li>
          )}
        </ul>
      </Layout>
    );
  }
}

export default () =>
  <PageContainer component={Visitors} needAuth />
;
