<section class="p-6 md:p-10 space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-slate-500 uppercase tracking-[0.3em]">Manajemen</p>
            <h1 class="text-2xl font-semibold text-slate-800 mt-1">Manajemen Pengguna</h1>
            <p class="text-sm text-slate-500">Kelola pengguna dan peran akses sistem</p>
        </div>
        <button onclick="UserManagement.showCreateModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
            <span>+</span> Tambah User
        </button>
    </div>

    <!-- Tabs -->
    <div class="border-b border-slate-200">
        <nav class="flex gap-8">
            <a href="/roles" class="border-b-2 border-transparent text-slate-500 hover:text-slate-700 py-3 text-sm font-medium">
                Roles
            </a>
            <a href="/users" class="border-b-2 border-indigo-600 text-indigo-600 py-3 text-sm font-medium">
                Users
            </a>
        </nav>
    </div>

    <!-- Search & Filter Bar -->
    <div class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[250px] relative">
            <input type="text" id="searchInput" placeholder="Cari user..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <svg class="absolute left-3 top-2.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <select id="roleFilter" class="px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Semua Role</option>
            <!-- Options will be dynamically loaded -->
        </select>
        <select id="statusFilter" class="px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Semua Status</option>
            <option value="1">Aktif</option>
            <option value="0">Tidak Aktif</option>
        </select>
    </div>

    <!-- User Cards Grid -->
    <div id="userGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Cards will be dynamically loaded here -->
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="hidden text-center py-12">
        <div class="text-slate-400 text-5xl mb-4">👤</div>
        <p class="text-slate-500">Tidak ada user ditemukan</p>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        <p class="text-slate-500 mt-4">Memuat data...</p>
    </div>

    <!-- Pagination -->
    <div id="pagination" class="hidden flex items-center justify-between">
        <p class="text-sm text-slate-600">
            Menampilkan <span id="showingStart">1</span> - <span id="showingEnd">10</span> dari <span id="totalUsers">0</span> user
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

<!-- Create/Edit User Modal -->
<div id="userModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-slate-200">
            <h2 id="modalTitle" class="text-xl font-semibold text-slate-800">Tambah User Baru</h2>
        </div>
        
        <form id="userForm" class="p-6 space-y-4">
            <input type="hidden" id="userId" name="id">
            
            <!-- Avatar Upload -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Foto Profil</label>
                <div class="flex items-center gap-4">
                    <div id="userAvatarPreview" class="w-16 h-16 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl font-bold border-2 border-slate-300">
                        👤
                    </div>
                    <div>
                        <input type="file" id="userAvatarInput" accept="image/*" class="hidden">
                        <button type="button" onclick="document.getElementById('userAvatarInput').click()" class="px-3 py-1.5 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition">
                            Pilih Foto
                        </button>
                        <p class="text-xs text-slate-500 mt-1">JPG, PNG max 2MB (Opsional)</p>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="userName" name="name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="e.g., Ahmad Yani">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Email <span class="text-red-500">*</span></label>
                <input type="email" id="userEmail" name="email" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="e.g., ahmad@inventory.com">
            </div>

            <div id="passwordField">
                <label class="block text-sm font-medium text-slate-700 mb-2">Password <span class="text-red-500" id="passwordRequired">*</span></label>
                <input type="password" id="userPassword" name="password" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Minimal 6 karakter">
                <p class="text-xs text-slate-500 mt-1" id="passwordHint">Minimal 6 karakter</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Role <span class="text-red-500">*</span></label>
                <select id="userRole" name="role_id" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Pilih Role</option>
                    <!-- Options will be dynamically loaded -->
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="userIsActive" name="is_active" checked class="sr-only peer">
                    <div class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    <span class="ms-3 text-sm text-slate-700">Aktif</span>
                </label>
            </div>
        </form>

        <div class="p-6 border-t border-slate-200 flex justify-end gap-3">
            <button type="button" onclick="UserManagement.hideModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 text-sm font-medium transition">
                Batal
            </button>
            <button type="button" onclick="UserManagement.saveUser()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium transition">
                Simpan
            </button>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Reset Password</h3>
        <form id="resetPasswordForm" class="space-y-4">
            <input type="hidden" id="resetUserId">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Password Baru <span class="text-red-500">*</span></label>
                <input type="password" id="newPassword" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Minimal 6 karakter">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" id="newPasswordConfirmation" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Ketik ulang password">
            </div>
        </form>
        <div class="flex gap-3 mt-6">
            <button type="button" onclick="UserManagement.hideResetPasswordModal()" class="flex-1 px-4 py-2 text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 text-sm font-medium transition">
                Batal
            </button>
            <button type="button" onclick="UserManagement.confirmResetPassword()" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium transition">
                Reset Password
            </button>
        </div>
    </div>
</div>

<!-- Deactivate Confirmation Modal -->
<div id="deactivateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 mb-4">
                <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Nonaktifkan User</h3>
            <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menonaktifkan user ini? User tidak akan dapat login ke sistem.</p>
        </div>
        <div class="flex gap-3">
            <button type="button" onclick="UserManagement.hideDeactivateModal()" class="flex-1 px-4 py-2 text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 text-sm font-medium transition">
                Batal
            </button>
            <button type="button" onclick="UserManagement.confirmDeactivate()" class="flex-1 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 text-sm font-medium transition">
                Nonaktifkan
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
        <button onclick="UserManagement.hideToast()" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
</div>

<script src="/assets/js/modules/user-management.js"></script>