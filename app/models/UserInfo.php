<?php
namespace models;

use app\database\interfaces;

class UserInfo
{
    public int $uid;
    public string $userName;
    public string $userRealName;
    public array $userTelNum;
}