import React from 'react';
import { Layout } from '../components';
import PageContainer from '../containers/PageContainer';
import { Button } from 'react-bootstrap';
import { Link } from 'react-router-dom';

const NotFound = ({ global }) =>
  <Layout global={global}>
    <h1>Page inexistante</h1>
    <p>Vous allez sûrement trouver l'âme soeur, mais pas ici...</p>
    <Link to="/">
      <Button>Retour à l'accueil</Button>
    </Link>
  </Layout>
;

export default () =>
  <PageContainer component={NotFound} />
;
