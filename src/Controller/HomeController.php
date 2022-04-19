<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Form\SearchType;
use App\Service\Service;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{

    private Service $service;
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;

    public function __construct(CategoryRepository $categoryRepository,ProductRepository $productRepository,Service $service) {
      $this->categoryRepository=$categoryRepository;
      $this->productRepository=$productRepository;
      $this->service = $service;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $category=$this->categoryRepository->findAll();
        $panierWithData = $this->service->getFullCart();
        $product=$this->productRepository->findBy(['category'=>1],['libelle'=>'ASC'],3);
        $food=$this->productRepository->findBy(['category'=>4]);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Home',
            'category'=>array_map(function (Category $categories) {
                return $categories->tojson();
            }, $category),
            'product'=>array_map(function (Product $products) {
                return $products->tojson();
            }, $product),
            'food'=>array_map(function (Product $products) {
                return $products->tojson();
            }, $food),
            'panier'=>$panierWithData
        ]);

    }
    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        $panierWithData = $this->service->getFullCart();
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'Contact',
            'panier'=>$panierWithData,
        ]);
    }
    #[Route('/cart', name: 'app_cart')]
    public function cart(): Response
    {
        $panierWithData = $this->service->getFullCart();
        $total = $this->service->getTotal();
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'Cart',
            'panier'=>$panierWithData,
            'total'=>$total
        ]);
    }

    #[Route('/category/{id}', name: 'app_category')]
    public function category($id,Request $request): Response
    {
        $panierWithData = $this->service->getFullCart();
        $form = $this->createForm(SearchType::class);
        $category=$this->productRepository->findBy(['category'=>$id]);
        if(!$category){
            return $this->redirectToRoute('app_home');
        }
        $productData=[];

        if($form->handleRequest($request)->isSubmitted()){
            $criteria=$form->get('searchInput')->getData();    
            $productData=$this->productRepository->search($criteria,$id);
        }else{
            $productData=array_map(function (Product $products) {
                return $products->tojson();
            }, $category);
        }
        return $this->render('category/index.html.twig', [
            'controller_name' => 'Category',
            'products'=>$productData,
            'form'=>$form->createView(),
            'panier'=>$panierWithData
        ]);
    }

    #[Route('/addCart/{id}', name: 'app_addCart')]
    public function addCart($id): Response
    {
        $this->service->add($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/removeCart/{id}', name: 'app_removeCart')]
    public function removeCart($id): Response
    {
        $this->service->remove($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/decrementCart/{id}', name: 'app_decrementCart')]
    public function decrementCart($id): Response
    {
        $this->service->decrement($id);
        return $this->redirectToRoute('app_cart');
    }


}
