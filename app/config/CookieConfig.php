<?php

namespace  app\config;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

class CookieConfig
{
    public static int $expire_time = 3600 * 24 * 30;

    public static string $cookie_avail_path = "/";
}