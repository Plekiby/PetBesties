<?php
require_once __DIR__ . '/../models/Prestataire.php';

class PrestataireController {
    private $prestataireModel;

    public function __construct() {
        $this->prestataireModel = new Prestataire();
    }

    public function displayPrestataires() {
        // Récupérer les filtres via GET
        $filters = [
            'promenade' => isset($_GET['promenade']) ? true : false,
            'hebergement' => isset($_GET['hebergement']) ? true : false,
            'prix_max' => isset($_GET['prix_max']) ? floatval($_GET['prix_max']) : 100,
            'types' => [
                'chien' => isset($_GET['chien']),
                'chat' => isset($_GET['chat']),
                'oiseau' => isset($_GET['oiseau']),
                'rongeur' => isset($_GET['rongeur']),
            ]
        ];

        // Pas de limite, nous récupérons tous les prestataires correspondants
        $prestataires = $this->prestataireModel->getFilteredPrestataires($filters);

        require __DIR__ . '/../views/prestataireList.php';
    }
}
?>
