<section class="p-6 md:p-10 space-y-6">
    <div class="flex flex-col gap-2">
        <h1 class="text-2xl font-semibold text-slate-900">Data Supplier</h1>
        <p class="text-sm text-slate-500">Kelola data supplier dan kontak mereka</p>
    </div>

    <!-- SEARCH AND ADD BUTTON -->
    <div class="flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
        <div class="relative flex-1">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" /></svg>
            </span>
            <input id="searchInput" type="text" placeholder="Cari supplier..." class="w-full rounded-2xl border border-slate-200 pl-12 pr-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-400" />
        </div>
        <button id="btnAddSupplier" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-pink-500 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-sm hover:shadow-md transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5" /></svg>
            Tambah Supplier
        </button>
    </div>

    <!-- LOADING STATE -->
    <div id="loadingState" class="hidden">
        <div class="flex flex-col items-center justify-center py-12">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-500"></div>
            <p class="mt-4 text-slate-500">Memuat data supplier...</p>
        </div>
    </div>

    <!-- EMPTY STATE -->
    <div id="emptyState" class="hidden">
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L6.75 17.25M6.75 6.75l10.5 10.5" /></svg>
            <p id="emptyMessage" class="text-slate-500">Belum ada supplier yang dibuat</p>
        </div>
    </div>

    <!-- SUPPLIER GRID -->
    <div id="supplierGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5"></div>

    <!-- PAGINATION -->
    <div id="paginationContainer" class="flex justify-center mt-8"></div>
</section>

<!-- SUPPLIER MODAL -->
<div id="supplierModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <h2 id="modalTitle" class="text-lg font-semibold text-slate-900">Tambah Supplier</h2>
            <button onclick="SupplierModule.closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Form -->
        <form id="supplierForm" class="p-6 space-y-4">
            <input type="hidden" id="supplierId" />

            <!-- Nama Supplier -->
            <div>
                <label for="supplierName" class="block text-sm font-medium text-slate-700 mb-2">Nama Supplier</label>
                <input id="supplierName" type="text" placeholder="e.g., PT Bogasari" class="w-full rounded-lg border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all" required />
                <p id="supplierNameError" class="hidden text-sm text-red-500 mt-1"></p>
            </div>

            <!-- Nama Kontak -->
            <div>
                <label for="contactPerson" class="block text-sm font-medium text-slate-700 mb-2">Nama Kontak</label>
                <input id="contactPerson" type="text" placeholder="e.g., Budi Santoso" class="w-full rounded-lg border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all" required />
                <p id="contactPersonError" class="hidden text-sm text-red-500 mt-1"></p>
            </div>

            <!-- Nomor Telepon -->
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">Nomor Telepon</label>
                <input id="phone" type="tel" placeholder="e.g., 021-5551234" class="w-full rounded-lg border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all" required />
                <p id="phoneError" class="hidden text-sm text-red-500 mt-1"></p>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                <input id="email" type="email" placeholder="e.g., info@bogasari.co.id" class="w-full rounded-lg border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all" />
                <p id="emailError" class="hidden text-sm text-red-500 mt-1"></p>
            </div>

            <!-- Alamat -->
            <div>
                <label for="address" class="block text-sm font-medium text-slate-700 mb-2">Alamat</label>
                <textarea id="address" placeholder="e.g., Jakarta Utara" class="w-full rounded-lg border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all" rows="3"></textarea>
                <p id="addressError" class="hidden text-sm text-red-500 mt-1"></p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="SupplierModule.closeModal()" class="flex-1 px-4 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button id="btnSubmit" type="submit" class="flex-1 px-4 py-2.5 rounded-lg bg-blue-500 text-white font-medium hover:bg-blue-600 transition-colors inline-flex items-center justify-center gap-2">
                    <span id="submitText">Simpan</span>
                    <svg id="submitSpinner" class="hidden w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" stroke-dasharray="15.7 47.1" /></svg>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full">
        <!-- Header -->
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-semibold text-slate-900">Hapus Supplier</h2>
        </div>

        <!-- Body -->
        <div class="p-6">
            <p class="text-slate-600">Apakah Anda yakin ingin menghapus supplier <strong id="deleteItemName"></strong>?</p>
            <p class="text-sm text-slate-500 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 p-6 border-t border-slate-100">
            <button onclick="SupplierModule.closeDeleteModal()" class="flex-1 px-4 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                Batal
            </button>
            <button id="btnConfirmDelete" onclick="SupplierModule.confirmDelete()" class="flex-1 px-4 py-2.5 rounded-lg bg-red-500 text-white font-medium hover:bg-red-600 transition-colors inline-flex items-center justify-center gap-2">
                <span id="deleteText">Hapus</span>
                <svg id="deleteSpinner" class="hidden w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" stroke-dasharray="15.7 47.1" /></svg>
            </button>
        </div>
    </div>
</div>

<!-- TOAST CONTAINER -->
<div id="toast" class="hidden fixed top-4 right-4 z-50 max-w-sm w-full">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 p-4 flex items-start gap-3">
        <div id="toastIcon" class="flex-shrink-0"></div>
        <div class="flex-1 min-w-0">
            <h4 id="toastTitle" class="font-semibold text-slate-900 text-sm"></h4>
            <p id="toastMessage" class="text-sm text-slate-600 mt-1"></p>
        </div>
        <button onclick="Toast.hide()" class="flex-shrink-0 text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<!-- SCRIPTS -->
<script src="/assets/js/utils/api.js"></script>
<script src="/assets/js/utils/toast.js"></script>
<script src="/assets/js/utils/validator.js"></script>
<script src="/assets/js/modules/supplier.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        console.log('DOM loaded, initializing SupplierModule...');
        SupplierModule.init();
    });
</script>
