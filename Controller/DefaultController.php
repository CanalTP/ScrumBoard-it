<?php

namespace CanalTP\ScrumBoardItBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * controller of navigation.
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="canal_tp_postit_homepage")
     * 
     * @return Response
     */
    public function indexAction()
    {
        $manager = $this->container->get('canal_tp_scrum_board_it.service.manager');
        $service = $manager->getService();

        return $this->render(
            'CanalTPScrumBoardItBundle:Default:index.html.twig',
            array(
                'issues' => $service->getIssues(),
            )
        );
    }

    /**
     * @Route("/print", name="canal_tp_postit_print")
     * 
     * @param Request $request
     * @return Response
     */
    public function printAction(Request $request)
    {
        $manager = $this->container->get('canal_tp_scrum_board_it.service.manager');
        $service = $manager->getService();
        /* @var $service \CanalTP\ScrumBoardItBundle\Service\AbstractService */
        $selected = $request->request->get('issues');

        return $this->render(
            'CanalTPScrumBoardItBundle:Print:tickets.html.twig',
            array(
                'issues' => $service->getIssues($selected),
            )
        );
    }

    /**
     * @Route("/flag/add", name="canal_tp_postit_add_flag")
     * 
     * @param Request $request
     * @return Response
     */
    public function addFlagAction(Request $request)
    {
        $manager = $this->container->get('canal_tp_scrum_board_it.service.manager');
        $service = $manager->getService();
        /* @var $service \CanalTP\ScrumBoardItBundle\Service\AbstractService */
        $selected = $request->request->get('issues');
        $service->addFlag($selected);

        return $this->redirect($this->generateUrl('canal_tp_postit_homepage'));
    }
}
