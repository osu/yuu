<?php

class Router
{
    private $array;
    public $request;
    public $args;
    public $get;

    public function __construct($get_param, $default_page, $redirect = false)
    {
        if(!isset($_GET[$get_param])){
            if($redirect == true){
                header("Location: /$default_page");
                exit;
            }else{
                $this->request = $default_page;
                $this->get = $_SERVER["REQUEST_URI"];
                preg_match_all('/([^?&=#]+)=([^&#]*)/',$this->get,$m);
                $this->get = array_combine( $m[1], $m[2]);
            }
        }else{
            $this->array = explode("/", $_GET[$get_param]);
            array_shift($this->array, [""]);
            $this->request = $this->array[0];
            $this->args = array_values(array_diff_key($this->array, [0 => $this->request]));
            $this->get = str_replace($_GET[$get_param], "", $_SERVER["REQUEST_URI"]);
            preg_match_all('/([^?&=#]+)=([^&#]*)/',$this->get,$m);
            $this->get = array_combine( $m[1], $m[2]);
        }
    }
}
