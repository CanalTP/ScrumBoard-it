<?php

namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ScrumBoardItBundle\Form\Type\LoginType;
use ScrumBoardItBundle\Form\Type\RegistrationType;
use ScrumBoardItBundle\Entity\Mapping\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Controller of security.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY', 'IS_CONFIGURED')) {
            return $this->redirect('home');
        }

        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        // get the login error if there is one
        $error = $this->get('security.authentication_utils')->getLastAuthenticationError();

        return $this->render('ScrumBoardItBundle:Security:login.html.twig', array(
            'form' => $form->createView(),
            'error' => $error,
        ));
    }

    /**
     * @Route("/registration", name="registration")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function registrationAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect('home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        $error = null;

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->get('general.persist')->initiate(array(
                    'user' => $user,
                ));
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);
                $this->get('session')->set('_security_main', serialize($token));

                $this->get('jira_configuration.persist')->initiate();
                $this->get('favorites.persist')->initiate();
                $this->get('user.provider')->loadUserByUsername($user->getUsername());

                return $this->redirect('login');
            } catch (UniqueConstraintViolationException $e) {
                $error = new \Exception("Désolé, ce nom d'utilisateur est déjà utilisé...");
            } catch (\Exception $e) {
                dump($e);
                $error = new \Exception("Une erreur s'est produite, veuillez réessayer.");
            }
        }

        return $this->render('ScrumBoardItBundle:Security:registration.html.twig', array(
            'form' => $form->createView(),
            'error' => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        // No action, Symfony security removes the session and redirects to the welcome page
    }

    /**
     * @Route("/discover", name="discover")
     */
    public function visitorAction()
    {
        // No action, Guard authenticates the user as a visitor and redirects to the home page
    }
}
