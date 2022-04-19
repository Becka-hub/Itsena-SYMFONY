<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FilterType;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\Service;

class ProductController extends AbstractController
{

    private ProductRepository $productRepository;
    private Service $service;

    public function __construct(ProductRepository $productRepository,Service $service) {

      $this->productRepository=$productRepository;
      $this->service = $service;
    }
    #[Route('/product', name: 'app_product')]
    public function index(Request $request,PaginatorInterface $paginator): Response
    {
        $panierWithData = $this->service->getFullCart();
        $product=$this->productRepository->findBy([],['id'=>'DESC']);

        $filterForm=$this->createForm(FilterType::class);

        $data=[];

        if($filterForm->handleRequest($request)->isSubmitted()){
            $id=$filterForm->get('category')->getData();
            $min= $filterForm->get('minimumPrice')->getData();    
            $max= $filterForm->get('maximumPrice')->getData();
            $name= $filterForm->get('product')->getData();
            $dataFilter=$this->productRepository->searchCart($id,$min,$max,$name);
            $data=$this->productRepository->searchCart($id,$min,$max,$name);
        }else{
            $data=array_map(function (Product $products) {
                return $products->tojson();
            }, $product);
        }

        $dataProduct = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );



        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'filter_Form'=> $filterForm->createView(),
            'products'=>$dataProduct,
            'panier'=> $panierWithData 
        ]);
    }
}
