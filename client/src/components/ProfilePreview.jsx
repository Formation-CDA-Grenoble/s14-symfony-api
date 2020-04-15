import React from 'react';
import { Card, ListGroup, Image } from 'react-bootstrap';

const GENDERS = ['Femme', 'Homme'];

const computeAge = (birthDate) => {
  const diff = (new Date() - new Date(birthDate)) / 31557600000;
  return Math.abs(Math.round(diff));
}

const ProfilePreview = ({ user, visited }) =>
  <Card border={visited ? '' : 'primary'} className="text-center shadow-hover">
    <Card.Header>
      <Image width="100px" src={user.galleryPictures[0].url} roundedCircle />
      <Card.Title className="mt-2">{user.firstName} {user.lastName}</Card.Title>
    </Card.Header>
    <ListGroup variant="flush">
      <ListGroup.Item>Sexe: {GENDERS[user.gender]}</ListGroup.Item>
      <ListGroup.Item>Age: {computeAge(user.birthDate.date)}</ListGroup.Item>
      <ListGroup.Item>Ville: {user.city.name}</ListGroup.Item>
    </ListGroup>
  </Card>
;

export default ProfilePreview;
