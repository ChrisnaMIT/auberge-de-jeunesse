<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }


    #[Route('/promote/admin/{id}', name: 'app_promote_admin')]
    public function promoteAdmin(User $user, EntityManagerInterface $manager): Response
    {
        if(!in_array('ROLE_ADMIN', $user->getRoles())) {
            $user->setRoles(['ROLE_ADMIN']);
            $manager->persist($user);
            $manager->flush();
        }
        return $this->redirectToRoute('app_admin');
    }


    #[Route('/demote/admin/{id}', name: 'app_demote_admin')]
    public function demoteAdmin(User $user, EntityManagerInterface $manager): Response
    {
        if(!in_array('ROLE_ADMIN', $user->getRoles())) {
            $user->setRoles([]);
            $manager->persist($user);
            $manager->flush();
        }
        return $this->redirectToRoute('app_admin');
    }


    #[Route('/promote/employee/{id}', name: 'app_promote_employee')]
    public function promoteEmployee(User $user, EntityManagerInterface $manager): Response
    {
        if(!in_array('ROLE_EMPLOYEE', $user->getRoles())) {
            $user->setRoles(['ROLE_EMPLOYEE']);
            $manager->persist($user);
            $manager->flush();
        }
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/demote/employee/{id}', name: 'app_demote_employee')]
    public function demoteEmployee(User $user, EntityManagerInterface $manager): Response
    {
        if(!in_array('ROLE_EMPLOYEE', $user->getRoles())) {
            $user->setRoles([]);
            $manager->persist($user);
            $manager->flush();
        }
        return $this->redirectToRoute('app_admin');
    }


    #[Route('/promote/manager/{id}', name: 'app_promote_manager')]
    public function promoteManager(User $user, EntityManagerInterface $manager): Response
    {
        if(!in_array('ROLE_MANAGER', $user->getRoles())) {
            $user->setRoles(['ROLE_MANAGER']);
            $manager->persist($user);
            $manager->flush();
        }
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/demote/manager/{id}', name: 'app_demote_manager')]
    public function demoteManager(User $user, EntityManagerInterface $manager): Response
    {
        if(!in_array('ROLE_MANAGER', $user->getRoles())) {
            $user->setRoles([]);
            $manager->persist($user);
            $manager->flush();
        }
        return $this->redirectToRoute('app_admin');
    }













}
