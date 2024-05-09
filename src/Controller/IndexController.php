<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Review;
use App\Entity\User;
use App\Repository\ReviewRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reviews = $entityManager->getRepository(Review::class)->findAll();
        $categories = $entityManager->getRepository(Category::class)->findBy(['status' => true]);
        $products = $entityManager->getRepository(Product::class)->findBy(['status' => true]);

        if ($this->getUser() !== null){
            $auth = true;
        } else {
            $auth = false;
        }

        return $this->render('index/index.html.twig', [
            'reviews' => $reviews,
            'categories' => $categories,
            'products' => $products,
            'auth' => $auth
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $orders = $entityManager->getRepository(Order::class)->findBy(['user_id' => $user->getId()]);
        $reviews = $entityManager->getRepository(Review::class)->findBy(['user_id' => $user->getId()]);

        return $this->render('index/profile.html.twig',
            [
                'user' => $user,
                'orders' => $orders,
                'reviews' => $reviews
            ]
        );
    }

    #[Route('/create-order', name: 'create_order', methods: 'post')]
    public function createOrder(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        $params = $request->request->all();

        if ($params['order_date'] && $params['product_id'] && $this->getUser()) {
            $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $params['product_id']]);

            $order = new Order();
            $order->setUser($this->getUser());
            $order->setProduct($product);
            $date = new DateTime($params['order_date']);
            $order->setOrderDate($date);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($order);
            $entityManager->flush();
        }

        return $this->redirect('/');
    }

    #[Route('/delete-order', name: 'delete_order', methods: 'post')]
    public function deleteOrder(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        $params = $request->request->all();

        if ($params['order_id'] && $this->getUser()) {
            $order = $entityManager->getRepository(Order::class)->findOneBy(['id' => $params['order_id']]);
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirect('/profile');
    }

    #[Route('/delete-review', name: 'delete_review', methods: 'post')]
    public function deleteReview(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        $params = $request->request->all();

        if ($params['review_id'] && $this->getUser()) {
            $review = $entityManager->getRepository(Review::class)->findOneBy(['id' => $params['review_id']]);
            $entityManager->remove($review);
            $entityManager->flush();
        }

        return $this->redirect('/profile');
    }

    #[Route('/create-review', name: 'create_review', methods: 'post')]
    public function createReview(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        $params = $request->request->all();

        if ($params['title'] && $params['body'] && $this->getUser()) {
            $review = new Review();
            $review->setUser($this->getUser());
            $review->setTitle($params['title']);
            $review->setBody($params['body']);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($review);
            $entityManager->flush();
        }

        return $this->redirect('/profile');
    }

    #[Route('/error', name: 'error')]
    public function error(Request $request): Response
    {
        $error = $request->get('error');
        if ($this->getUser() !== null){
            $auth = true;
        } else {
            $auth = false;
        }

        return $this->render('index/error.html.twig',
            [
                'error' => $error,
                'auth' => $auth
            ]
        );
    }
}
