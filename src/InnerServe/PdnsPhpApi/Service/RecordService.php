<?php
namespace InnerServe\PdnsPhpApi\Service;

class RecordService extends Service
{
    private $domain_service = null;

    public function __construct(\PDO $pdo) {
        parent::__construct($pdo);

        $this->domain_service = new DomainService($pdo);
    }

    /**
     * Get record information by id
     *
     * @param $name
     * @return bool|array
     */
    public function get($id)
    {
        $stmt = $this->getPdo()->prepare("SELECT `id`, `name`, `type`, `content`, `ttl`, `prio` FROM records WHERE id = :id");
        $stmt->execute(array('id' => $id));
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!$data) {
            return false;
        }

        return $data;
    }

    /**
     * Get record information by name
     *
     * @param $name
     * @return bool|array
     */
    public function getByName($name)
    {
        $stmt = $this->getPdo()->prepare("SELECT `name`, `type`, `content`, `ttl`, `prio` FROM records WHERE name = :name");
        $stmt->execute(array('name' => $name));
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!$data) {
            return false;
        }

        return $data;
    }

    /**
     * Get Records for domain by type
     * @param $domain
     * @param $type
     * @return array|bool
     * @throws \Exception
     */
    public function getByType($domain, $type) {

        if ( !($domain_id = $this->domain_service->getIdForDomain($domain) ) ) {
            throw new \Exception("Domain Not Found");
        }

        $stmt = $this->getPdo()->prepare("SELECT `name`, `type`, `content`, `ttl`, `prio` FROM records WHERE domain_id = :domain_id AND type = :type");
        $stmt->execute(array('domain_id' => $domain_id, 'type' => $type));
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!$data) {
            return false;
        }

        return $data;
    }

    public function create($domain, $name, $type, $content, $ttl = 3600, $prio = null)
    {
        if ( !($domain_id = $this->domain_service->getIdForDomain($domain) ) ) {
            throw new \Exception("Domain not found");
        }

        $type = strtoupper($type);

        //Todo: Add better error checking
        if ( empty($name) ) {
            throw new \Exception("Name parameter is required");
        } elseif ( empty($type) ) {
            throw new \Exception("Type parameter is required");
        } elseif ( empty($content) ) {
            throw new \Exception("Content parameter is required");
        }

        $stmt = $this->getPdo()->prepare("INSERT INTO records (`domain_id`, `name`, `type`, `content`, `ttl`, `prio` ) VALUES (:domain_id, :name, :type, :content, :ttl, :prio)");
        $stmt->execute(array('domain_id' => $domain_id, 'name' => $name, 'type' => $type, 'content' => $content, 'ttl' => $ttl, 'prio' => $prio));

        return $this->get($this->getPdo()->lastInsertId());
    }

    public function exists($id)
    {
        $stmt = $this->getPdo()->prepare("SELECT COUNT(id) FROM records WHERE name = :id");
        $stmt->execute(array('domain' => $id));
        $data = $stmt->fetch(\PDO::FETCH_NUM);

        return ($data[0] > 0) ? true : false;
    }

    public function update($id, $name, $type, $content, $ttl = 3600, $prio = null)
    {
        if ( !$this->exists($id) ) {
            throw new \Exception("Record not found");
        }

        $type = strtoupper($type);

        //Todo: Add error checking
        if ( empty($name) ) {
            throw new \Exception("Name parameter is required");
        } elseif ( empty($type) ) {
            throw new \Exception("Type parameter is required");
        } elseif ( empty($content) ) {
            throw new \Exception("Content parameter is required");
        }

        $stmt = $this->getPdo()->prepare("UPDATE records SET `name` = :name, `type` = :type, `content` = :content, `ttl` = :ttl, `prio` = :prio WHERE id = :id");
        $stmt->execute(array('id' => $id, 'name' => $name, 'type' => $type, 'content' => $content, 'ttl' => $ttl, 'prio' => $prio));

        return $this->get($id);
    }

    public function delete($id)
    {
        if ( !$this->exists($id) ) {
            throw new \Exception("Record not found");
        }

        $stmt = $this->getPdo()->prepare("DELETE FROM records WHERE id = :id");
        $stmt->execute(array('id' => $id));

        return true;
    }
}