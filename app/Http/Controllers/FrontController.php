<?php

namespace App\Http\Controllers;

use App\Contants\Main;
use App\Models\Order;
use App\Models\OrderData;
use App\Models\OrderProduct;
use App\Repositories\CategoryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FrontController extends Controller
{

    public function index(
        CategoryRepository $categoryRepository
    ) {
        $categories = $categoryRepository->getByCategoryId();

        return view('front.welcome', array(
            'categories'    => $categories
        ));
    }

    public function category(
        $id,
        CategoryRepository $categoryRepository
    ) {
        $category = $categoryRepository->find($id);
        $categories = $categoryRepository->getByCategoryId();

        return view('front.category', array(
            'category'      => $category,
            'products'      => $category->products()->paginate(15),
            'categories'    => $categories
        ));
    }

    public function product(
        $id,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ) {
        $product = $productRepository->find($id);
        $categories = $categoryRepository->getByCategoryId();

        return view('front.product', array(
            'product'       => $product,
            'categories'    => $categories,
            'units'         => Main::UNITS
        ));
    }

    public function addToCart(
        Request $request
    ) {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $cart = session('cart', array());
        $cart[$productId] = $quantity;

        session(array(
            'cart'  => $cart
        ));

        return redirect()->back()->with('success', 'Produkt dodano do koszyka');
    }

    public function removeFromCart(
        $id
    ) {
        $cart = session('cart', array());
        unset($cart[$id]);

        session(array(
            'cart'  => $cart
        ));

        return redirect()->back()->with('success', 'Usunięto produkt z koszyka');
    }

    public function cart(
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository
    ) {
        $categories = $categoryRepository->getByCategoryId();
        $cart = session('cart', array());
        $ids = array_keys($cart);

        $products = count($ids) > 0 ? $productRepository->findByIds($ids) : array();

        return view('front.cart', array(
            'categories'    => $categories,
            'count'         => count($cart),
            'products'      => $products,
            'cart'          => $cart,
            'units'         => Main::UNITS
        ));
    }

    public function order(
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository
    ) {
        $categories = $categoryRepository->getByCategoryId();
        $cart = session('cart', array());
        $ids = array_keys($cart);

        $products = count($ids) > 0 ? $productRepository->findByIds($ids) : array();

        return view('front.order', array(
            'categories'    => $categories,
            'count'         => count($cart),
            'products'      => $products,
            'cart'          => $cart
        ));
    }

    public function saveOrder(
        Request $request
    ) {
        $order = new Order();
        $order->save();
        $orderId = $order->id;

        $cart = session('cart', array());

        foreach($cart as $productId => $quantity) {
            OrderProduct::create(array(
                'order_id'      => $orderId,
                'product_id'    => $productId,
                'quantity'      => $quantity
            ));
        }

        $data = $request->all();
        OrderData::create(array_merge(
            $data, array(
                'order_id'  => $orderId
            )
        ));

        session(array(
            'cart'  => array()
        ));

        return redirect('/summary/' . $orderId);
    }

    public function summary(
        $id,
        OrderRepository $orderRepository,
        CategoryRepository $categoryRepository
    ) {
        $order = $orderRepository->find($id);
        $categories = $categoryRepository->getByCategoryId();
        $allCategories = $categoryRepository->getAll(array('id', 'name'));

        return view('front.summary', array(
            'order'         => $order,
            'categories'    => $categories,
            'allCategories' => $allCategories,
            'units'         => Main::UNITS
        ));
    }

    public function file($filename)
    {
        return Storage::download('/files/' . $filename);
    }
}
