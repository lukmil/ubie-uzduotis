<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cities;
use AppBundle\Entity\Countries;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    /**
     * @Route(path="/register", name="authentication_register")
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("success", '');
            return $this->redirectToRoute('authentication_login');

        }
        return $this->render('AppBundle:uzduotis:register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/")
     */
    public function mainAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->render('AppBundle:uzduotis:index.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * @Route("/login", name="authentication_login")
     */
    public function loginActionAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('AppBundle:uzduotis:login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     * @Method({"GET"})
     */
    public function logoutAction(Request $request)
    {
        $session = $this->$request->getSession();
        $session = $this->get('session')->clear();
        return $this->render('AppBundle:uzduotis:login.html.twig');
    }


    /**
     * @Route("/country/{country}")
     * @Method({"GET"})
     * @param Countries $country
     * @return JsonResponse
     */
    public function getCitiesByCountryAction(Countries $country = null)
    {
        $manager = $this->getDoctrine()->getRepository('AppBundle:Cities');
        if (!$country) {
            $data = $manager->findAll();
        } else {
            $data = $manager->findBy(['country' => $country]);
        }

        $arr = [];
        //Building array from objects
        foreach ($data as $d) {
            $arr[] = [
                'id' => $d->getId(),
                'name' => $d->getName()
            ];
        }
        return new JsonResponse($arr);


    }

}
