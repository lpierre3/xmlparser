<?php


class APIClient
{
    const JSON = 1;
    const POST = 2;
    const GET = 3;
    const REQUEST = 4;

    private $parameters;
    private $sanitizeOptions;
    private $optionLengths;
    private $headers;

    function __construct($type = APIClient::JSON, $sanitizeOptions = null, $optionLengths = null) {
        $parameters = null;

        switch ($type) {
            case APIClient::JSON:
                $this->parameters = json_decode(file_get_contents("php://input"), true);
                break;
            case APIClient::POST:
                $this->parameters = filter_input_array(INPUT_POST);
                break;
            case APIClient::GET:
                $this->parameters = filter_input_array(INPUT_GET);
                break;
            case APIClient::REQUEST:
                $parameters = filter_input_array(INPUT_GET);
                if (isset($parameters)) {
                    $this->parameters = $parameters;
                }

                $parameters = filter_input_array(INPUT_POST);
                if (isset($parameters)) {
                    if (isset($this->parameters)) {
                        $this->parameters = array_merge($this->parameters, $parameters);
                    } else {
                        $this->parameters = $parameters;
                    }
                }
                break;
            default:
                break;
        }
        $this->sanitizeOptions = $sanitizeOptions;
        $this->optionLengths = $optionLengths;
        $this->headers = array();
    }


    public function __get($name) {
        $value = null;
        $length = 0;

        if (isset($name)) {
            if (isset($this->sanitizeOptions) && isset($this->sanitizeOptions[$name])) {
                switch ($this->sanitizeOptions[$name]) {
                    case 'string':
                        if (isset($this->optionLengths[$name])) {
                            $length = $this->optionLengths[$name];
                        }
                        $value = $this->getString($name, $length);
                        break;
                    case 'int':
                        $value = $this->getInteger($name);
                        break;
                    default:
                        $value = $this->getRawValue($name);
                        break;
                }
            } else {
                $value = $this->getRawValue($name);
            }
        }
        return $value;
    }

    private function getString($name, $length = 0) {
        $value = null;

        if (isset($name)) {
            $value = $this->getRawValue($name);
            if (isset($value)) {
                $value = trim($value);
                $value = filter_var($value, FILTER_SANITIZE_STRING);
                if (isset($value) && ($length > 0)) {
                    $value = substr($value, 0, $length);
                }
            }
        }
        return $value;
    }

    private function getRawValue($name) {
        $value = null;

        if (isset($name) && isset($this->parameters) && isset($this->parameters[$name])) {
            $value = $this->parameters[$name];
        }
        return $value;
    }

    private function getInteger($name) {
        $value = 0;

        if (isset($name)) {
            $value = trim($this->getRawValue($name));

            if (isset($value)) {
                $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                if (is_numeric($value)) {
                    $value = intval($value);
                } else {
                    $value = 0;
                }
            }
        }
        return $value;

    }

    public function sendSuccessResponse($data = null, $message = 'success') {
        return $this->sendResponse(200, $message, $data);
    }

    public function sendResponse($code, $message, $data = null) {
        $response = array();

        $response['message'] = $message;
        if (isset($data)) {
            $response = array_merge($response, $data);
        }
        header("HTTP/1.1 " . $code);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($response);
    }

    public function sendBadRequestResponse($data = null, $message = 'Bad Request') {
        return $this->sendResponse(400, $message, $data);
    }

    public function sendUnauthorizedResponse($data = null, $message = 'Unauthorized') {
        return $this->sendResponse(401, $message, $data);
    }

    public function addHeader($key, $value) {
        if (isset($this->headers) && isset($key) && isset($value)) {
            $this->headers[$key] = $value;
        }
    }

}