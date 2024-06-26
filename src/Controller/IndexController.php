<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\ModelSubProduct;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Review;
use App\Entity\SubProducts;
use App\Entity\User;
use App\Repository\ReviewRepository;
use App\Utils\Cart;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reviews = $entityManager->getRepository(Review::class)->findAll();
        $categories = $entityManager->getRepository(Category::class)->findBy(['status' => true]);
        $products = $entityManager->getRepository(Product::class)->findBy(['status' => true]);
        $subProducts = $entityManager->getRepository(SubProducts::class)->findBy(['status' => true]);
        $modelSubProducts = $entityManager->getRepository(ModelSubProduct::class)->findBy(['status' => true]);

        if ($this->getUser() !== null){
            $auth = true;
        } else {
            $auth = false;
        }

        return $this->render('index/index.html.twig', [
            'reviews' => $reviews,
            'categories' => $categories,
            'products' => $products,
            'auth' => $auth,
            'subProducts' => $subProducts,
            'modelSubProducts' => $modelSubProducts
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $orders = $entityManager->getRepository(Order::class)->findBy(['user_id' => $user->getId()]);
        $reviews = $entityManager->getRepository(Review::class)->findBy(['user_id' => $user->getId()]);
        $products = $entityManager->getRepository(Product::class)->findBy(['status' => true]);
        $subProducts = $entityManager->getRepository(SubProducts::class)->findBy(['status' => true]);
        $modelSubProducts = $entityManager->getRepository(ModelSubProduct::class)->findBy(['status' => true]);

        return $this->render('index/profile.html.twig',
            [
                'user' => $user,
                'orders' => $orders,
                'reviews' => $reviews,
                'products' => $products,
                'subProducts' => $subProducts,
                'modelSubProducts' => $modelSubProducts
            ]
        );
    }

    #[Route('/create-order', name: 'create_order', methods: 'post')]
    public function createOrder(Request $request, SessionInterface $session, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return new Response(json_encode([]), Response::HTTP_UNAUTHORIZED, ['Content-Type' => 'application/json']);

        }

        $params = $request->request->all();

        if (!isset($params['date']) && !isset($params['time'])) {
            return new Response(json_encode([]), Response::HTTP_NOT_ACCEPTABLE, ['Content-Type' => 'application/json']);
        }

        $cart = new Cart($session);
        $cartItems = $cart->getItems();

        if (empty($cartItems)) {
            return new Response(json_encode([]), Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        foreach ($cartItems as $key => $item) {
            $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $item->getProductId($key)]);
            $subProduct = $entityManager->getRepository(SubProducts::class)->findOneBy(['id' => $item->getSubProductId($key)]);
            $modelSubProduct = $entityManager->getRepository(ModelSubProduct::class)->findOneBy(['id' => $item->getModelSubProductId($key)]);

            $order = new Order();
            $order->setUser($this->getUser());
            $order->setProduct($product);
            $order->setSubProduct($subProduct);
            $order->setModelSubProduct($modelSubProduct);
            $date = new DateTime($params['date'] . ' ' . $params['time'] . ':00');
            $order->setOrderDate($date);
            $order->setStatus(false);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            $cart->removeItem($key);
        }

        return new Response(json_encode([]), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/update-order', name: 'update_order', methods: 'post')]
    public function updateOrder(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        $params = $request->request->all();

        if ($params['orderId'] && $params['productId'] && $params['subProductId'] && $params['orderDate'] && $this->getUser()) {
            $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $params['productId']]);
            $subProduct = $entityManager->getRepository(SubProducts::class)->findOneBy(['id' => $params['subProductId']]);
            $modelSubProduct = $entityManager->getRepository(ModelSubProduct::class)->findOneBy(['id' => $params['modelSubProductId']]);

            $order = $entityManager->getRepository(Order::class)->findOneBy(['id' => $params['orderId']]);
            $order->setProduct($product);
            $order->setSubProduct($subProduct);
            $order->setModelSubProduct($modelSubProduct);
            $date = new DateTime($params['orderDate'] . ' ' . $params['orderTime'] . ':00');
            $order->setOrderDate($date);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($order);
            $entityManager->flush();
        }

        return $this->redirect('/');
    }

    #[Route('/update-user', name: 'update_user', methods: 'post')]
    public function updateUser(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        $params = $request->request->all();

        $user = $this->getUser();

        if (!empty($params['fio'])){
            $user->setFio($params['fio']);
        }

        if (!empty($params['email'])){
            $user->setEmail($params['email']);
        }

        if (!empty($params['phoneNumber'])){
            $user->setPhoneNumber($params['phoneNumber']);
        }

        if (!empty($params['password'])){
            $user->setPassword($userPasswordHasher->hashPassword(
                $user,
                $params['password']
            ));
        }

        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response(json_encode(['data' => 'success']), Response::HTTP_OK, ['Content-Type' => 'application/json']);

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

    #[Route('/frequent-questions', name: 'frequent_questions', methods: 'get')]
    public function questions()
    {
        if ($this->getUser() !== null){
            $auth = true;
        } else {
            $auth = false;
        }

        return $this->render('index/questions.html.twig', [
            'auth' => $auth
        ]);
    }

    #[Route('/category/{id}', name: 'category', methods: 'get')]
    public function category($id, EntityManagerInterface $entityManager)
    {
        if ($this->getUser() !== null){
            $auth = true;
        } else {
            $auth = false;
        }

        $categories = $entityManager->getRepository(Category::class)->findBy(['status' => true]);
        $category = $entityManager->getRepository(Category::class)->findOneBy(['status' => true, 'id' => $id]);
        $products = $entityManager->getRepository(Product::class)->findBy(['status' => true, 'category_id' => $id]);
        $subProducts = $entityManager->getRepository(SubProducts::class)->findBy(['status' => true]);
        $modelSubProducts = $entityManager->getRepository(ModelSubProduct::class)->findBy(['status' => true]);

        return $this->render('index/category.html.twig', [
            'auth' => $auth,
            'products' => $products,
            'categories' => $categories,
            'category' => $category,
            'subProducts' => $subProducts,
            'modelSubProducts' => $modelSubProducts
        ]);
    }

    #[Route('/product/{id}', name: 'product', methods: 'get')]
    public function product($id, EntityManagerInterface $entityManager)
    {
        if ($this->getUser() !== null){
            $auth = true;
        } else {
            $auth = false;
        }

        $categories = $entityManager->getRepository(Category::class)->findBy(['status' => true]);
        $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);
        $subProducts = $entityManager->getRepository(SubProducts::class)->findBy(['status' => true]);
        $modelSubProducts = $entityManager->getRepository(ModelSubProduct::class)->findBy(['status' => true]);


        return $this->render('index/product.html.twig', [
            'auth' => $auth,
            'product' => $product,
            'categories' => $categories,
            'subProducts' => $subProducts,
            'modelSubProducts' => $modelSubProducts

        ]);
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

    #[Route('/get-time-slots', name: 'get_time_slots', methods: ['GET'])]
    public function getTimeSlots(EntityManagerInterface $entityManager): Response
    {
        $orders = $entityManager->getRepository(Order::class)->findBy(['status' => false]);
        $occupiedDates = [];
        $workStartHour = 8;
        $workEndHour = 18;

        foreach ($orders as $order) {
            $orderTime = $order->getOrderDate();
            $executionTime = $order->getSubProduct()->getExecution();
            $currentStartTime = clone $orderTime;
            $remainingExecutionTime = $executionTime;

            while ($remainingExecutionTime > 0) {
                $startDate = $currentStartTime->format('Y-m-d');
                $startHour = (int)$currentStartTime->format('H');

                if ($startHour < $workStartHour) {
                    $currentStartTime->setTime($workStartHour, 0);
                }

                $workingDayEnd = (clone $currentStartTime)->setTime($workEndHour, 0);
                $endTime = (clone $currentStartTime)->modify("+{$remainingExecutionTime} hours");

                if ($endTime > $workingDayEnd) {
                    $endTime = $workingDayEnd;
                }

                $endFormattedTime = $endTime->format('H:i');
                $startFormattedTime = $currentStartTime->format('H:i');

                if (!isset($occupiedDates[$startDate])) {
                    $occupiedDates[$startDate] = [];
                }

                $occupiedDates[$startDate][] = [
                    'start' => $startFormattedTime,
                    'end' => $endFormattedTime,
                ];

                $hoursWorked = ($endTime->getTimestamp() - $currentStartTime->getTimestamp()) / 3600;
                $remainingExecutionTime -= $hoursWorked;

                $currentStartTime = (clone $workingDayEnd)->modify("+14 hours");
            }
        }

        return new Response(json_encode($occupiedDates), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/search-products', name: 'search-products', methods: ['POST'])]
    public function searchProducts(Request $request, EntityManagerInterface $entityManager)
    {
        $searchWord = $request->get('word');
        $searchPattern = '%' . $searchWord . '%';
        $result = [];
        $products = $entityManager->getRepository(Product::class)->createQueryBuilder('o')
            ->andWhere('o.name LIKE :searchWord')
            ->setParameter('searchWord', $searchPattern)
            ->getQuery()
            ->getResult();

        foreach ($products as $product) {
            $result[] = [
                'id' => $product->getId(),
                'name' => $product->getName()
            ];
        }

        return new JsonResponse(['items' => $result]);
    }


}
