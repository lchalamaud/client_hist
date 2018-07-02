<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\User;

class UserManagerController extends Controller
{
    /**
     * @Route("/admin/manager/", name="admin_manager")
     */
    public function indexAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();
        return $this->render('Default/userManager.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * @Route("/admin/manager/role/", name="manage_role")
     */
    public function manageRole(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy(array('username'=>$request->get('username')));
        $userManager = $this->get('fos_user.user_manager');   
        $user->setRoles(array($request->get('role')));
        $userManager->updateUser($user);

        return new JsonResponse();

    }

    /**
     * @Route("/admin/manager/del/", name="manage_dels")
     */
    public function delUser(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy(array('username'=>$request->get('username')));
        $em->remove($user);
        $em->flush();

        return new JsonResponse();

    }

}