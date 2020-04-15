import React, { Component } from 'react';
import Axios from 'axios';

const { REACT_APP_API_BASE_URL } = process.env;

export default class PageContainer extends Component
{
  state = {
    currentUser: {
      apiToken: null,
      data: null,
    },
    messages: [],
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
        }
      );

      const { currentUser } = this.state;
      currentUser.apiToken = response.data.token;
  
      await this.setState({ currentUser });
  
      this.refreshCurrentUser();

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

  logout = () => {
    this.setState({
      currentUser: {
        apiToken: null,
        data: null,
      }
    });

    const { messages } = this.state;
    this.setState({ messages: [...messages, {
      message: 'Vous êtes maintenant déconnecté',
      type: 'info',
    }] });
  }

  refreshCurrentUser = async () => {
    const response = await Axios.get(
      `${REACT_APP_API_BASE_URL}/current-user`,
      {
        headers: {
          'X-AUTH-TOKEN': this.state.currentUser.apiToken,
        }
      }
    )
    
    const { currentUser } = this.state;
    currentUser.data = response.data;

    await this.setState({ currentUser });
  }

  deleteMessage = (index) => () => {
    const { messages } = this.state;
    messages.splice(index, 1);
    this.setState({ messages });
  }

  render = () => {
    const { component } = this.props;

    const ComponentName = component;

    const { currentUser, messages } = this.state;

    const currentUserProps = {
      ...currentUser,
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
