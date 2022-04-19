<?php
namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\ProductRepository;
class Service extends AbstractController
{
    private SluggerInterface $slugger;
    private ProductRepository $product;
    private RequestStack $session;
     
    public function __construct( SluggerInterface $slugger,ProductRepository $product ,RequestStack $session)
    {
        $this->slugger=$slugger;
        $this->product=$product;
        $this->session=$session;
    }
   
    public function uploadFile($data, $directory)
    {
        $originalImageName = pathinfo($data->getClientOriginalName(), PATHINFO_FILENAME);
        $safeImageName = $this->slugger->slug($originalImageName);
        $imageName = $safeImageName . '-' . uniqid() . '.' . $data->guessExtension();

        try {
            $data->move(
                $directory,
                $imageName
            );
            return $imageName;
        } catch (FileException $e) {
        }
    }



    public function add(int $id)
    {
        $session = $this->session->getSession();
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        $session->set('panier', $panier);
    }
    public function remove(int $id)
    {
        $session = $this->session->getSession();
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $session->set('panier', $panier);
    }
    
    public function decrement(int $id)
    {
        $session = $this->session->getSession();
        $panier = $session->get('panier', []);

        if (!empty($panier[$id]) && $panier[$id] !== 1) {
            $panier[$id]--;
        } else {
            unset($panier[$id]);
        }
        $session->set('panier', $panier);
    }

    public function getFullCart(): array
    {
        $session = $this->session->getSession();
        $panier = $session->get('panier', []);
        $panierWithData = [];
        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'product' => $this->product->find($id),
                'quantity' => $quantity
            ];
        }

        return $panierWithData;
    }

    public function getTotal(): float
    {
        $total = 0;
        $panierWithData = $this->getFullCart();
        foreach ($panierWithData as $item) {
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }
        return $total;
    }

    public function emptyCart()
    {
        $session = $this->session->getSession();
        $session->set('panier', []);
    }

}