<?php

namespace App\Repository;

use App\Entity\User;
use App\Repository\CityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private $cityRepository;

    public function __construct(ManagerRegistry $registry, CityRepository $cityRepository)
    {
        parent::__construct($registry, User::class);

        $this->cityRepository = $cityRepository;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function getVisitors(User $visited)
    {
        // Crée un Query builder afin de générer une requête SQL précise
        return $this
            // Associe les entités User à l'identifiant "u"
            ->createQueryBuilder('u')
            // Ajoute une jointure avec les entités Visit en les associant à l'identifiant "v"
            // en précisant que les visites concernées sont celles qui ont été envoyées par les utilisateurs
            ->join('u.sentVisits', 'v')
            // Ajoute un critère de sélection: pour chaque visite, l'utilisateur qui a reçu la visite
            // doit être celui qui a été passé en paramètre de la fonction
            ->andWhere('v.visited = :user')
            ->setParameter('user', $visited)
            // Ordonne les résultats par date, de la visite la plus récente à la plus ancienne
            ->orderBy('v.createdAt', 'DESC')
            // Génère la requête SQL correspondante
            ->getQuery()
            // Renvoie les résultats de la requête
            ->getResult()
        ;
    }

    public function search(array $params)
    {
        list($minAge, $maxAge) = $params['age'];

        $minDate = (new \DateTime())->sub(new \DateInterval('P' . $maxAge . 'Y'));
        $maxDate = (new \DateTime())->sub(new \DateInterval('P' . $minAge . 'Y'));

        $query = $this
            ->createQueryBuilder('u')
            ->andWhere('u.gender = :gender')
            ->setParameter('gender', $params['gender'])
            ->andWhere('u.birthDate >= :minDate')
            ->andWhere('u.birthDate <= :maxDate')
            ->setParameter('minDate', $minDate)
            ->setParameter('maxDate', $maxDate)
            ->orderBy('u.createdAt', 'DESC')
        ;

        if (!is_null($params['city'])) {
            $city = $this->cityRepository->find($params['city']);

            $query = $query
                ->andWhere('u.city = :city')
                ->setParameter('city', $city)
            ;
        }

        return $query->getQuery()->getResult();
    }

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
