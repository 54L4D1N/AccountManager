<?php

/**
 * \brief gets a random generated passord
 * \param chars the password length
 * \return string the password
 */
function getRandomPassword(int $chars = 8)
{
    $data = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`-=~!@#$%^&*()_+,./<>?;:[]{}\|'\"";

    $password = substr(str_shuffle($data), 0, $chars);
    while (!preg_match('/(?=^.{'.$chars.',255}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $password)) {
        $password = substr(str_shuffle($data), 0, $chars);
    }
    return $password;
}