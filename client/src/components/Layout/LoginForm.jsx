import React, { Component } from 'react';
import { Form, FormControl, Button } from 'react-bootstrap';

export default class LoginForm extends Component
{
  state = {
    email: '',
    password: '',
  }

  setProperty = (propName) => (event) => {
    this.setState({ [propName]: event.target.value });
  }

  submit = (event) => {
    const { global } = this.props;
    const { email, password } = this.state;

    event.preventDefault();

    global.currentUser.actions.login(email, password);
  }

  render = () => {
    const { email, password } = this.state;

    return (
      <Form inline onSubmit={this.submit}>
        <FormControl type="text" placeholder="E-mail" className=" mr-sm-2" value={email} onChange={this.setProperty('email')} />
        <FormControl type="password" placeholder="Mot de passe" className=" mr-sm-2" value={password} onChange={this.setProperty('password')} />
        <Button type="submit">Connexion</Button>
      </Form>
    );
  }
}
