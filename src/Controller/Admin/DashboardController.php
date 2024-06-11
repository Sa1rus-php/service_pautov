<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\Category;
use App\Entity\ModelSubProduct;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Review;
use App\Entity\SubProducts;
use App\Entity\SubProductsModel;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(UserCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Сервіс');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Особи');
        yield MenuItem::linkToCrud('Користувачі', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Адміни', 'fa fa-user-circle-o', Admin::class);
        yield MenuItem::section('Товари та Категорії');
        yield MenuItem::linkToCrud('Категорії', 'fa fa-file-o', Category::class);
        yield MenuItem::linkToCrud('Продукти', 'fa fa-archive', Product::class);
        yield MenuItem::linkToCrud('Під продукти', 'fa fa-archive', SubProducts::class);
        yield MenuItem::linkToCrud('Моделі під продуктів', 'fa fa-archive', ModelSubProduct::class);
        yield MenuItem::section('Замовлення');
        yield MenuItem::linkToCrud('Замовлення', 'fa fa-credit-card-alt', Order::class);
        yield MenuItem::section('Відгуки');
        yield MenuItem::linkToCrud('Відгуки', 'fa fa-commenting-o', Review::class);
    }
}
