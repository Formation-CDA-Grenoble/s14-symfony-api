import React from 'react';
import NavBar from './Navbar';
import { Container, Alert } from 'react-bootstrap';

const Layout = ({ global, children }) =>
  <div>
    <header>
      <NavBar global={global} />
    </header>
    <main>
      <Container>
        {global.messages.messages.map( (message, index) =>
          <Alert key={index} variant={message.type} onClose={global.messages.actions.delete(index)} dismissible>
            {message.message}
          </Alert>
        )}
        {children}
      </Container>
    </main>
  </div>
;

export default Layout;
