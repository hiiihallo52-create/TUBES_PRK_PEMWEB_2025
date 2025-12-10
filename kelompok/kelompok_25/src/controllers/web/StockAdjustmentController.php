<?php

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/middleware/AuthMiddleware.php';

class StockAdjustmentController extends Controller
{
    private $stockAdjustmentModel;
    private $materialModel;
    private $db;

    public function __construct()
    {
        AuthMiddleware::check();
        $this->db = Database::getInstance()->getConnection();
        $this->stockAdjustmentModel = new StockAdjustment($this->db);
        $this->materialModel = new Material();
    }

    public function index()
    {
        $this->view('stock-adjustments/index', [
            'title' => 'Penyesuaian Stok'
        ]);
    }

}
