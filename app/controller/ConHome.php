<?php

// Index method in every controller has set as a default method.
// When someone try to access the controller without any other methods Index will be appeared.

class ConHome extends Controller {

    // status use for internal requests that server should send if it's set on "private"
    public string $status = "public";
    private mixed $data;
    public function __construct()
    {
        $this->data = $this->model("ModHome");

    }
    public function Index($params, $post){
        $user = $this->data->getData($params[0]);
        $this->view("/home/index", ["user" => $user]);
    }
}

