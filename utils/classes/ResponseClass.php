<?php

class Response
{
    /**
     * Response type
     */

    private $type;

    /**
     * Indicates requested response type.
     *
     * @param string|null $response_type Response type
     */

    public function __construct($response_type = null)
    {
        switch ($response_type) {
            case "json":
                header("Content-Type: application/json; charset=UTF-8");
                $this->type = $response_type;
                break;
            case "text":
                header("Content-Type: text/plain; charset=UTF-8");
                $this->type = $response_type;
                break;
            default:
                header('Content-Type: text/plain; charset=UTF-8');
                $this->type = 'text';
                $this->error(400, "Invalid response type");
                break;
        }
    }

    /**
     * Show error depending on response type
     * 
     * @param int $code Error response code
     * @param string $desc Description of error
     * 
     * @return void
     */

    public function error($code, $desc)
    {
        $response = null;

        switch ($this->type) {
            case "json":
                $response = $this->jsonError($code, $desc);
                break;
            case "text":
                $response = $this->textError($code, $desc);
                break;
            default:
                $response = $this->textError($code, $desc);
                break;
        }

        http_response_code($code);
        echo $response;

    }

    /**
     * Show success depending on response type
     * 
     * @param mixed $files Files uploaded
     * 
     * @return void
     */

    public function success($files)
    {
        $response = null;

        switch ($this->type) {
            case "json":
                $response = $this->jsonSuccess($files);
                break;
            case "text":
                $response = $this->textSuccess($files);
                break;
            default:
                $response = $this->textSuccess($files);
                break;
        }

        http_response_code(200);
        echo $response;

    }

    /**
     * Error JSON format
     * 
     * @param int $code Error code
     * @param string $desc Error text
     * 
     * @return string Error json formated
     * 
     */

    private function jsonError($code, $desc)
    {
        return json_encode([
            "success" => false,
            "code" => $code,
            "error" => $desc
        ], JSON_PRETTY_PRINT);
    }

    /**
     * Success JSON format
     * 
     * @param array $files Uploaded files
     * 
     * @return string Response json formated
     * 
     */

    private function jsonSuccess($files)
    {
        return json_encode([
            "success" => true,
            "items" => $files
        ]);
    }

    /**
     * Error Text format
     * 
     * @param int $code Error code
     * @param string $desc Error text
     * 
     * @return string Error text formated
     * 
     */
    
    private function textError($code, $desc)
    {
        return "Error $code: $desc";
    }

    /**
     * Success text format
     * 
     * @param array $files Uploaded files
     * 
     * @return string Response text formated
     * 
     */

    private function textSuccess($files)
    {
        $res = "";

        foreach ($files as $file) {
            
            $res .= $file["name"]." => ".$file["url"]."\n";

        }
    }

}