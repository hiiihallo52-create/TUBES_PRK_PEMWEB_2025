/**
 * Stock Out Module
 * Handle Stock Out CRUD operations dengan AJAX
 */

const StockOutModule = {
    state: {
        stockOuts: [],
        materials: [],
        currentPage: 1,
        totalPages: 1,
        filters: {
            material_id: '',
            usage_type: '',
            start_date: '',
            end_date: '',
            search: ''
        }
    },

    /**
     * Initialize module
     */
    init() {
        this.loadMaterials();
        this.loadStockOuts();
        this.bindEvents();
    },

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Create button
        document.getElementById('btnCreateStockOut')?.addEventListener('click', () => {
            this.showModal();
        });

        // Form submit
        document.getElementById('stockOutForm')?.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleSubmit();
        });

        // Filter changes
        document.getElementById('filterMaterial')?.addEventListener('change', () => {
            this.applyFilters();
        });

        document.getElementById('filterUsageType')?.addEventListener('change', () => {
            this.applyFilters();
        });

        document.getElementById('filterStartDate')?.addEventListener('change', () => {
            this.applyFilters();
        });

        document.getElementById('filterEndDate')?.addEventListener('change', () => {
            this.applyFilters();
        });

        document.getElementById('searchInput')?.addEventListener('input', (e) => {
            this.state.filters.search = e.target.value;
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => this.applyFilters(), 500);
        });

        // Material change handler
        document.getElementById('material_id')?.addEventListener('change', (e) => {
            this.handleMaterialChange(e.target.value);
        });

        // Quantity input
        document.getElementById('quantity')?.addEventListener('input', (e) => {
            this.updateStockPreview();
        });
    },

    /**
     * Load materials for dropdown
     */
    async loadMaterials() {
        try {
            const response = await ApiClient.get('/materials', { per_page: 1000 });
            if (response.success && response.data) {
                this.state.materials = response.data.data || [];
                this.renderMaterialDropdowns();
            }
        } catch (error) {
            console.error('Load materials error:', error);
        }
    },

    /**
     * Render material dropdowns
     */
    renderMaterialDropdowns() {
        const formSelect = document.getElementById('material_id');
        const filterSelect = document.getElementById('filterMaterial');

        const options = `
            <option value="">Pilih Material</option>
            ${this.state.materials.map(m => `
                <option value="${m.id}">${m.code ? m.code + ' - ' : ''}${m.name} (Stok: ${m.current_stock})</option>
            `).join('')}
        `;

        if (formSelect) {
            formSelect.innerHTML = options;
        }

        if (filterSelect) {
            filterSelect.innerHTML = `<option value="">Semua Material</option>` + options;
        }
    },

    /**
     * Handle material selection change
     */
    async handleMaterialChange(materialId) {
        const currentStockField = document.getElementById('current_stock');
        const stockPreviewDiv = document.getElementById('stockPreview');
        
        if (!materialId) {
            if (currentStockField) currentStockField.value = '0';
            if (stockPreviewDiv) stockPreviewDiv.innerHTML = '';
            return;
        }

        try {
            const response = await ApiClient.get(`/materials/${materialId}`);
            if (response.success && response.data) {
                const material = response.data.data || response.data;
                if (currentStockField) {
                    currentStockField.value = material.current_stock || 0;
                }
                this.updateStockPreview();
            }
        } catch (error) {
            console.error('Get material error:', error);
            if (currentStockField) currentStockField.value = '0';
        }
    },

    /**
     * Update stock preview
     */
    updateStockPreview() {
        const currentStock = parseFloat(document.getElementById('current_stock')?.value || 0);
        const quantity = parseFloat(document.getElementById('quantity')?.value || 0);
        const previewDiv = document.getElementById('stockPreview');

        if (!previewDiv || !quantity) {
            if (previewDiv) previewDiv.innerHTML = '';
            return;
        }

        const newStock = currentStock - quantity;
        const alertClass = newStock < 0 ? 'alert-danger' : (newStock <= 10 ? 'alert-warning' : 'alert-info');

        previewDiv.innerHTML = `
            <div class="alert ${alertClass} mb-0">
                <small>
                    <strong>Preview:</strong> Stok saat ini: ${currentStock.toFixed(2)} → 
                    Setelah keluar: ${newStock.toFixed(2)}
                    ${newStock < 0 ? '<br><i class="bi bi-exclamation-triangle"></i> Stok tidak mencukupi!' : ''}
                </small>
            </div>
        `;
    },

    /**
     * Load stock outs with filters
     */
    async loadStockOuts() {
        try {
            const params = {
                page: this.state.currentPage,
                per_page: 20,
                ...this.state.filters
            };

            // Remove empty params
            Object.keys(params).forEach(key => {
                if (params[key] === '' || params[key] === null) {
                    delete params[key];
                }
            });

            const response = await ApiClient.get('/stock-out', params);
            
            if (response.success && response.data) {
                this.state.stockOuts = response.data.data || [];
                this.state.currentPage = response.data.current_page || 1;
                this.state.totalPages = response.data.last_page || 1;
                
                this.renderStockOuts();
                this.renderPagination();
            }
        } catch (error) {
            console.error('Load stock outs error:', error);
            Toast.error('Gagal memuat data stock out');
        }
    },

    /**
     * Render stock outs table
     */
    renderStockOuts() {
        const tbody = document.getElementById('stockOutTableBody');
        if (!tbody) return;

        if (this.state.stockOuts.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data stock out</td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = this.state.stockOuts.map((so, index) => {
            const no = (this.state.currentPage - 1) * 20 + index + 1;
            
            return `
                <tr>
                    <td>${no}</td>
                    <td><code>${so.reference_number || '-'}</code></td>
                    <td>${so.material_code ? so.material_code + '<br>' : ''}${so.material_name || '-'}</td>
                    <td class="text-end"><strong>${parseFloat(so.quantity || 0).toFixed(2)}</strong></td>
                    <td><span class="badge bg-primary">${this.getUsageTypeLabel(so.usage_type)}</span></td>
                    <td>${so.destination || '-'}</td>
                    <td>
                        <small>${new Date(so.transaction_date).toLocaleDateString('id-ID')}</small><br>
                        <small class="text-muted">oleh: ${so.created_by_name || '-'}</small>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="StockOutModule.viewDetail(${so.id})">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    },

    /**
     * Get usage type label
     */
    getUsageTypeLabel(type) {
        const labels = {
            'production': 'Produksi',
            'sale': 'Penjualan',
            'waste': 'Terbuang',
            'transfer': 'Transfer',
            'other': 'Lainnya'
        };
        return labels[type] || type;
    },

    /**
     * Render pagination
     */
    renderPagination() {
        const pagination = document.getElementById('pagination');
        if (!pagination) return;

        if (this.state.totalPages <= 1) {
            pagination.innerHTML = '';
            return;
        }

        let html = `
            <li class="page-item ${this.state.currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="StockOutModule.changePage(${this.state.currentPage - 1}); return false;">Previous</a>
            </li>
        `;

        for (let i = 1; i <= this.state.totalPages; i++) {
            if (i === 1 || i === this.state.totalPages || (i >= this.state.currentPage - 2 && i <= this.state.currentPage + 2)) {
                html += `
                    <li class="page-item ${i === this.state.currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="StockOutModule.changePage(${i}); return false;">${i}</a>
                    </li>
                `;
            } else if (i === this.state.currentPage - 3 || i === this.state.currentPage + 3) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        html += `
            <li class="page-item ${this.state.currentPage === this.state.totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="StockOutModule.changePage(${this.state.currentPage + 1}); return false;">Next</a>
            </li>
        `;

        pagination.innerHTML = html;
    },

    /**
     * Change page
     */
    changePage(page) {
        if (page < 1 || page > this.state.totalPages) return;
        this.state.currentPage = page;
        this.loadStockOuts();
    },

    /**
     * Apply filters
     */
    applyFilters() {
        this.state.filters.material_id = document.getElementById('filterMaterial')?.value || '';
        this.state.filters.usage_type = document.getElementById('filterUsageType')?.value || '';
        this.state.filters.start_date = document.getElementById('filterStartDate')?.value || '';
        this.state.filters.end_date = document.getElementById('filterEndDate')?.value || '';
        
        this.state.currentPage = 1;
        this.loadStockOuts();
    },

    /**
     * Show create modal
     */
    showModal() {
        const modal = new bootstrap.Modal(document.getElementById('stockOutModal'));
        const form = document.getElementById('stockOutForm');

        form.reset();
        document.getElementById('current_stock').value = '0';
        document.getElementById('stockPreview').innerHTML = '';
        document.getElementById('transaction_date').value = new Date().toISOString().split('T')[0];

        modal.show();
    },

    /**
     * Handle form submit
     */
    async handleSubmit() {
        const form = document.getElementById('stockOutForm');
        const formData = new FormData(form);

        const data = {
            material_id: parseInt(formData.get('material_id')),
            quantity: parseFloat(formData.get('quantity')),
            usage_type: formData.get('usage_type'),
            transaction_date: formData.get('transaction_date'),
            destination: formData.get('destination'),
            notes: formData.get('notes')
        };

        // Validate
        const errors = Validator.validate(data, {
            material_id: 'required|numeric',
            quantity: 'required|numeric|min:0.01',
            usage_type: 'required',
            transaction_date: 'required'
        });

        if (Object.keys(errors).length > 0) {
            Toast.error('Mohon lengkapi form dengan benar');
            return;
        }

        // Check stock
        const currentStock = parseFloat(document.getElementById('current_stock').value || 0);
        if (data.quantity > currentStock) {
            Toast.error('Jumlah keluar melebihi stok tersedia');
            return;
        }

        try {
            const response = await ApiClient.post('/stock-out', data);

            if (response.success) {
                Toast.success(response.message || 'Stock out berhasil dibuat');
                
                const modal = bootstrap.Modal.getInstance(document.getElementById('stockOutModal'));
                modal.hide();
                
                form.reset();
                this.loadStockOuts();
                this.loadMaterials(); // Refresh material stock
            } else {
                Toast.error(response.message || 'Gagal membuat stock out');
            }
        } catch (error) {
            console.error('Submit error:', error);
            Toast.error(error.message || 'Terjadi kesalahan saat menyimpan');
        }
    },

    /**
     * View detail
     */
    async viewDetail(id) {
        try {
            const response = await ApiClient.get(`/stock-out/${id}`);
            
            if (response.success && response.data) {
                const so = response.data.data;
                
                const detailHtml = `
                    <table class="table">
                        <tr>
                            <th width="150">No. Referensi</th>
                            <td><code>${so.reference_number}</code></td>
                        </tr>
                        <tr>
                            <th>Material</th>
                            <td>${so.material_code ? so.material_code + ' - ' : ''}${so.material_name}</td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td><strong>${parseFloat(so.quantity).toFixed(2)}</strong></td>
                        </tr>
                        <tr>
                            <th>Jenis Penggunaan</th>
                            <td><span class="badge bg-primary">${this.getUsageTypeLabel(so.usage_type)}</span></td>
                        </tr>
                        <tr>
                            <th>Tujuan</th>
                            <td>${so.destination || '-'}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Transaksi</th>
                            <td>${new Date(so.transaction_date).toLocaleDateString('id-ID')}</td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>${so.notes || '-'}</td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>${so.created_by_name || '-'}</td>
                        </tr>
                        <tr>
                            <th>Waktu Dibuat</th>
                            <td>${new Date(so.created_at).toLocaleString('id-ID')}</td>
                        </tr>
                    </table>
                `;
                
                document.getElementById('detailContent').innerHTML = detailHtml;
                const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                modal.show();
            }
        } catch (error) {
            console.error('View detail error:', error);
            Toast.error('Gagal memuat detail');
        }
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    StockOutModule.init();
});
