<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Service\Service;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class FullController extends AbstractController
{

    private Service $service;
    private EntityManagerInterface $entityManager;
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;
    public function __construct(Service $service,EntityManagerInterface $entityManager,CategoryRepository $categoryRepository,ProductRepository $productRepository)
    {
        $this->service = $service;
        $this->entityManager=$entityManager;
        $this->categoryRepository=$categoryRepository;
        $this->productRepository=$productRepository;
    }

    #[Route('/addCategory', name: 'addCategory',methods:'POST')]
    public function addCategory(Request $request): Response
    {
       $libelle=$request->request->get('libelle');
       $image=$request->files->get('image');

       if(!isset($libelle,$image) || ($libelle==="" || $image==="")){
           return $this->json("Form Invalid",400);
       }

       $category = $this->categoryRepository->findOneBy(['libelle' => $libelle]);
       if ($category) {
        return $this->json("Category exist",400);
       }
       $extension = $image->guessExtension();
       
       if (isset($extension) && ($extension !== "png" && $extension !== "jpg" && $extension !== "jpeg")) {

        return $this->json("Invalid format file! Format photo accepted png, jpg, jpeg",400);
        }
       
        $photoName = $this->service->uploadFile($image, $this->getParameter('category_directory'));

        $categorie= new Category();
        $categorie->setLibelle($libelle);
        $categorie->setImage($photoName);
        $this->entityManager->persist($categorie);
        $this->entityManager->flush();
        return $this->json("add category success!!!",400);
}


#[Route('/addProduct', name: 'addProduct',methods:'POST')]
public function addProduct(Request $request): Response
{
   $libelle=$request->request->get('libelle');
   $price=$request->request->get('price');
   $image=$request->files->get('image');
   $category=$request->request->get('category');

   if(!isset($libelle,$price,$image,$category) || ($libelle==="" || $image==="" || $price==="" || $category==="")){
       return $this->json("Form Invalid",400);
   }

   $product = $this->productRepository->findOneBy(['libelle' => $libelle]);
   if ($product) {
    return $this->json("Category exist",400);
   }

   $categories = $this->categoryRepository->findOneBy(['id' => $category]);

   $extension = $image->guessExtension();
   
   if (isset($extension) && ($extension !== "png" && $extension !== "jpg" && $extension !== "jpeg")) {

    return $this->json("Invalid format file! Format photo accepted png, jpg, jpeg",400);
    }
   
    $photoName = $this->service->uploadFile($image, $this->getParameter('product_directory'));

    $products= new Product();
    $products->setLibelle($libelle);
    $products->setPrice($price);
    $products->setCategory($categories);
    $products->setImage($photoName);
    $this->entityManager->persist($products);
    $this->entityManager->flush();
    return $this->json("add product success!!!",400);
}


}