<?php

/**
 * Inspired by the `guard` syntax in Swift.
 * @author Ning "Alex" Kuang
 */

/**
 * If the condition is not met (false), redirect to the specified URL.
 * @param $condition
 * @param $redirect_url
 */
function guard_redirect($condition, $redirect_url) {
    if (!$condition) {
        header("Location: $redirect_url");
        die;
    }
}

/**
 * If the condition is not met (false), die with the specified message.
 * @param $condition
 * @param $message
 */
function guard_die($condition, $message) {
    if (!$condition) die($message);
}
