<?php
require_once __DIR__ . '/../models/Historique.php';

class HistoriqueController {
    private $model;

    public function __construct() {
        $this->model = new Historique();
    }

    public function index($userId) {
        $sentHistory = $this->model->fetchSentHistory($userId);
        $receivedHistory = $this->model->fetchReceivedHistory($userId);

        return [
            'sent' => $sentHistory,
            'received' => $receivedHistory
        ];
    }
}
?>
