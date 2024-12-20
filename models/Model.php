
<?php

class Model {
    protected $db;

    public function __construct() {
        // ...existing code connecting to the database...
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
?>