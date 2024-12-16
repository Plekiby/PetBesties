<?php
require_once __DIR__ . '/../models/Postule.php';

class PostuleController {
    private $model;

    public function __construct() {
        $this->model = new Postule();
    }

    public function index($userId) {
        $sentCandidatures = $this->model->fetchSent($userId);
        $receivedCandidatures = $this->model->fetchReceived($userId);

        return [
            'sent' => $sentCandidatures,
            'received' => $receivedCandidatures
        ];
    }
}
?>
