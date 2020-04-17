import React, { Component } from 'react';
import PageContainer from '../containers/PageContainer';
import { Layout, ProfilePreview } from '../components';
import { Form, Button, Spinner } from 'react-bootstrap';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faSearch } from '@fortawesome/free-solid-svg-icons';
import Axios from 'axios';
import { Range } from 'rc-slider';

const { REACT_APP_API_BASE_URL } = process.env;

class ProfileSearch extends Component
{
  state = {
    users: [],
    cities: null,
    formData: {
      gender: 0,
      city: null,
      age: [18, 60],
    },
    searching: false,
  }

  componentDidMount = async () => {
    const response = await Axios.get(
      `${REACT_APP_API_BASE_URL}/city`
    );

    this.setState({ cities: response.data });
  }

  handleFormChange = (propName) => (event) => {
    const { formData } = this.state;
    let newValue;
    if (event.target.value === '') {
      newValue = null
    } else {
      newValue = Number(event.target.value);
    }
    this.setState({ formData: {...formData, [propName]: newValue } })
  }

  handleAgeChange = (value) => {
    const { formData } = this.state;
    this.setState({ formData: {...formData, age: value } })
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
    const { formData, users, cities, searching } = this.state;

    if (cities === null) {
      return (
        <Layout global={global}>
          <Spinner animation="border" variant="info" />
        </Layout>
      );
    }

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
            <Form.Control as="select" onChange={this.handleFormChange('gender')} value={formData.gender}>
              <option value={0}>Femme</option>
              <option value={1}>Homme</option>
            </Form.Control>
          </Form.Group>
          <Form.Group controlId="city">
            <Form.Label>Ville</Form.Label>
            <Form.Control as="select" onChange={this.handleFormChange('city')} value={formData.city}>
              <option value={null}></option>
              {cities.map( (city, index) =>
                <option key={index} value={city.id}>{city.name}</option>
              )}
            </Form.Control>
          </Form.Group>
          <Form.Group>
            <Form.Label>Âge</Form.Label>
              <div>{formData.age[0]} - {formData.age[1]}</div>
            <Range
              onChange={this.handleAgeChange}
              value={formData.age}
              allowCross={false}
              min={18}
              max={60}
            />
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
