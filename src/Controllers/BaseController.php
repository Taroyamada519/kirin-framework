<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;

abstract class BaseController {
    protected $request;
    protected $response;

    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    protected function validate($data, $rules) {
        $errors = [];

        foreach ($rules as $field => $rule) {
            if (strpos($rule, 'required') !== false && (!isset($data[$field]) || empty($data[$field]))) {
                $errors[$field] = "{$field} is required";
                continue;
            }

            if (!isset($data[$field])) {
                continue;
            }

            if (strpos($rule, 'email') !== false && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "{$field} must be a valid email address";
            }

            if (strpos($rule, 'min:') !== false) {
                preg_match('/min:(\d+)/', $rule, $matches);
                $min = (int)$matches[1];
                if (strlen($data[$field]) < $min) {
                    $errors[$field] = "{$field} must be at least {$min} characters";
                }
            }

            if (strpos($rule, 'max:') !== false) {
                preg_match('/max:(\d+)/', $rule, $matches);
                $max = (int)$matches[1];
                if (strlen($data[$field]) > $max) {
                    $errors[$field] = "{$field} must not exceed {$max} characters";
                }
            }
        }

        return $errors;
    }
}
