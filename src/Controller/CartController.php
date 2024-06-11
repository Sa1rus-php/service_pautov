<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\ModelSubProduct;
use App\Entity\Product;
use App\Entity\SubProducts;
use App\Utils\Cart;
use App\Utils\CartItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findBy(['status' => true]);
        if ($this->getUser() !== null){
            $auth = true;
        } else {
            $auth = false;
        }

        $cart = new Cart($session);
        $cartItems = $cart->getItems();
        $totalPrice = $cart->getTotalPrice();

        $items = [];

        foreach ($cartItems as $key => $item) {
            $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $item->getProductId($key)]);
            $subProduct = $entityManager->getRepository(SubProducts::class)->findOneBy(['id' => $item->getSubProductId($key)]);
            $modelSubProduct = $entityManager->getRepository(ModelSubProduct::class)->findOneBy(['id' => $item->getModelSubProductId($key)]);
            $price = $item->getPrice($key);

            $items[] = [
                'product' => $product,
                'subProduct' => $subProduct,
                'modelSubProduct' => $modelSubProduct,
                'price' => $price,
                'key' => $key,
            ];
        }

        return $this->render('cart/index.html.twig', [
            'items' => $items,
            'totalPrice' => $totalPrice,
            'categories' => $categories,
            'auth' => 'auth'
        ]);
    }

    #[Route('/cart/add', name: 'app_cart_add', methods: ['POST'])]
    public function add(Request $request, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $productId = $request->request->get('product_id');
        $subProductId = $request->request->get('subProduct_id');
        $modelSubProductId = $request->request->get('modelSubProduct_id');
        $modelSubProduct = $entityManager->getRepository(ModelSubProduct::class)->findOneBy(['id' => $modelSubProductId]);

        $price = $modelSubProduct->getPrice();

        if (!$productId || !$subProductId || !$price || !$modelSubProductId) {
            return $this->redirectToRoute('app_cart');
        }

        $cartItem = new CartItem([
            'id' => $subProductId,
            'product_id' => $productId,
            'subProduct_id' => $subProductId,
            'modelSubProductId' => $modelSubProductId,
            'price' => $price,
        ]);


        $cart = new Cart($session);
        $cart->addItem($cartItem);


        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{key}', name: 'app_cart_remove', requirements: ['key' => '.+'])]
    public function remove(SessionInterface $session, $key): Response
    {
        $cart = new Cart($session);
        $cart->removeItem($key);

        return $this->redirectToRoute('app_cart');
    }
}
