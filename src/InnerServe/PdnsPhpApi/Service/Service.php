<?php

namespace InnerServe\PdnsPhpApi\Service;

class Service {
    private $pdo;

    /**
     * @param \PDO $pdo
     */
    public function setPdo($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    public function __construct(\PDO $pdo) {
        $this->setPdo($pdo);
    }


}