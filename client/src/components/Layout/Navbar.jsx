import React from 'react';
import { Navbar, Nav, Button, Spinner } from 'react-bootstrap';
import Logo from '../../logo.svg';
import LoginForm from './LoginForm';

const CustomNavBar = ({ global }) =>
  <Navbar bg="dark" variant="dark">
    <Navbar.Brand href="#home">
      <img
        src={Logo}
        width="30"
        height="30"
        className="d-inline-block align-top"
        alt="React Bootstrap logo"
      />{' '}
      Symfony Dating
    </Navbar.Brand>
    <Nav className="mr-auto">
      <Nav.Link href="#home">Home</Nav.Link>
      <Nav.Link href="#features">Features</Nav.Link>
      <Nav.Link href="#pricing">Pricing</Nav.Link>
    </Nav>
    { global.currentUser.fecthing ?
      <Spinner animation="border" variant="light" />
      : global.currentUser.data === null ?
        <LoginForm global={global} />
        :
        <Nav>
          <Navbar.Text className="mr-2">
            Bonjour {global.currentUser.data.firstName} {global.currentUser.data.lastName}!
          </Navbar.Text>
          <Button onClick={global.currentUser.actions.logout} variant="secondary">Déconnexion</Button>
        </Nav>
    }
  </Navbar>
;

export default CustomNavBar;
