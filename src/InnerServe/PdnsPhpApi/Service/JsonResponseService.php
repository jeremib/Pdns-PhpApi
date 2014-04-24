<?php

namespace InnerServe\PdnsPhpApi\Service;

class JsonResponseService {
    public function ok($data) {
        return json_encode(array('success' => true, 'error' => null, 'data' => $data));
    }

    public function error($error, $data = null) {
        return json_encode(array('success' => false, 'error' => $error, 'data' => $data));
    }
}