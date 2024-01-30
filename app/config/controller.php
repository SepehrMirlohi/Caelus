<?PHP 


class Controller{

    // function __construct(){
    //     return;
    // }
    public function model($model){
        require_once "../app/models/".$model.".php";
        return new $model();
    }

    public function view($view, $data = null): void{
        require_once "../app/view/".$view.".php";
    }
}