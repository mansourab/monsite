<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemFormType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{

    /**
     * @Route("/", name="app_home")
     */
    public function home(ItemRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {

        $items = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('home/index.html.twig', ['items' => $items]);
    }

    /**
     * @Route("/admin", name="index_item")
     * @param ItemRepository $repository
     * @param Request $request
     * @return Response
     */
    public function index(ItemRepository $repository, Request $request)
    {
        $items = $repository->findAll();
        return $this->render('item/index.html.twig', ['items' => $items]);
    }

    /**
     * @Route("/admin/add", name="add_item")
     * @param Request $request
     * @return Response
     */
    public function addItem(Request $request, EntityManagerInterface $em)
    {
        $item = new Item();
        $form = $this->createForm(ItemFormType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($item);
            $em->flush();
            $this->addFlash('info', 'Item ajouté avec succès');
            return $this->redirectToRoute("index_item");
        }

        return $this->render('item/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/edit/{id}", name="edit_item")
     */
    public function editItem(Item $item, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ItemFormType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('info', 'Item modifié avec succès');
            return $this->redirectToRoute("index_item");
        }
        
        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/delete{id}", name="delete_item", methods="DELETE")
     * @param Item $item
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteItem(Item $item, EntityManagerInterface $em, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' .$item->getId(), $request->get('_token'))) {
            $em->remove($item);
            $em->flush();
            $this->addFlash('info', 'Item supprimé avec succès');
        }
        return $this->redirectToRoute('index_item');
    }
}
