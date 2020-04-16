import React, { Fragment } from 'react';
import { Navbar, Nav, Button, Spinner } from 'react-bootstrap';
import Logo from '../../logo.svg';
import LoginForm from './LoginForm';
import { Link } from 'react-router-dom';

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
      <Link to="/" className="nav-link">Accueil</Link>
      { global.currentUser.data !== null ?
        <Fragment>
          <Link to="/profiles" className="nav-link">Profils</Link>
          <Link to="/profile/visitors" className="nav-link">Mes visiteurs</Link>
        </Fragment>
        :
        null
      }
    </Nav>
    { global.currentUser.fetching ?
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
