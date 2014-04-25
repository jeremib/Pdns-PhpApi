<?php
namespace InnerServe\PdnsPhpApi\Service;

class DomainService extends Service
{

    /**
     * Returns all domains, but does not return all records for each domain
     * @return array
     */
    public function getAll() {
        $stmt = $this->getPdo()->prepare("SELECT id, `name`, `type` FROM domains");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get domain information with all records and return as an associate array
     *
     * @param $domain
     * @return bool|array
     */
    public function get($domain)
    {
        $stmt = $this->getPdo()->prepare("SELECT id, `name`, `type` FROM domains WHERE name = :domain");
        $stmt->execute(array('domain' => $domain));
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            return false;
        }

        $stmt = $this->getPdo()->prepare("SELECT `name`, `type`, `content`, `ttl`, `prio`, `change_date` FROM records WHERE domain_id = :domain_id");
        $stmt->execute(array('domain_id' => $data['id']));
        $data['details'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $data;
    }

    public function create($domain, $type = 'MASTER', $master = null)
    {
        if ($this->exists($domain)) {
            throw new \Exception("Domain Exists");
        }

        $type = strtoupper($type);

        if ($type == 'SLAVE' && empty($master)) {
            throw new \Exception("Master parameter required for SLAVE domains.");
        }

        $stmt = $this->getPdo()->prepare("INSERT INTO domains (`name`, `type`, `master` ) VALUES (:name, :type, :master)");
        $stmt->execute(array('name' => $domain, 'type' => $type, 'master' => $master));

        return $this->get($domain);
    }

    public function update($domain_key, $type = 'MASTER', $master = null, $domain = null)
    {
        if (!$this->exists($domain_key)) {
            throw new \Exception("Domain not found");
        }

        $type = strtoupper($type);

        if (empty($domain)) {
            $domain = $domain_key;
        }

        if ($type == 'SLAVE' && empty($master)) {
            throw new \Exception("Master parameter required for SLAVE domains.");
        }

        $stmt = $this->getPdo()->prepare("UPDATE domains SET  `name` = :domain,  `type` = :type, `master` = :master WHERE `name` = :domain_key");
        $stmt->execute(array('name' => $domain, 'type' => $type, 'master' => $master, 'domain_key' => $domain_key));

        return $this->get($domain);
    }

    public function delete($domain)
    {
        if (!$this->exists($domain)) {
            throw new \Exception("Domain not found");
        }

        $domain_id = $this->getIdForDomain($domain);

        $stmt = $this->getPdo()->prepare("DELETE FROM domains WHERE name = :domain");
        $stmt->execute(array('domain' => $domain));

        $stmt = $this->getPdo()->prepare("DELETE FROM records WHERE domain_id = :domain_id");
        $stmt->execute(array('domain_id' => $domain_id));

        return true;
    }

    public function exists($domain)
    {
        $stmt = $this->getPdo()->prepare("SELECT COUNT(id) FROM domains WHERE name = :domain");
        $stmt->execute(array('domain' => $domain));
        $data = $stmt->fetch(\PDO::FETCH_NUM);

        return ($data[0] > 0) ? true : false;
    }

    public function getIdForDomain($domain) {
        $stmt = $this->getPdo()->prepare("SELECT id FROM domains WHERE name = :domain");
        $stmt->execute(array('domain' => $domain));
        $data = $stmt->fetch(\PDO::FETCH_NUM);
        return $data[0];
    }
}