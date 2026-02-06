<?php
class Security {
    public static function sanitizeId($id) {
        return filter_var($id, FILTER_VALIDATE_INT);
    }

    public static function sanitizeString($str) {
        return htmlspecialchars(strip_tags($str), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valida un código de préstamo en formato P_[numero]
     * - Acepta P_ seguido de uno o más dígitos.
     * - Retorna el número (como int) si es válido, o false si no.
     *
     * Ejemplos válidos: P_1, P_000123, P_42
     */
    public static function validateLoanCode(string $code) {
        $code = trim($code);
        if (preg_match('/^P_(\d+)$/', $code, $matches)) {
            // Devuelve el número como entero (elimna ceros a la izquierda)
            return intval($matches[1], 10);
        }
        return false;
    }

    /**
     * Valida una cédula con guiones en el formato:
     *  - 3 dígitos - 6 dígitos - 4 dígitos + 1 letra final
     * Ejemplo: 291-190299-0011X
     *
     * Retorna la cédula normalizada (mayúsculas, sin espacios extra) si es válida,
     * o false si no.
     */
    public static function validateCedula(string $cedula) {
        $cedula = strtoupper(trim($cedula));
        // eliminar espacios internos accidentaless (si los hubiese)
        $cedula = preg_replace('/\s+/', '', $cedula);

        // Regex: ^\d{3}-\d{6}-\d{4}[A-Z]$
        if (preg_match('/^\d{3}-\d{6}-\d{4}[A-Z]$/', $cedula)) {
            return $cedula;
        }
        return false;
    }
}
