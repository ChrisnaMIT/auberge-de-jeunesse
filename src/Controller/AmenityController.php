<?php

namespace App\Controller;

use App\Entity\Amenity;
use App\Form\AmenityType;
use App\Repository\AmenityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/amenity')]
final class AmenityController extends AbstractController
{
    #[Route('/', name: 'app_amenity')]
    public function index(AmenityRepository $amenityRepository): Response
    {
        return $this->render('amenity/index.html.twig', [
            'amenities' => $amenityRepository->findAll(),
        ]);
    }

    //



    #[Route('/amenity/create', name: 'app_amenity_create')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $amenity = new Amenity();
        $form = $this->createForm(AmenityType::class, $amenity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($amenity);
            $manager->flush();
            return $this->redirectToRoute('app_amenity');
        }

        return $this->render('amenity/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //



    #[Route('/amenity/{id}/show', name: 'app_amenity_show')]
    public function show(Amenity $amenity): Response
    {
        return $this->render('amenity/show.html.twig', [
            'amenity' => $amenity,
        ]);
    }

    //



    #[Route('/amenity/{id}/edit', name: 'app_amenity_edit')]
    public function edit(Amenity $amenity, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AmenityType::class, $amenity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            return $this->redirectToRoute('app_amenity');
        }

        return $this->render('amenity/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //



    #[Route('/amenity/{id}/delete', name: 'app_amenity_delete')]
    public function delete(Amenity $amenity, EntityManagerInterface $manager): Response
    {
        $manager->remove($amenity);
        $manager->flush();
        return $this->redirectToRoute('app_amenity');
    }
}
