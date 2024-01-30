<?PHP /** @noinspection ALL */


class App{

    protected $controller = "home";
    protected $method = "index";
    protected $params = [];
    protected $post = [];


    function __construct(){

        //Starting session

        session_start();


        // Define variables

        $url = $this->parseUrl();
        $this->post = $this->parsePost();

        // Set configs
        if(file_exists("../app/controller/".ucfirst($url[0]).".php")){
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        }
        require_once "../app/controller/".$this->controller.".php";

        $this->controller = new $this->controller;

        if(isset($url[1])){
            if(method_exists($this->controller, $url[1])){

                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : array();
        if($this->controller->status == "private")
        {
            if ($_SERVER['REMOTE_ADDR'] != $_SERVER['SERVER_ADDR']){
                die;
            }
        }

        $requests = [
            "params" => $this->params,
            "post" => $this->post
        ];
        call_user_func_array([$this->controller, $this->method], $requests);

    }

    public function parseUrl(){
        if(isset($_GET['url'])){
            return $url = explode("/",filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }

    public function parsePost(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $data = json_decode(file_get_contents("php://input"));
            return $data;
        }
        return array("nothing");

    }
}

