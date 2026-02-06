<?php
class Auth {
    private static $API_KEY = "lAtZ3ecapzlO5IKMPwEoP4YCZb7iUojOwABVPpiD8HdiZY3i9mO1z8fjbup3LUbF"; // cámbialo y guárdalo en config

    public static function check() {
        // Obtener todos los headers
        $headers = function_exists("getallheaders") ? getallheaders() : [];

        // Normalizar a minúsculas
        $headers = array_change_key_case($headers, CASE_LOWER);

        // Fallback por si no aparece Authorization
        if (!isset($headers["authorization"]) && isset($_SERVER["HTTP_AUTHORIZATION"])) {
            $headers["authorization"] = $_SERVER["HTTP_AUTHORIZATION"];
        }

        if (!isset($headers["authorization"])) {
            http_response_code(401);
            echo json_encode(["error" => "Falta token"]);
            exit;
        }

        $authHeader = $headers["authorization"];

        // Esperamos "Bearer TOKEN"
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
            if ($token !== self::$API_KEY) {
                http_response_code(403);
                echo json_encode(["error" => "Token inválido"]);
                exit;
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Formato de token incorrecto"]);
            exit;
        }
    }
}
