import React from 'react';
import { HomePage, ProfileListPage, NotFoundPage, ProfilePage, VisitorsPage } from './pages';
import { BrowserRouter, Switch, Route } from 'react-router-dom';

const App = () =>
  <BrowserRouter>
    <Switch>
      <Route exact path="/" component={HomePage} />
      <Route exact path="/profiles" component={ProfileListPage} />
      <Route exact path="/profiles/:id(\d+)" component={ProfilePage} />
      <Route exact path="/profile/visitors" component={VisitorsPage} />
      <Route exact path="/notfound" component={NotFoundPage} />
      <Route component={NotFoundPage} />
    </Switch>
  </BrowserRouter>
;

export default App;
