import React, { Component } from 'react';
import Axios from 'axios';
import { Redirect } from 'react-router-dom';

const { REACT_APP_API_BASE_URL } = process.env;

class PageContainer extends Component
{
  state = {
    currentUser: null,
    fetchingUser: true,
    messages: [],
  }

  componentDidMount = () => {
    console.log("Mounting PageContainer")

    this.refreshCurrentUser();
  }

  login = async (email, password) => {
    const { messages } = this.state;

    let message;
    let type;

    try {
      const response = await Axios.post(
        `${REACT_APP_API_BASE_URL}/login`,
        {
          email,
          password,
        },
        {
          withCredentials: true,
        }
      );

      await this.setState({ currentUser: response.data });

      message = "Vous êtes maintenant connecté";
      type = "success";
    }
    catch (error) {
      const match = error.message.match(/^Request failed with status code (\d+)$/);
      const statusCode = Number(match[1]);
      
      switch (statusCode) {
        case 404:
          message = "Nom d'utilisateur inconnu";
          type = "danger";
          break;

        case 401:
          message = "Mot de passe erroné";
          type = "danger";
          break;

        case 500:
          message = "Une erreur est survenue, merci de réessayer plus tard";
          type = "danger";
          break;

        default:
          message = "Argh, une erreur inconnue, on va tous mourir!";
          type = "danger";
      }
    }

    this.setState({ messages: [...messages, { message, type } ] })
  }

  logout = async () => {
    await Axios.get(
      `${REACT_APP_API_BASE_URL}/logout`,
      {
        withCredentials: true,
      }
    );

    this.setState({
      currentUser: null,
    });

    const { messages } = this.state;
    this.setState({ messages: [...messages, {
      message: 'Vous êtes maintenant déconnecté',
      type: 'info',
    }] });
  }

  refreshCurrentUser = async () => {
    try {
      const response = await Axios.get(
        `${REACT_APP_API_BASE_URL}/current-user`,
        {
          withCredentials: true,
        }
      )
      
      await this.setState({ currentUser: response.data });
    }
    catch (error) {
      const match = error.message.match(/^Request failed with status code (\d+)$/);
      const statusCode = Number(match[1]);
      
      switch (statusCode) {
        case 401:
          console.info('User disconnected');
          break;

        default:
          console.error('Error while refreshing user data');
      }
    }

    this.setState({ fetchingUser: false });
  }

  deleteMessage = (index) => () => {
    const { messages } = this.state;
    messages.splice(index, 1);
    this.setState({ messages });
  }

  render = () => {
    const { component, needAuth } = this.props;

    const { currentUser, fetchingUser, messages } = this.state;

    if (!fetchingUser) {
      if (needAuth && currentUser === null) {
        return <Redirect to="/" />;
      }
    }

    const ComponentName = component;

    const currentUserProps = {
      data: currentUser,
      fetching: fetchingUser,
      actions: {
        login: this.login,
        logout: this.logout,
        refreshCurrentUser: this.refreshCurrentUser,
      }
    }

    const messagesProps = {
      messages,
      actions: {
        delete: this.deleteMessage,
      }
    }

    return (
      <ComponentName global={{ currentUser: currentUserProps, messages: messagesProps }} />
    )
  }
}

export default PageContainer;
