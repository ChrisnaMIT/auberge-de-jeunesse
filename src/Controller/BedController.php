<?php

namespace App\Controller;

use App\Entity\Bed;
use App\Entity\Room;
use App\Form\BedType;
use App\Repository\BedRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BedController extends AbstractController
{
    #[Route('/bed', name: 'app_bed')]
    public function index(BedRepository $bedRepository): Response
    {
        return $this->render('bed/index.html.twig', [
            'beds' => $bedRepository->findAll(),
        ]);

    }


    //


    #[Route('/bed//create/{room}', name: 'app_bed_create')]
    public function create(Request $request, EntityManagerInterface $manager, Room $room): Response
    {
        $bed = new Bed();
        $bed->setRoom($room);

        $form = $this->createForm(BedType::class, $bed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($bed);
            $manager->flush();

            return $this->redirectToRoute('app_room_show', ['id' => $room->getId()]);
        }

        return $this->render('bed/create.html.twig', [
            'bed' => $bed,
            'form' => $form,
            'room' => $room
        ]);
    }



    //

    #[Route('/bed/show/{id}', name: 'app_bed_show')]
    public function show(Bed $bed): Response
    {
        return $this->render('bed/show.html.twig', [
            'bed' => $bed,
        ]);
    }


    //

    #[Route('/bed//edit/{id}', name: 'app_bed_edit')]
    public function edit(Request $request, Bed $bed, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(BedType::class, $bed);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($bed);
            $manager->flush();
            return $this->redirectToRoute('app_bed_show', ['id' => $bed->getId()]);
        }
        return $this->render('bed/edit.html.twig', [
            'form' => $form->createView(),
            'bed' => $bed,
        ]);
    }



    //

    #[Route('/bed//delete/{id}', name: 'app_bed_delete')]
    public function delete(Bed $bed, EntityManagerInterface $manager): Response
    {
        $manager->remove($bed);
        $manager->flush();
        return $this->redirectToRoute('app_bed');

    }


















}
