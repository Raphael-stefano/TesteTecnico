<?php

namespace Testes;
use PDO;

class TestDatabase
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO{
        if (self::$pdo === null) {
            self::$pdo = new PDO('sqlite::memory:');
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::createTables();
        }
        return self::$pdo;
    }

    private static function createTables(): void{
        $pdo = self::$pdo;

        $pdo->exec("
            CREATE TABLE categorias (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT NOT NULL
            );
        ");

        $pdo->exec("
            CREATE TABLE produtos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT NOT NULL,
                quantidade INTEGER NOT NULL,
                preco INTEGER NOT NULL,
                sku TEXT,
                descricao TEXT,
                id_categoria INTEGER NOT NULL,
                FOREIGN KEY(id_categoria) REFERENCES categorias(id)
            );
        ");

        $pdo->exec("
            CREATE TABLE usuarios (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT NOT NULL,
                email TEXT NOT NULL,
                senha TEXT NOT NULL,
                nivel INTEGER NOT NULL
            );
        ");
    }
}
