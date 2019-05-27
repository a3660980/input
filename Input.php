<?php 
class Input extends CI_Controller
{
    const InputClassPrefix = 'Dal\Input';

    public function __construct()
    {
        parent::__construct();
        include_once 'Dal/inc.php';
        

        $inputClassName = self::InputClassPrefix . '\\' . $this->_getSubNamespace();
        $this->setDalInput($inputClassName);
    }

    
    private function _getSubNamespace()
    {
        $controller = $this->uri->segment(1);
        $controller = empty($controller) ? $this->router->default_controller : $controller;
        $function = $this->uri->segment(2);
        $function = empty($function) ? 'index' : $function;

        return ucfirst($controller) . '\\' . ucfirst($function);
    }

    protected function setDalInput($inputClassName)
    {
        $hasClass = true;
        try {
            $this->dalInput = new $inputClassName($this->input);
        } catch (\Throwable $e) {
            $hasClass = false;
        }
        if ($hasClass) {
            $contentType = empty($_SERVER['CONTENT_TYPE']) ? '' : $_SERVER['CONTENT_TYPE'];

            switch ($contentType) {
                case 'application/json':
                    $this->dalInput->setPayloadBody(json_decode(trim(file_get_contents('php://input'))));
                    break;
                default:
            }
        }

        return $this;
    }
}