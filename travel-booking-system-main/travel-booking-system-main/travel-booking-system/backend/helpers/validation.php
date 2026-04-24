<?php
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validatePassword($password) {
    return strlen($password) >= 8;
}

function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function validateBookingDates($checkIn, $checkOut) {
    if (!validateDate($checkIn) || !validateDate($checkOut)) return false;
    $today = new DateTime();
    $today->setTime(0, 0, 0);
    $in = new DateTime($checkIn);
    $out = new DateTime($checkOut);
    return $in >= $today && $out > $in;
}

function validateRequired($fields, $data) {
    $errors = [];
    foreach ($fields as $field) {
        if (empty($data[$field])) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
        }
    }
    return $errors;
}
