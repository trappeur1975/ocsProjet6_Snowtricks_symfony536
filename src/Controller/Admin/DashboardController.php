<?php

namespace App\Controller\Admin;

use App\Entity\Pool;
use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\Picture;
use App\Repository\PoolRepository;
use App\Repository\UserRepository;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
// use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
// use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    protected $userRepository;
    protected $trickRepository;
    protected $poolRepository;

    public function __construct(UserRepository $userRepository, TrickRepository $trickRepository, PoolRepository $poolRepository)
    {
        $this->userRepository = $userRepository;
        $this->trickRepository = $trickRepository;
        $this->poolRepository = $poolRepository;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('bundles/easyAdminBundle/dashboard.html.twig', [
            'countAllUser' => $this->userRepository->countAllUser(),
            'countAllTrick' => $this->trickRepository->countAllTrick(),
            'pools' => $this->poolRepository->findAll()
        ]);
        // return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dashboard Snowtricks');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'icon class', EntityClass::class);
        yield MenuItem::linktoRoute('Website frontSide', 'fas fa-laptop-house', 'home');
        yield MenuItem::linkToCrud('Tricks', 'fas fa-snowboarding', Trick::class);
        yield MenuItem::linkToCrud('Pool', 'far fa-snowflake', Pool::class);
        yield MenuItem::linkToCrud('Video', 'far fa-snowflake', Video::class, null);
        yield MenuItem::linkToCrud('Pictures', 'far fa-snowflake', Picture::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToLogout('Logout', 'fa fa-sign-out');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {

        return parent::configureUserMenu($user)
            ->setName($user->getUserIdentifier())
            ->setAvatarUrl('https://cdn.pixabay.com/photo/2016/08/18/11/00/man-1602633_960_720.png/')
            // ->setGravatarEmail($user->getUsername())
            ->displayUserAvatar(true);
    }
}
