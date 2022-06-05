<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Employe;
use App\Entity\User;
use App\Exception\FormException;
use App\Repository\DepartementRepository;
use App\Repository\EmployeRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\UserRepository;
use App\Service\AuthyHelper;
use App\Service\VerifyService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Exception\InvalidCustomGenerator;
use Doctrine\ORM\ORMException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class SecurityController extends AbstractFOSRestController
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $encoder;
    private UserRepository $userRepository;
    private AuthyHelper $authy;
    private LoggerInterface $logger;
    private EmployeRepository $employeRepository;
    private $JWTEncoder;
    /**
     * Verification service
     *
     * @var VerifyService
     */
    protected VerifyService $verify;

    public function __construct(EmployeRepository $employeRepository, LoggerInterface $logger, JWTEncoderInterface $JWTEncoder, VerifyService $verify, AuthyHelper $authyHelper, UserRepository $userRepository, UserPasswordHasherInterface $encoder, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
        $this->authy = $authyHelper;
        $this->verify = $verify;
        $this->logger = $logger;
        $this->JWTEncoder = $JWTEncoder;
        $this->employeRepository = $employeRepository;
    }

    /**
     * @Rest\Get("/v1/users",name="getusers")
     * @return Response
     */
    public function cgetUsersAction()
    {
        //$users = $this->userRepository->findByRoleNotIn("['ROLE_ADMIN']");
        $users = $this->employeRepository->findAll();
        $view = $this->view($users, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/users/admin",name="getusersadmin")
     * @return Response
     */
    public function cgetUsersAdminAction()
    {
        $users = $this->userRepository->findAll();
        $view = $this->view($users, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/users/{id}",name="getuserid")
     * @param Request $request
     * @return Response
     */
    public function getUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository(User::class)->find($request->get('id'));

        if (!$book) {
            throw new ResourceNotFoundException("Resource not found");
        }

        $view = $this->view($book, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/users/import",name="importuserpost")
     * @param Request $request
     * @return Response
     */
    public function importUserPost(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $values=$data['data'];
        $body = $values['employes'];
        for($i=0;$i<sizeof($body);$i++){
            $email = $body[$i]['email'];
            $user = $this->userRepository->findOneBy(['email' => $email]);
            if (is_null($user)) {
                $user = new User();
                $plainPassword = "123456789";
                $hashedPassword = $this->encoder->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }
            $user->setName($body[$i]['firstname']);
            $user->setLastname($body[$i]['lastname']);
            $user->setPhone($body[$i]['phone']);
            $user->setEmail($body[$i]['email']);
            $roles = ["ROLE_USER"];
            $user->setRoles($roles);
            $user->setCountry($body[$i]['country']);
            $employe = new Employe();
            $employe->setName($body[$i]['firstname'] . ' ' . $body[$i]['lastname']);
            $employe->setCompte($user);
            $employe->updateTimestamps();
            $em->persist($user);
            $em->persist($employe);
        }
        $em->flush();
        $view = $this->view($body, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/users",name="getuserpost")
     * @param Request $request
     * @return Response
     */
    public function getUserPost(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $body = $data['data'];
        $email = $body['email'];
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (is_null($user)) {
            $user = new User();
            $plainPassword = $body['password'];
            $hashedPassword = $this->encoder->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        }

        $user->setName($body['firstname']);
        $user->setLastname($body['lastname']);
        $user->setPhone($body['phone']);
        $user->setEmail($body['email']);
        $roles = [$body['roles']];
        $user->setRoles($roles);
        $user->setCountry($body['country']);
        $employe = new Employe();
        $employe->setName($body['firstname'] . ' ' . $body['lastname']);
        $employe->setCompte($user);
        $employe->updateTimestamps();
        $em->persist($user);
        $em->persist($employe);
        $em->flush();
        $view = $this->view($user, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Delete("/v1/users/{id}", name="app_users_delete")
     */
    public function delete(Request $request): Response
    {
        $user = $this->employeRepository->find($request->get('id'));
        if (is_null($user)) {
            throw new ResourceNotFoundException("Resource user is null");
        }
       // $employe = $this->employeRepository->findOneBy(['compte' => $user]);
        $this->em->remove($user);
       // $this->em->remove($employe);
        $this->em->flush();
        $view = $this->view(null, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Delete("/v1/users/{id}/user", name="app_users_deleteuser")
     */
    public function deleteUser(Request $request): Response
    {
        $user = $this->userRepository->find($request->get('id'));
        if (is_null($user)) {
            throw new ResourceNotFoundException("Resource user is null");
        }
        $employe = $this->employeRepository->findOneBy(['compte' => $user]);
        $this->em->remove($user);
        $this->em->remove($employe);
        $this->em->flush();
        $view = $this->view(null, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Put("/v1/users",name="putuser")
     * @param Request $request
     * @return Response
     */
    public function getUserPut(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $body = $data['data'];
        $id = $body['id'];
        $user = $this->userRepository->find(['id' => $id]);

        if (is_null($user)) {
            throw new ResourceNotFoundException("Resource user is null");
        }
        $employe = $this->employeRepository->findOneBy(['compte' => $user]);

        $user->setPhone($body['phone']);
        $user->setName($body['firstname']);
        $user->setLastname($body['lastname']);
        $user->setEmail($body['email']);
        $user->setRoles($body['roles']);
        $user->setCountry($body['country']);
        if (!is_null($employe)) {
            $employe->setName($body['firstname'] . ' ' . $body['lastname']);
            $employe->updateTimestamps();
        }

        $em->flush();
        $view = $this->view($user, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/auth/login",name="applogin",)
     * @param Request $request
     * @param JWTEncoderInterface $JWTEncoder
     * @return Response
     * @throws ORMException
     * @throws JWTEncodeFailureException
     */
    public function postLoginAction(Request $request)
    {
        $body = json_decode($request->getContent(), true);

        return $this->login($body['email'], $body['password']);
    }

    /**
     * Creates a new Job.
     *
     * @param $email
     * @param $password
     * @param JWTEncoderInterface $JWTEncoder
     * @return Response|null
     * @throws JWTEncodeFailureException
     */
    public function login($email, $password): ?Response
    {
        $em = $this->getDoctrine()->getManager();
        //  $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (null == $user) {
            throw new ResourceNotFoundException("Resource $email not found");
        }
        $employe = $this->employeRepository->findOneBy(['compte' => $user]);
        $entrepriseid=null; $entreprisename=null;
        if (!is_null($employe)){
        }
        $isValid = $this->encoder->isPasswordValid($user, $password);
        if (!$isValid) {
            throw new ResourceNotFoundException("Resource $password not work");
        }
        $channel = 'sms';
        $verification = $this->verify->startVerification("+" . $user->getPhone(), $channel);
        // $this->logger->info("------",$verification->getErrors());
        if (!$verification->isValid()) {
            $token =
                $this->JWTEncoder->encode([
                    'username' => $user->getEmail(),
                    'exp' => time() + 3600 // 1 hour expiration
                ]);
            $values = [
                "token" => null,
                "id" => $user->getId(),
                "email" => $user->getEmail(),
                "roles" => $user->getRoles(),
                "photoURL" => "",
                "otpSend" => true,
                "entrepriseid"=>$entrepriseid,
                "entreprisename"=>$entreprisename,
                "emailVerified" => false
            ];
        } else {
            $token =
                $this->JWTEncoder->encode([
                    'username' => $user->getEmail(),
                    'exp' => time() + 3600 // 1 hour expiration
                ]);
            $values = [
                "token" => null,
                "id" => $user->getId(),
                "email" => $user->getEmail(),
                "roles" => $user->getRoles(),
                "photoURL" => "",
                "entrepriseid"=>$entrepriseid,
                "entreprisename"=>$entreprisename,
                "otpSend" => false,
                "emailVerified" => false
            ];
        }
        // $this->logger->info("-------------".$values["token"]);
        $view = $this->view($values, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/auth/signin",name="appsignup",)
     * @param Request $request
     * @return Response
     * @throws InvalidCustomGenerator
     */
    public function postSingUpAction(Request $request)
    {
        $body = json_decode($request->getContent(), true);
        $user=$this->userRepository->findOneBy(['email'=>$body['email']]);
        if (!is_null($user)){
            throw new InvalidCustomGenerator("Resource not found");
        }
        $customer=new Customer();
        $customer->setFirstname($body['firstname']);
        $customer->setLastname($body['lastname']);
        $customer->setEmail($body['email']);
        $customer->setPhone($body['phone']);
        $this->em->persist($customer);
        $user=new User();
        $user->setPhone($body['phone']);
        $user->setEmail($body['email']);
        $user->setName($body['firstname']);
        $user->setLastname($body['lastname']);
        $user->setRoles(['ROLE_CUSTOMER']);
        $plainPassword = $body['password'];
        $hashedPassword = $this->encoder->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        $this->em->persist($user);
        $customer->setCompte($user);
        $this->em->flush();
        $view = $this->view($customer, Response::HTTP_OK);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/auth/resendotp/{id}",name="resendotp",)
     */
    public function resend(Request $request, User $user)
    {
        $phone = $user->getPhone();
        $channel = $request->get('channel', 'sms');
        $verification = $this->verify->startVerification("+" . $phone, $channel);

        if (!$verification->isValid()) {

            $this->addFlash('registration_error', $verification->getErrors()[0]);

        }

        $url = $this->generateUrl('verify_otp', ['id' => $user->getId()]);
        return $this->redirect($url);
    }

    /**
     * @Rest\Post("/auth/verifyotp/{id}/{code}",name="verifyotp",)
     * @param Request $request
     * @return Response
     * @throws JWTEncodeFailureException
     */
    public function verifyOTPRest(Request $request)
    {
        $error = true;
        $user = $this->userRepository->find($request->get('id'));
        $code = $request->get('code');
        $phone = $user->getPhone();
        $employe = $this->employeRepository->findOneBy(['compte' => $user]);
        $entrepriseid=null; $entreprisename=null;
        if (!is_null($employe)){
        }
        $verification = $this->verify->checkVerification("+" . $phone, $code);

        //$this->logger->info("###################".$verification->isValid());
        if ($verification->isValid()) {
            $token =
                $this->JWTEncoder->encode([
                    'username' => $user->getEmail(),
                    'exp' => time() + 3600 // 1 hour expiration
                ]);
            $values = [
                "token" => $token,
                "id" => $user->getId(),
                "email" => $user->getEmail(),
                "roles" => $user->getRoles(),
                "entrepriseid"=>$entrepriseid,
                "entreprisename"=>$entreprisename,
                "photoURL" => "",
                "otpSend" => true,
                "emailVerified" => true
            ];
        } else {
            $values = [
                "token" => null,
                "id" => $user->getId(),
                "email" => $user->getEmail(),
                "roles" => $user->getRoles(),
                "entrepriseid"=>$entrepriseid,
                "entreprisename"=>$entreprisename,
                "photoURL" => "",
                "otpSend" => true,
                "emailVerified" => false
            ];
        }
        $view = $this->view($values, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/auth/resendtoken/{id}",name="resendtoken",)
     * @param Request $request
     * @return Response
     * @throws JWTEncodeFailureException
     */
    public function resendToken(Request $request)
    {
        $user = $this->userRepository->find($request->get('id'));
        $token =
            $this->JWTEncoder->encode([
                'username' => $user->getEmail(),
                'exp' => time() + 3600 // 1 hour expiration
            ]);
        $values = [
            "token" => $token,
            "id" => $user->getId(),
            "email" => $user->getEmail(),
            "roles" => $user->getRoles(),
            "photoURL" => "",
            "otpSend" => true,
            "emailVerified" => true
        ];

        $view = $this->view($values, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/auth/google",name="setauthgoogle",)
     * @param Request $request
     * @return Response
     * @throws JWTEncodeFailureException
     */
    public function setAuthGoogle(Request $request)
    {
        $error = true;
        $body = json_decode($request->getContent(), true);
        $email = $body['email'];
        $user = $this->userRepository->findOneBy(['email' => $email]);
        $employe = $this->employeRepository->findOneBy(['compte' => $user]);
        $entrepriseid=null; $entreprisename=null;
        if (!is_null($employe)){
        }
        if (!is_null($user)) {

            $token =
                $this->JWTEncoder->encode([
                    'username' => $user->getEmail(),
                    'exp' => time() + 3600 // 1 hour expiration
                ]);
            $values = [
                "token" => $token,
                "id" => $user->getId(),
                "email" => $user->getEmail(),
                "roles" => $user->getRoles(),
                "entrepriseid"=>$entrepriseid,
                "entreprisename"=>$entreprisename,
                "photoURL" => "",
                "otpSend" => true,
                "emailVerified" => true
            ];
        } else {
            $values = [
                "token" => null,
                "id" => null,
                "email" => $email,
                "roles" => null,
                "photoURL" => "",
                "entrepriseid"=>$entrepriseid,
                "entreprisename"=>$entreprisename,
                "otpSend" => true,
                "emailVerified" => false
            ];
        }


        $view = $this->view($values, Response::HTTP_OK);
        return $this->handleView($view);
    }
}
