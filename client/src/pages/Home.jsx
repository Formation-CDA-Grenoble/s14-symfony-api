import React from 'react';
import PageContainer from '../containers/PageContainer';
import { Layout } from '../components';

const Home = ({ global }) =>
  <Layout global={global}>
    Home
  </Layout>
;

export default () =>
  <PageContainer component={Home} />
;
