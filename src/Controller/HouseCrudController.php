<?php

namespace App\Controller;

use App\Entity\House;
use App\Form\HouseType;
use App\Repository\HouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/house/crud')]
class HouseCrudController extends AbstractController
{
    #[Route('/', name: 'app_house_crud_index', methods: ['GET'])]
    public function index(HouseRepository $houseRepository): Response
    {
        return $this->render('house_crud/index.html.twig', [
            'houses' => $houseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_house_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $house = new House();
        $form = $this->createForm(HouseType::class, $house);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($house);
            $entityManager->flush();

            return $this->redirectToRoute('app_house_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('house_crud/new.html.twig', [
            'house' => $house,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_house_crud_show', methods: ['GET'])]
    public function show(House $house): Response
    {
        return $this->render('house_crud/show.html.twig', [
            'house' => $house,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_house_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, House $house, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HouseType::class, $house);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_house_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('house_crud/edit.html.twig', [
            'house' => $house,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_house_crud_delete', methods: ['POST'])]
    public function delete(Request $request, House $house, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$house->getId(), $request->request->get('_token'))) {
            $entityManager->remove($house);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_house_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
