<?php
namespace InnerServe\PdnsPhpApi\Service;

class DomainService extends Service {

    /**
     * Get domain information with all records and return as an associate array
     *
     * @param $domain
     * @return bool|array
     */
    public function get($domain) {
        $stmt = $this->getPdo()->prepare("SELECT id, `name`, `type` FROM domains WHERE name = :domain");
        $stmt->execute(array('domain' => $domain));
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ( !$data ) { return false; }

        $stmt = $this->getPdo()->prepare("SELECT `name`, `type`, `content`, `ttl`, `prio`, `change_date` FROM records WHERE domain_id = :domain_id");
        $stmt->execute(array('domain_id' => $data['id']));
        $data['details'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $data;
    }

    public function create($domain, $type = 'MASTER', $master = null) {
        if ( $this->exists($domain) ) {
            throw new \Exception("Domain Exists");
        }

        $type = strtoupper($type);

        if ( $type == 'SLAVE' && empty($master) ) {
            throw new \Exception("Master parameter required for SLAVE domains.");
        }

        $stmt = $this->getPdo()->prepare("INSERT INTO domains (`name`, `type`, `master` ) VALUES (:name, :type, :master)");
        $stmt->execute(array('name' => $domain, 'type' => $type, 'master' => $master));

        return $this->get($domain);
    }

    public function update($domain, $type = 'MASTER', $master = null, $new_domain = null) {
        if ( !$this->exists($domain) ) {
            throw new \Exception("Domain not found");
        }

        $type = strtoupper($type);

        if ( empty($new_domain) ) {
            $new_domain = $domain;
        }

        if ( $type == 'SLAVE' && empty($master) ) {
            throw new \Exception("Master parameter required for SLAVE domains.");
        }

        $stmt = $this->getPdo()->prepare("UPDATE domains SET  `name` = :new_name,  `type` = :type, `master` = :master WHERE `name` = :name");
        $stmt->execute(array('name' => $domain, 'type' => $type, 'master' => $master, 'new_name' => $new_domain));

        return $this->get($new_domain);
    }

    public function delete($domain) {
        if ( !$this->exists($domain) ) {
            throw new \Exception("Domain not found");
        }

        $stmt = $this->getPdo()->prepare("DELETE FROM domains WHERE name = :domain");
        $stmt->execute(array('domain' => $domain));

        return true;
    }

    private function exists($domain) {
        $stmt = $this->getPdo()->prepare("SELECT COUNT(id) FROM domains WHERE name = :domain");
        $stmt->execute(array('domain' => $domain));
        $data = $stmt->fetch(\PDO::FETCH_NUM);

        return ( $data[0] > 0 ) ? true : false;
    }
}