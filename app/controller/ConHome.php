<?php

// Index method in every controller has set as a default method.
// When someone try to access the controller without any other methods Index will be appeared.

class ConHome extends Controller {
    private mixed $product;
    public string $status = "public";
    private mixed $user;
    public function __construct()
    {
        $this->user = $this->model("User");
        $this->product = $this->model("Product");
    }
    public function Index($params, $post){
        $products = $this->product->getProducts();

//        $Products = [];
//        if (!is_object($Product)){
//            $Products = array_slice($Product->getProducts(), 0, 6);
//        }


        $this->view("/home/index", ["user" => $_SESSION['user'], "product" => $products]);
    }
}

