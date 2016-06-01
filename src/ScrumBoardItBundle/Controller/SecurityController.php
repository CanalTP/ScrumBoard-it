<?php
namespace ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use ScrumBoardItBundle\Form\Type\LoginType;

/**
 * Controller of security
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     *
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_AUTHENTICATED')) {
            return $this->redirect('home');
        }

        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('ScrumBoardItBundle:Security:login.html.twig', array(
            'form' => $form->createView(),
            'error' => $error
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
    }
}
