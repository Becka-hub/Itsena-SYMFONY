<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PaymentType;
use App\Entity\Order;
use App\Service\Service;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderRepository;

class PaymentController extends AbstractController
{
    
    private Service $service;
    private EntityManagerInterface $entityManager;
    private FlashBagInterface $flashMessage;
    private OrderRepository $orderRepository;

    public function __construct(Service $service,EntityManagerInterface $entityManager,FlashBagInterface $flashMessage,OrderRepository $orderRepository) {
       $this->entityManager = $entityManager;
       $this->flashMessage = $flashMessage;
       $this->service = $service;
       $this->orderRepository=$orderRepository;
    }


    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    {
        $panierWithData = $this->service->getFullCart();

        $order=$this->orderRepository->findBy(['user'=> $this->getUser()->getId()]);

        $total = $this->service->getTotal();
        $form = $this->createForm(PaymentType::class);

        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
            'orderForm'=>$form->createView(),
            'panier'=> $panierWithData,
            'total'=>$total,
            'order'=>array_map(function (Order $orders) {
                return $orders->tojson();
            }, $order)
        ]);
    }

    #[Route('/pay', name: 'app_pay')]
    public function pay(Request $request): Response
    {
        $panierWithData = $this->service->getFullCart();
        $total = $this->service->getTotal();

        if(count($panierWithData) === 0){
            $this->flashMessage->add("error", "Your Cart is Empty !!!");
            return $this->redirectToRoute('app_payment');
        }
        $achat='';
        foreach($panierWithData as $panier){
            $qty=$panier['product']->getPrice() * $panier['quantity'];
            $achat .=$panier['product']->getLibelle()."(".$panier['quantity'].") ".$panier['quantity']."x".$panier['product']->getPrice()." = $".$qty." , ";
        }

        $country = $request->request->get('payment')['country'];
        $city = $request->request->get('payment')['city'];
        $adress = $request->request->get('payment')['adress'];

        $order= new Order();
        $order->setShopping($achat);
        $order->setTotalPrice($total);
        $order->setCountry($country);
        $order->setCity($city);
        $order->setAdressDevivery($adress);
        $order->setUser($this->getUser());
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $this->service->emptyCart();

        $this->flashMessage->add("success", "Payment success");
        return $this->redirectToRoute('app_payment');
    }

    #[Route('/deleteOrder/{id}', name: 'app_deleteOrder')]
    public function deleteOrder($id): Response
    {
       $order=$this->orderRepository->findOneBy(['id'=>$id,'user'=>$this->getUser()->getId()]);
       $this->entityManager->remove($order);
       $this->entityManager->flush();
       $this->flashMessage->add("success", "Deleted order success");
       return $this->redirectToRoute('app_payment');
    }

}
