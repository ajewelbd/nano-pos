<?php

namespace POS\Config;

use PDO;

class Config
{
    const APPNAME           = 'Nano poS';
    const DEVELEOPER        = '<a href="https://devjewel.xyz">Md. Ashraful Alam</a>';
    const HOSTNAME          = 'localhost';
    const DBNAME            = 'nano_pos';
    const DBUSER            = 'root';
    const DBPASS            = '';
    const BASE_URL          = 'https://nanopos.devjewel.xyz';
    const IS_SECURE         = FALSE;
    const DEBUG_MODE        = TRUE;

    static public function db()
    {
        try {
            $db = new PDO('mysql:host=localhost;dbname=nano_pos', 'root', '');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            echo "Database Connection failed: " . $e->getMessage();
        }
    }
}
