import React, { Component } from 'react';
import { Spinner, Card, Carousel, Button } from 'react-bootstrap';
import { withRouter, Redirect } from 'react-router-dom';
import { Layout, ProfilePreview } from '../components';
import PageContainer from '../containers/PageContainer';
import Axios from 'axios';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { FaHeart, faHeart } from '@fortawesome/free-solid-svg-icons';


const { REACT_APP_API_BASE_URL } = process.env;

const GENDERS = ['Femme', 'Homme'];

const Gallery = ({ galleryPictures }) =>
  <Carousel>
    {galleryPictures.map( (picture, index) =>
      <Carousel.Item key={index}>
        <img
          className="d-block w-100"
          src={picture.url}
          alt={`Photo de profil n°${index}`}
        />
        <Carousel.Caption>
          <p>Postée le {(new Date(picture.createdAt.date)).toLocaleString('fr-FR')}</p>
        </Carousel.Caption>
      </Carousel.Item>
    )}
  </Carousel>
;

class Profile extends Component
{
  state = {
    user: null,
    notFound: false,
    heartDisabled: true,
  }

  componentDidMount = async () => {
    const { match } = this.props;
    const id = match.params.id;

    try {
      const response = await Axios.get(
        `${REACT_APP_API_BASE_URL}/user/${id}`
      );
      
      this.setState({ user: response.data })
    }
    catch (error) {
      const match = error.message.match(/^Request failed with status code (\d+)$/);
      const statusCode = Number(match[1]);
      
      switch (statusCode) {
        case 404:
          this.setState({ notFound: true });
          break;
          
        default:
          return;
      }
    }

    const response = await Axios.get(
      `${REACT_APP_API_BASE_URL}/profile/heart/${id}`,
      {
        withCredentials: true,
      }
    )

    if (
      response.status === 204 ||
      response.status === 200 && (new Date() - new Date(response.data.createdAt.date)) >= 86400000
    ) {
      this.setState({ heartDisabled: false })
    }

    console.log(response);

    await Axios.post(
      `${REACT_APP_API_BASE_URL}/profile/visit/${id}`,
      null,
      {
        withCredentials: true,
      }
    );
  }

  sendHeart = async () => {
    const { match } = this.props;
    const id = match.params.id;

    try {
      await Axios.post(
        `${REACT_APP_API_BASE_URL}/profile/heart/${id}`,
        null,
        {
          withCredentials: true,
        }
      );

      this.setState({ heartDisabled: true });
    }
    catch (error) {
      console.error(error);
    }
  }

  render = () => {
    const { global } = this.props;

    const { user, notFound, heartDisabled } = this.state;

    if (notFound) {
      return <Redirect to="/notfound" />;
    }

    if (user === null) {
      return (
        <Layout global={global}>
          <Spinner animation="border" variant="info" />
        </Layout>
      );
    }

    return (
      <Layout global={global}>
        <div className="grid-1-3">
          <div>
            <ProfilePreview user={user} visited />
            <Button variant="outline-danger" onClick={this.sendHeart} disabled={heartDisabled}>
              <FontAwesomeIcon icon={faHeart} />
            </Button>
          </div>
          { user.galleryPictures.length === 0 ?
            <div>
              Cet utilisateur n'a pas encore posté de photo...
            </div>
            :
            <Gallery galleryPictures={user.galleryPictures} />
          }
        </div>
      </Layout>
    )
  }
}

export default () =>
  <PageContainer component={withRouter(Profile)} needAuth={true} />
;
