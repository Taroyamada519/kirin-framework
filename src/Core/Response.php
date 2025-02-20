<?php
namespace App\Core;

class Response {
    public function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function error($message, $statusCode = 400) {
        $this->json([
            'error' => true,
            'message' => $message
        ], $statusCode);
    }

    public function success($data = null, $message = 'Success') {
        $response = [
            'error' => false,
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        $this->json($response);
    }

    public function notFound($message = 'Resource not found') {
        $this->error($message, 404);
    }

    public function unauthorized($message = 'Unauthorized') {
        $this->error($message, 401);
    }
}
