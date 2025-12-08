/**
 * Supplier Module
 * Handles all supplier CRUD operations with AJAX
 */
const SupplierModule = {
    // State
    state: {
        suppliers: [],
        currentPage: 1,
        perPage: 9,
        totalPages: 1,
        searchKeyword: '',
        editingId: null,
        deletingId: null
    },

    // Color gradients for cards
    gradients: [
        'from-blue-500 to-blue-400',
        'from-purple-500 to-fuchsia-500',
        'from-pink-500 to-rose-500',
        'from-orange-500 to-amber-500',
        'from-emerald-500 to-green-500',
        'from-red-400 to-rose-400',
        'from-cyan-500 to-blue-500',
        'from-violet-500 to-purple-500',
        'from-lime-500 to-green-400'
    ],

    // DOM Elements
    elements: {},

    /**
     * Initialize module
     */
    init() {
        console.log('SupplierModule.init() called');
        this.cacheElements();
        this.bindEvents();
        this.loadSuppliers();
    },

    /**
     * Cache DOM elements
     */
    cacheElements() {
        this.elements = {
            searchInput: document.getElementById('searchInput'),
            btnAddSupplier: document.getElementById('btnAddSupplier'),
            supplierGrid: document.getElementById('supplierGrid'),
            paginationContainer: document.getElementById('paginationContainer'),
            loadingState: document.getElementById('loadingState'),
            emptyState: document.getElementById('emptyState'),
            
            // Modal elements
            supplierModal: document.getElementById('supplierModal'),
            modalTitle: document.getElementById('modalTitle'),
            supplierForm: document.getElementById('supplierForm'),
            supplierId: document.getElementById('supplierId'),
            supplierName: document.getElementById('supplierName'),
            contactPerson: document.getElementById('contactPerson'),
            phone: document.getElementById('phone'),
            email: document.getElementById('email'),
            address: document.getElementById('address'),
            submitText: document.getElementById('submitText'),
            submitSpinner: document.getElementById('submitSpinner'),
            btnSubmit: document.getElementById('btnSubmit'),
            
            // Delete modal elements
            deleteModal: document.getElementById('deleteModal'),
            deleteItemName: document.getElementById('deleteItemName'),
            btnConfirmDelete: document.getElementById('btnConfirmDelete'),
            deleteText: document.getElementById('deleteText'),
            deleteSpinner: document.getElementById('deleteSpinner')
        };
    },

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Search with debounce
        let searchTimeout;
        if (this.elements.searchInput) {
            this.elements.searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.state.searchKeyword = e.target.value;
                    this.state.currentPage = 1;
                    this.loadSuppliers();
                }, 500);
            });
        }

        // Add button
        if (this.elements.btnAddSupplier) {
            this.elements.btnAddSupplier.addEventListener('click', (e) => {
                e.preventDefault();
                this.openModalForCreate();
            });
        }

        // Form submit
        if (this.elements.supplierForm) {
            this.elements.supplierForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleSubmit();
            });
        }

        // Close modal on backdrop click
        if (this.elements.supplierModal) {
            this.elements.supplierModal.addEventListener('click', (e) => {
                if (e.target === this.elements.supplierModal) {
                    this.closeModal();
                }
            });
        }

        if (this.elements.deleteModal) {
            this.elements.deleteModal.addEventListener('click', (e) => {
                if (e.target === this.elements.deleteModal) {
                    this.closeDeleteModal();
                }
            });
        }
    },

    /**
     * Load suppliers from API
     */
    async loadSuppliers() {
        this.showLoading();

        try {
            const params = {
                page: this.state.currentPage,
                per_page: this.state.perPage
            };

            if (this.state.searchKeyword) {
                params.search = this.state.searchKeyword;
            }

            const response = await ApiClient.get('/suppliers', params);

            if (response.success) {
                // Handle both old and new response format
                if (response.data.data) {
                    this.state.suppliers = response.data.data || [];
                    this.state.currentPage = response.data.current_page || 1;
                    this.state.totalPages = response.data.last_page || 1;
                } else if (response.data.suppliers) {
                    this.state.suppliers = response.data.suppliers || [];
                    this.state.currentPage = response.data.pagination?.current_page || 1;
                    this.state.totalPages = response.data.pagination?.last_page || 1;
                }

                this.renderSuppliers();
                this.renderPagination();
            } else {
                throw new Error(response.message || 'Gagal memuat data');
            }
        } catch (error) {
            console.error('Load suppliers error:', error);
            Toast.error('Gagal Memuat Data', error.message);
            this.showEmpty('Gagal memuat data supplier');
        }
    },

    /**
     * Render suppliers grid
     */
    renderSuppliers() {
        if (this.state.suppliers.length === 0) {
            this.showEmpty(
                this.state.searchKeyword 
                    ? `Tidak ditemukan supplier dengan kata kunci "${this.state.searchKeyword}"`
                    : 'Belum ada supplier yang dibuat'
            );
            return;
        }

        const html = this.state.suppliers.map((supplier, index) => {
            const gradient = this.gradients[index % this.gradients.length];
            const initials = supplier.name.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2);

            return `
                <article class="rounded-3xl border border-slate-100 bg-white shadow-sm p-5 flex flex-col gap-4 hover:shadow-md transition-shadow">
                    <!-- HEADER -->
                    <div class="flex items-start gap-4">
                        <div class="h-12 w-12 rounded-2xl text-white flex items-center justify-center font-semibold text-sm bg-gradient-to-br ${gradient}">
                            ${initials}
                        </div>

                        <div class="flex-1 min-w-0">
                            <h2 class="text-lg font-semibold text-slate-900 truncate">${this.escapeHtml(supplier.name)}</h2>
                            <p class="text-sm text-slate-500 mt-1">${this.escapeHtml(supplier.contact_person || 'Tidak ada kontak')}</p>
                        </div>

                        <!-- ACTIONS -->
                        <div class="flex items-center gap-3 text-slate-400">
                            <button 
                                title="Edit" 
                                class="hover:text-blue-500 transition-colors"
                                onclick="SupplierModule.openModalForEdit(${supplier.id})"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </button>
                            <button 
                                title="Hapus" 
                                class="hover:text-rose-500 transition-colors"
                                onclick="SupplierModule.openDeleteModal(${supplier.id}, '${this.escapeHtml(supplier.name)}')"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- DETAILS -->
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.362-.271.527-.733.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                            <span>${this.escapeHtml(supplier.phone)}</span>
                        </div>
                        ${supplier.email ? `
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5H4.5A2.25 2.25 0 002.25 6.75m19.5 0v-1.5a2.25 2.25 0 00-2.25-2.25H4.5a2.25 2.25 0 00-2.25 2.25v1.5m19.5 0h-2.25m-7.5 0h-2.25m7.5 0v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25v-10.5" />
                            </svg>
                            <span>${this.escapeHtml(supplier.email)}</span>
                        </div>
                        ` : ''}
                        ${supplier.address ? `
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M3 21h18" />
                            </svg>
                            <span>${this.escapeHtml(supplier.address)}</span>
                        </div>
                        ` : ''}
                    </div>
                </article>
            `;
        }).join('');

        this.elements.supplierGrid.innerHTML = html;
        this.elements.loadingState.classList.add('hidden');
        this.elements.emptyState.classList.add('hidden');
        this.elements.supplierGrid.classList.remove('hidden');
    },

    /**
     * Render pagination
     */
    renderPagination() {
        if (this.state.totalPages <= 1) {
            this.elements.paginationContainer.innerHTML = '';
            return;
        }

        const pages = [];
        for (let i = 1; i <= this.state.totalPages; i++) {
            pages.push(i);
        }

        const html = pages.map(page => {
            const isActive = page === this.state.currentPage;
            return `
                <button
                    onclick="SupplierModule.goToPage(${page})"
                    class="px-3 py-2 rounded-lg text-sm font-medium transition-all ${
                        isActive
                            ? 'bg-blue-500 text-white'
                            : 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                    }"
                >
                    ${page}
                </button>
            `;
        }).join('');

        this.elements.paginationContainer.innerHTML = `
            <nav class="flex items-center gap-2">
                ${html}
            </nav>
        `;
    },

    /**
     * Go to page
     */
    goToPage(page) {
        this.state.currentPage = page;
        this.loadSuppliers();
    },

    /**
     * Open modal for create
     */
    openModalForCreate() {
        this.state.editingId = null;
        this.clearForm();
        
        if (this.elements.modalTitle) {
            this.elements.modalTitle.textContent = 'Tambah Supplier';
        }
        if (this.elements.submitText) {
            this.elements.submitText.textContent = 'Simpan';
        }
        
        this.elements.supplierModal.classList.remove('hidden');
    },

    /**
     * Open modal for edit
     */
    async openModalForEdit(id) {
        try {
            this.showLoading();
            
            const response = await ApiClient.get(`/suppliers/${id}`);
            
            if (response.success) {
                const supplier = response.data.data || response.data.supplier;
                
                this.state.editingId = supplier.id;
                this.elements.supplierId.value = supplier.id;
                this.elements.supplierName.value = supplier.name;
                this.elements.contactPerson.value = supplier.contact_person || '';
                this.elements.phone.value = supplier.phone || '';
                this.elements.email.value = supplier.email || '';
                this.elements.address.value = supplier.address || '';
                
                if (this.elements.modalTitle) {
                    this.elements.modalTitle.textContent = 'Edit Supplier';
                }
                if (this.elements.submitText) {
                    this.elements.submitText.textContent = 'Perbarui';
                }
                
                this.elements.supplierModal.classList.remove('hidden');
            } else {
                Toast.error('Gagal Memuat', response.message);
            }
        } catch (error) {
            Toast.error('Error', error.message);
        }
    },

    /**
     * Close modal
     */
    closeModal() {
        this.elements.supplierModal.classList.add('hidden');
        this.clearForm();
    },

    /**
     * Clear form
     */
    clearForm() {
        this.elements.supplierForm.reset();
        this.elements.supplierId.value = '';
        this.state.editingId = null;
        document.querySelectorAll('[id$="Error"]').forEach(el => el.classList.add('hidden'));
    },

    /**
     * Handle form submit
     */
    async handleSubmit() {
        const formData = {
            name: this.elements.supplierName.value.trim(),
            contact_person: this.elements.contactPerson.value.trim(),
            phone: this.elements.phone.value.trim(),
            email: this.elements.email.value.trim() || null,
            address: this.elements.address.value.trim() || null
        };

        // Validation
        const errors = {};
        
        if (!formData.name) {
            errors.name = 'Nama supplier harus diisi';
        }
        if (!formData.contact_person) {
            errors.contact_person = 'Nama kontak harus diisi';
        }
        if (!formData.phone) {
            errors.phone = 'Nomor telepon harus diisi';
        }
        if (formData.email && !this.isValidEmail(formData.email)) {
            errors.email = 'Format email tidak valid';
        }

        if (Object.keys(errors).length > 0) {
            this.displayErrors(errors);
            return;
        }

        this.clearErrors();
        this.setSubmitLoading(true);

        try {
            let response;
            if (this.state.editingId) {
                response = await ApiClient.post(`/suppliers/${this.state.editingId}`, formData);
            } else {
                response = await ApiClient.post('/suppliers', formData);
            }

            if (response.success) {
                Toast.success('Berhasil!', response.message);
                this.closeModal();
                this.loadSuppliers();
            } else if (response.errors) {
                this.displayErrors(response.errors);
                Toast.error('Validasi Gagal', response.message);
            } else {
                Toast.error('Error', response.message);
            }
        } catch (error) {
            // Handle validation errors (422) and other errors
            if (error.errors && Object.keys(error.errors).length > 0) {
                this.displayErrors(error.errors);
                Toast.error('Validasi Gagal', error.message || 'Ada kesalahan pada form');
            } else {
                Toast.error('Error', error.message || 'Terjadi kesalahan');
            }
        } finally {
            this.setSubmitLoading(false);
        }
    },

    /**
     * Open delete confirmation modal
     */
    openDeleteModal(id, name) {
        this.state.deletingId = id;
        this.elements.deleteItemName.textContent = name;
        this.elements.deleteModal.classList.remove('hidden');
    },

    /**
     * Close delete modal
     */
    closeDeleteModal() {
        this.elements.deleteModal.classList.add('hidden');
        this.state.deletingId = null;
    },

    /**
     * Confirm delete
     */
    async confirmDelete() {
        if (!this.state.deletingId) return;

        this.setDeleteLoading(true);

        try {
            const response = await ApiClient.post(`/suppliers/${this.state.deletingId}/delete`, {});

            if (response.success) {
                Toast.success('Berhasil!', response.message);
                this.closeDeleteModal();
                this.loadSuppliers();
            } else {
                Toast.error('Error', response.message);
            }
        } catch (error) {
            Toast.error('Error', error.message);
        } finally {
            this.setDeleteLoading(false);
        }
    },

    /**
     * Show loading state
     */
    showLoading() {
        this.elements.loadingState.classList.remove('hidden');
        this.elements.emptyState.classList.add('hidden');
        this.elements.supplierGrid.classList.add('hidden');
        this.elements.paginationContainer.classList.add('hidden');
    },

    /**
     * Show empty state
     */
    showEmpty(message) {
        this.elements.emptyState.classList.remove('hidden');
        document.getElementById('emptyMessage').textContent = message;
        this.elements.loadingState.classList.add('hidden');
        this.elements.supplierGrid.classList.add('hidden');
        this.elements.paginationContainer.classList.add('hidden');
    },

    /**
     * Display validation errors
     */
    displayErrors(errors) {
        Object.keys(errors).forEach(field => {
            const errorEl = document.getElementById(`${field}Error`);
            if (errorEl) {
                errorEl.textContent = errors[field];
                errorEl.classList.remove('hidden');
            }
        });
    },

    /**
     * Clear validation errors
     */
    clearErrors() {
        document.querySelectorAll('[id$="Error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
    },

    /**
     * Set submit button loading state
     */
    setSubmitLoading(loading) {
        this.elements.btnSubmit.disabled = loading;
        if (loading) {
            this.elements.submitText.classList.add('hidden');
            this.elements.submitSpinner.classList.remove('hidden');
        } else {
            this.elements.submitText.classList.remove('hidden');
            this.elements.submitSpinner.classList.add('hidden');
        }
    },

    /**
     * Set delete button loading state
     */
    setDeleteLoading(loading) {
        this.elements.btnConfirmDelete.disabled = loading;
        if (loading) {
            this.elements.deleteText.classList.add('hidden');
            this.elements.deleteSpinner.classList.remove('hidden');
        } else {
            this.elements.deleteText.classList.remove('hidden');
            this.elements.deleteSpinner.classList.add('hidden');
        }
    },

    /**
     * Check if email is valid
     */
    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },

    /**
     * Escape HTML special characters
     */
    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};
