import React, { Component } from 'react';
import PageContainer from '../containers/PageContainer';
import { Layout, ProfilePreview } from '../components';
import { Form, Button, Spinner } from 'react-bootstrap';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faSearch } from '@fortawesome/free-solid-svg-icons';
import Axios from 'axios';

const { REACT_APP_API_BASE_URL } = process.env;

class ProfileSearch extends Component
{
  state = {
    users: [],
    formData: {
      gender: 0,
    },
    searching: false,
  }

  handleGenderChange = (event) => {
    const { formData } = this.state;
    this.setState({ formData: {...formData, gender: Number(event.target.value) } })
  }

  submitSearch = async (event) => {
    this.setState({ searching: true });

    const { formData } = this.state;

    event.preventDefault();
    
    const response = await Axios.post(
      `${REACT_APP_API_BASE_URL}/user/search`,
      formData,
      {
        withCredentials: true,
      }
    );

    this.setState({ users: response.data, searching: false });
  }

  render = () => {
    const { global } = this.props;
    const { formData, users, searching } = this.state;

    let visits = []
    if (global.currentUser.data !== null) {
      visits = global.currentUser.data.sentVisits.map(
        visit => visit.visitedId
      );
    }

    return (
      <Layout global={global}>
        <Form onSubmit={this.submitSearch}>
          <Form.Group controlId="gender">
            <Form.Label>Sexe</Form.Label>
            <Form.Control as="select" onChange={this.handleGenderChange} value={formData.gender}>
              <option value={0}>Femme</option>
              <option value={1}>Homme</option>
            </Form.Control>
          </Form.Group>
          <Button type="submit" disabled={searching}>
            <FontAwesomeIcon icon={faSearch} />
            {' '}Rechercher
          </Button>
        </Form>
        {searching ?
          <Spinner animation="border" variant="info" />
          :
          <ul className="grid-4">
            {users.map( (user, index) =>
              <li key={index}>
                <ProfilePreview user={user} visited={visits.includes(user.id)} />
              </li>
            )}
          </ul>
        }
      </Layout>
    );
  }
}

export default () =>
  <PageContainer component={ProfileSearch} />
;
