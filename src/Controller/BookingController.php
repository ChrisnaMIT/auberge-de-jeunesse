<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookingController extends AbstractController
{
    #[Route('/booking', name: 'app_booking')]
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

    //

    #[Route('/booking/create', name: 'app_booking_create')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $booking->calculateTotalPrice();
            $manager->persist($booking);
            $manager->flush();
            return $this->redirectToRoute('app_booking_show', ['id' => $booking->getId()]);
        }
        return $this->render('booking/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //

    #[Route('/booking/{id}/show', name: 'app_booking_show')]
    public function show(Booking $booking): Response
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    //

    #[Route('/booking/{id}/edit', name: 'app_booking_edit')]
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $booking->calculateTotalPrice();
            $manager->persist($booking);
            $manager->flush();
            return $this->redirectToRoute('app_booking_show', ['id' => $booking->getId()]);
        }
        return $this->render('booking/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //

    #[Route('/booking/{id}/delete', name: 'app_booking_delete')]
    public function delete(Booking $booking, EntityManagerInterface $manager): Response
    {
        $manager->remove($booking);
        $manager->flush();
        return $this->redirectToRoute('app_booking');
    }
}
