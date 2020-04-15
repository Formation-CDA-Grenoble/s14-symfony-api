<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Security\TokenAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    private $userRepository;
    private $authenticator;
    private $guardHandler;
    private $passwordEncoder;
    private $serializer;

    public function __construct(
        UserRepository $userRepository,
        TokenAuthenticator $authenticator,
        GuardAuthenticatorHandler $guardHandler,
        UserPasswordEncoderInterface $passwordEncoder,
        SerializerInterface $serializer
    )
    {
        $this->userRepository = $userRepository;
        $this->authenticator = $authenticator;
        $this->guardHandler = $guardHandler;
        $this->passwordEncoder = $passwordEncoder;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/login", methods={"POST"}, name="login")
     */
    public function login(Request $request)
    {
        $data = \json_decode($request->getContent());

        $email = $data->email;
        $password = $data->password;

        $user = $this->userRepository->findOneBy([ 'email' => $email ]);

        if (is_null($user)) {
            throw new NotFoundHttpException('User "' . $email . '" does not exist.');
        }

        if ($this->passwordEncoder->isPasswordValid($user, $password)) {
            $this->guardHandler->authenticateUserAndHandleSuccess(
                $user,          // the User object you just created
                $request,
                $this->authenticator, // authenticator whose onAuthenticationSuccess you want to use
                'main'          // the name of your firewall in security.yaml
            );
    
            return $this->redirectToRoute('current-user');
            // return new JsonResponse([ 'token' => $user->getApiToken() ]);
        } else {
            throw new UnauthorizedHttpException('', 'Bad password for user "' . $email . '".');
        }
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() { }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/current-user", name="current-user")
     */
    public function getCurrentUser() {
        $user = $this->getUser();

        return new JsonResponse($user);
    }
}
