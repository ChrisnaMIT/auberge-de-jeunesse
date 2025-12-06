<?php

namespace App\Controller;

use App\Entity\Bed;
use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RoomController extends AbstractController
{
    #[Route('/room', name: 'app_room')]
    public function index(RoomRepository $roomRepository): Response
    {
        return $this->render('room/index.html.twig', [
            'rooms' => $roomRepository->findAll(),
        ]);
    }

    #[Route('/rooms', name: 'client_rooms')]
    public function clientRooms(RoomRepository $roomRepository): Response
    {
        $rooms = $roomRepository->findAll();

        return $this->render('client/rooms/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    //


    #[Route('/room/create', name: 'app_room_create')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($room);
            $manager->flush();
            // Auto-générer les lits si c'est un dortoir
            if ($room->getType() === 'dorm' && $room->getNumberOfBeds() > 0) {

                for ($i = 1; $i <= $room->getNumberOfBeds(); $i++) {

                    $bed = new Bed();
                    $bed->setNumber($i);
                    $bed->setStatus("available");
                    $bed->setRoom($room);

                    $manager->persist($bed);
                }

                $manager->flush();
            }

            return $this->redirectToRoute('app_room_show', ['id' => $room->getId()]);
        }
        return $this->render('room/create.html.twig', [
            'form' => $form->createView(),
        ]);

    }


    //


    #[Route('/room/{id}/show', name: 'app_room_show')]
    public function show(Room $room): Response
    {
        return $this->render('room/show.html.twig', [
            'room' => $room,
        ]);
    }


    //

    #[Route('/room/{id}/edit', name: 'app_room_edit')]
    public function edit(Room $room, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($room);
            $manager->flush();
            return $this->redirectToRoute('app_room_show', ['id' => $room->getId()]);
        }
        return $this->render('room/edit.html.twig', [
            'room' => $form->createView(),
        ]);
    }


    //

    #[Route('/room/{id}/delete', name: 'app_room_delete')]
    public function delete(Room $room, EntityManagerInterface $manager): Response
    {
        $manager->remove($room);
        $manager->flush();
        return $this->redirectToRoute('app_room');
    }




}
