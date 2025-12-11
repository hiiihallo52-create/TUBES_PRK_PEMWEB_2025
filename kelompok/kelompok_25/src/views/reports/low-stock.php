<section class="p-6 md:p-10 space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-slate-500 uppercase tracking-[0.3em]">Peringatan</p>
            <h1 class="text-2xl font-semibold text-slate-800 mt-1">Bahan Hampir Habis</h1>
            <p class="text-sm text-slate-500">Monitor dan kelola bahan yang perlu segera direstock</p>
        </div>
        <button onclick="LowStock.exportReport()" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
            <span>⬇</span> Export Report
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <article class="rounded-2xl bg-white shadow-sm border border-slate-100 p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-red-100 text-red-600 text-xl">⚠️</span>
            </div>
            <p class="text-sm text-slate-500">Total Bahan Low Stock</p>
            <p id="totalLowStock" class="text-2xl font-semibold text-slate-900">0</p>
        </article>

        <article class="rounded-2xl bg-white shadow-sm border border-slate-100 p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600 text-xl">🔴</span>
            </div>
            <p class="text-sm text-slate-500">Habis Total</p>
            <p id="outOfStock" class="text-2xl font-semibold text-red-600">0</p>
        </article>

        <article class="rounded-2xl bg-white shadow-sm border border-slate-100 p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 text-orange-600 text-xl">⚡</span>
            </div>
            <p class="text-sm text-slate-500">Critical</p>
            <p id="critical" class="text-2xl font-semibold text-orange-600">0</p>
        </article>

        <article class="rounded-2xl bg-white shadow-sm border border-slate-100 p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600 text-xl">📦</span>
            </div>
            <p class="text-sm text-slate-500">Total Kekurangan</p>
            <p id="totalShortage" class="text-2xl font-semibold text-slate-900">0 <span class="text-sm text-slate-500 font-normal">items</span></p>
        </article>
    </div>

    <!-- Quick Actions -->
    <div class="flex flex-wrap gap-3">
        <button onclick="LowStock.showUrgent()" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
            <span>🚨</span> Lihat Urgent (Habis Total)
        </button>
        <button onclick="LowStock.showReorderSuggestions()" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
            <span>📋</span> Saran Reorder
        </button>
        <button onclick="LowStock.showCategories()" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition">
            <span>📊</span> Per Kategori
        </button>
    </div>

    <!-- Filters -->
    <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-6">
        <div class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[250px] relative">
                <label class="block text-sm font-medium text-slate-700 mb-2">Cari Bahan</label>
                <input type="text" id="searchInput" placeholder="Nama atau kode bahan..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <svg class="absolute left-3 bottom-2.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <div class="min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 mb-2">Kategori</label>
                <select id="categoryFilter" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Kategori</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['id']) ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="onlyOutOfStock" class="w-4 h-4 text-red-600 border-slate-300 rounded focus:ring-red-500">
                    <span class="ml-2 text-sm text-slate-700">Hanya Habis Total</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Materials Table -->
    <div class="rounded-2xl bg-white border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Bahan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok Saat Ini</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Min Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kekurangan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="materialsTableBody" class="divide-y divide-slate-100">
                    <!-- Rows will be dynamically loaded here -->
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-12">
            <div class="text-slate-400 text-5xl mb-4">✅</div>
            <p class="text-slate-500">Tidak ada bahan yang perlu direstock</p>
            <p class="text-sm text-slate-400 mt-2">Semua stok bahan dalam kondisi aman</p>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            <p class="text-slate-500 mt-4">Memuat data...</p>
        </div>
    </div>

    <!-- Pagination -->
    <div id="pagination" class="hidden flex items-center justify-between">
        <p class="text-sm text-slate-600">
            Menampilkan <span id="showingStart">1</span> - <span id="showingEnd">20</span> dari <span id="totalItems">0</span> bahan
        </p>
        <div class="flex gap-2">
            <button id="prevPage" class="px-3 py-1 border border-slate-300 rounded-lg text-sm text-slate-600 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
                Sebelumnya
            </button>
            <button id="nextPage" class="px-3 py-1 border border-slate-300 rounded-lg text-sm text-slate-600 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
                Selanjutnya
            </button>
        </div>
    </div>
</section>

<!-- Urgent Modal -->
<div id="urgentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-slate-200 bg-red-50">
            <h2 class="text-xl font-semibold text-red-800">🚨 Bahan Habis Total (Urgent)</h2>
            <p class="text-sm text-red-600 mt-1">Bahan-bahan ini harus segera direstock!</p>
        </div>
        
        <div id="urgentContent" class="p-6">
            <!-- Content will be loaded here -->
        </div>

        <div class="p-6 border-t border-slate-200 flex justify-end">
            <button type="button" onclick="LowStock.hideUrgentModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 text-sm font-medium transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Reorder Suggestions Modal -->
<div id="reorderModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-5xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-slate-200 bg-emerald-50">
            <h2 class="text-xl font-semibold text-emerald-800">📋 Saran Reorder</h2>
            <p class="text-sm text-emerald-600 mt-1">Rekomendasi jumlah pembelian untuk memenuhi stok minimum</p>
        </div>
        
        <div id="reorderContent" class="p-6">
            <!-- Content will be loaded here -->
        </div>

        <div class="p-6 border-t border-slate-200 flex justify-end gap-3">
            <button type="button" onclick="LowStock.hideReorderModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 text-sm font-medium transition">
                Tutup
            </button>
            <button type="button" onclick="LowStock.printReorder()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-medium transition">
                Print / Export
            </button>
        </div>
    </div>
</div>

<!-- Categories Modal -->
<div id="categoriesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-slate-200 bg-purple-50">
            <h2 class="text-xl font-semibold text-purple-800">📊 Low Stock Per Kategori</h2>
            <p class="text-sm text-purple-600 mt-1">Ringkasan bahan low stock berdasarkan kategori</p>
        </div>
        
        <div id="categoriesContent" class="p-6">
            <!-- Content will be loaded here -->
        </div>

        <div class="p-6 border-t border-slate-200 flex justify-end">
            <button type="button" onclick="LowStock.hideCategoriesModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 text-sm font-medium transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="hidden fixed top-4 right-4 z-50 bg-white rounded-lg shadow-lg border border-slate-200 p-4 min-w-[300px] transition-all">
    <div class="flex items-start gap-3">
        <div id="toastIcon" class="flex-shrink-0"></div>
        <div class="flex-1">
            <p id="toastTitle" class="text-sm font-semibold text-slate-800"></p>
            <p id="toastMessage" class="text-sm text-slate-600 mt-1"></p>
        </div>
        <button onclick="LowStock.hideToast()" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
</div>

<script src="/assets/js/modules/low-stock.js"></script>
