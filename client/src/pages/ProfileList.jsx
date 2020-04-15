import React, { Component } from 'react';
import Axios from 'axios';
import { Layout, ProfilePreview } from '../components';
import PageContainer from '../containers/PageContainer';
import { Button } from 'react-bootstrap';

const { REACT_APP_API_BASE_URL } = process.env;

class ProfileList extends Component
{
  state = {
    users: null,
    visits: [],
  }

  componentDidMount = async () => {
    this.refresh();
  }

  refresh = async () => {
    const { global } = this.props;

    let response;
    
    response = await Axios.get(
      `${REACT_APP_API_BASE_URL}/user/`
    );

    this.setState({ users: response.data });

    response = await Axios.get(
      `${REACT_APP_API_BASE_URL}/profile/visits`,
      {
        headers: {
          'X-AUTH-TOKEN': global.currentUser.apiToken,
        }
      },
    );

    this.setState({ visits: response.data.map(visit => visit.visitedId) });
  }
  
  render = () => {
    const { global } = this.props;
    const { users, visits } = this.state;

    if (users === null) {
      return <div>Loading...</div>
    }

    return (
      <Layout global={global}>
        <Button onClick={this.refresh}>
          Recharger
        </Button>
        <ul className="grid-4">
          {users.map( (user, index) =>
            <li key={index}>
              <ProfilePreview user={user} visited={visits.includes(user.id)} />
            </li>
          )}
        </ul>
      </Layout>
    );
  }
}

export default () =>
  <PageContainer component={ProfileList} />
;
