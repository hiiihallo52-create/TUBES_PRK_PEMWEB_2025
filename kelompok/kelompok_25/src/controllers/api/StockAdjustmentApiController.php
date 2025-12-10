<?php

/**
 * Stock Adjustment API Controller
 * Handle API requests untuk Stock Adjustment Management
 */

require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Response.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/middleware/AuthMiddleware.php';
require_once ROOT_PATH . '/models/StockAdjustment.php';

class StockAdjustmentApiController extends Controller
{
    private $model;
    private $db;

    public function __construct()
    {
        AuthMiddleware::check();
        
        $this->db = Database::getInstance()->getConnection();
        $this->model = new StockAdjustment($this->db);
    }

    /**
     * GET /api/stock-adjustments
     * List all stock adjustments with pagination
     */
    public function index()
    {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
            
            if ($page < 1) $page = 1;
            if ($perPage < 1 || $perPage > 100) $perPage = 20;

            $filters = [
                'material_id' => isset($_GET['material_id']) ? (int)$_GET['material_id'] : null,
                'reason' => $_GET['reason'] ?? null,
                'start_date' => $_GET['start_date'] ?? null,
                'end_date' => $_GET['end_date'] ?? null,
                'q' => $_GET['q'] ?? null
            ];

            $result = $this->model->getAll($page, $perPage, $filters);

            Response::success('Data stock adjustment berhasil diambil', $result);

        } catch (Exception $e) {
            Response::error('Gagal mengambil data stock adjustment: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * GET /api/stock-adjustments/:id
     * Get stock adjustment detail
     */
    public function show($id)
    {
        try {
            if (!is_numeric($id) || $id < 1) {
                Response::error('ID tidak valid', [], 400);
                return;
            }

            $adjustment = $this->model->findById($id);

            if (!$adjustment) {
                Response::notFound('Stock adjustment tidak ditemukan');
                return;
            }

            Response::success('Detail stock adjustment', ['data' => $adjustment]);

        } catch (Exception $e) {
            Response::error('Gagal mengambil detail: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * POST /api/stock-adjustments
     * Create new stock adjustment
     */
    public function store()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Response::error('Invalid JSON format', [], 400);
                return;
            }

            $userId = Auth::id();

            $payload = [
                'material_id' => isset($input['material_id']) ? (int)$input['material_id'] : null,
                'new_stock' => isset($input['new_stock']) ? (float)$input['new_stock'] : null,
                'reason' => $input['reason'] ?? null,
                'notes' => $input['notes'] ?? null,
                'adjusted_by' => $userId
            ];

            $adjustment = $this->model->create($payload);

            // Log activity
            $this->logActivity('CREATE', 'stock_adjustments', $adjustment['id'], 
                "Created stock adjustment for material ID {$input['material_id']}");

            Response::created('Stock adjustment berhasil dibuat', $adjustment);

        } catch (Exception $e) {
            Response::error('Gagal membuat stock adjustment: ' . $e->getMessage(), [], 400);
        }
    }

    /**
     * GET /api/stock-adjustments/material/:id
     * Get adjustments by material
     */
    public function material($materialId)
    {
        try {
            $materialId = (int)$materialId;
            $start = $_GET['start_date'] ?? null;
            $end = $_GET['end_date'] ?? null;
            
            $dateRange = null;
            if ($start && $end) {
                $dateRange = ['start' => $start, 'end' => $end];
            }

            $adjustments = $this->model->getByMaterial($materialId, $dateRange);

            Response::success('Data stock adjustment berhasil diambil', ['data' => $adjustments]);

        } catch (Exception $e) {
            Response::error('Gagal mengambil data: ' . $e->getMessage(), [], 400);
        }
    }

    /**
     * GET /api/stock-adjustments/reason/:reason
     * Get adjustments by reason
     */
    public function reason($reason)
    {
        try {
            $start = $_GET['start_date'] ?? null;
            $end = $_GET['end_date'] ?? null;
            
            $dateRange = null;
            if ($start && $end) {
                $dateRange = ['start' => $start, 'end' => $end];
            }

            $adjustments = $this->model->getByReason($reason, $dateRange);

            Response::success('Data stock adjustment berhasil diambil', ['data' => $adjustments]);

        } catch (Exception $e) {
            Response::error('Gagal mengambil data: ' . $e->getMessage(), [], 400);
        }
    }

    /**
     * GET /api/stock-adjustments/stats
     * Get statistics
     */
    public function stats()
    {
        try {
            $start = $_GET['start_date'] ?? null;
            $end = $_GET['end_date'] ?? null;
            
            $dateRange = null;
            if ($start && $end) {
                $dateRange = ['start' => $start, 'end' => $end];
            }

            $stats = $this->model->getStats($dateRange);

            Response::success('Statistik stock adjustment', ['data' => $stats]);

        } catch (Exception $e) {
            Response::error('Gagal mengambil statistik: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * GET /api/stock-adjustments/report
     * Get report for date range
     */
    public function report()
    {
        try {
            $start = $_GET['start_date'] ?? null;
            $end = $_GET['end_date'] ?? null;

            if (!$start || !$end) {
                Response::error('start_date dan end_date wajib diisi', [], 400);
                return;
            }

            $stmt = $this->db->prepare("
                SELECT sa.*, m.name as material_name, m.code as material_code, u.name as adjusted_by_name 
                FROM stock_adjustments sa 
                LEFT JOIN materials m ON m.id = sa.material_id 
                LEFT JOIN users u ON u.id = sa.adjusted_by 
                WHERE sa.adjusted_at BETWEEN :start AND :end 
                ORDER BY sa.adjusted_at DESC
            ");
            
            $stmt->execute([
                ':start' => $start . ' 00:00:00', 
                ':end' => $end . ' 23:59:59'
            ]);
            
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            Response::success('Laporan stock adjustment', [
                'data' => $data,
                'period' => ['start' => $start, 'end' => $end],
                'total' => count($data)
            ]);

        } catch (Exception $e) {
            Response::error('Gagal mengambil laporan: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Log activity helper
     */
    private function logActivity($action, $entityType, $entityId, $description)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO activity_logs 
                (user_id, action, entity_type, entity_id, description, ip_address, user_agent, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                Auth::id(),
                $action,
                $entityType,
                $entityId,
                $description,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);
        } catch (Exception $e) {
            // Silent fail
        }
    }
}
