<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-[#F5F7FB]">

<div class="flex">

    <!-- SIDEBAR -->
    <aside class="w-[260px] bg-white h-screen fixed border-r border-gray-200 p-5 overflow-y-auto">

        <!-- Logo -->
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-md bg-blue-600 flex items-center justify-center">
                <i class="ri-box-3-line text-white text-xl"></i>
            </div>
            <div>
                <h3 class="font-semibold text-[16px]">Inventory Manager</h3>
                <p class="text-[12px] text-gray-500">Sistem Manajemen Stok Bahan Baku</p>
            </div>
        </div>

        <!-- Menu -->
        <ul class="space-y-1">

            <p class="font-semibold text-gray-600 mb-1">Dashboard</p>

            <p class="text-xs text-gray-500 uppercase mt-4 font-medium">Data Master</p>
            <li class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-100 cursor-pointer">
                <i class="ri-archive-line"></i>Bahan Baku
            </li>
            <li class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-100 cursor-pointer">
                <i class="ri-user-3-line"></i>Supplier
            </li>
            <li class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-100 cursor-pointer">
                <i class="ri-price-tag-3-line"></i>Kategori
            </li>

            <p class="text-xs text-gray-500 uppercase mt-4 font-medium">Transaksi Stok</p>
            <li class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-100 cursor-pointer">
                <i class="ri-download-2-line"></i>Stok Masuk
            </li>
            <li class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-100 cursor-pointer">
                <i class="ri-upload-2-line"></i>Stok Keluar
            </li>
            <li class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-100 cursor-pointer">
                <i class="ri-refresh-line"></i>Penyesuaian Stok
            </li>

            <p class="text-xs text-gray-500 uppercase mt-4 font-medium">Laporan</p>
            <li class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-100 cursor-pointer">
                <i class="ri-file-list-3-line"></i>Laporan Stok
            </li>
            <li class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-100 cursor-pointer">
                <i class="ri-survey-line"></i>Laporan Transaksi
            </li>
            <li class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-100 cursor-pointer">
                <i class="ri-error-warning-line"></i>Bahan Hampir Habis
            </li>

            <p class="text-xs text-gray-500 uppercase mt-4 font-medium">Manajemen Role</p>

            <!-- ACTIVE -->
            <li class="flex items-center gap-2 p-2 rounded-md cursor-pointer text-white 
                       bg-gradient-to-r from-[#0D5CFF] to-[#7B2BFF]">
                <i class="ri-user-settings-line"></i>Profil Saya
            </li>

            <li class="flex items-center gap-2 p-2 rounded-md cursor-pointer text-red-500 mt-3">
                <i class="ri-logout-box-r-line"></i>Logout
            </li>
        </ul>
    </aside>


    <!-- MAIN CONTENT -->
    <main class="ml-[260px] w-full p-10">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-5">
            <div></div>
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="font-semibold">Admin User</p>
                    <p class="text-xs text-gray-500">Owner / Admin</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center font-semibold">
                    A
                </div>
            </div>
        </div>

        <h1 class="text-2xl font-semibold">Profil Saya</h1>
        <p class="text-gray-500 mt-1">Kelola informasi akun dan keamanan Anda</p>

        <!-- PROFILE CARD -->
        <div class="bg-gradient-to-r from-[#0D5CFF] to-[#7B2BFF] p-6 rounded-xl mt-6 text-white flex justify-between items-center">

            <div class="flex items-center">
                <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center text-[#0D5CFF] text-3xl font-bold mr-4">
                    A
                </div>
                <div>
                    <h2 class="font-semibold text-lg">Admin User</h2>
                    <p class="opacity-90">scss2wfgcgf@gjsdjs.ck</p>
                </div>
            </div>

            <div class="text-right">
                <p class="opacity-90">Bergabung sejak</p>
                <h3 class="font-semibold text-lg">15 Januari 2024</h3>
            </div>
        </div>

        <!-- TAB -->
        <div class="flex mt-6 border-b">
            <button class="px-5 py-3 border-b-2 border-blue-600 text-blue-600 font-semibold flex items-center gap-2">
                <i class="ri-user-line"></i>Edit Profil
            </button>
            <button class="px-5 py-3 text-gray-500 hover:text-blue-600 flex items-center gap-2">
                <i class="ri-lock-password-line"></i>Ganti Password
            </button>
            <button class="px-5 py-3 text-gray-500 hover:text-blue-600 flex items-center gap-2">
                <i class="ri-history-line"></i>Aktivitas Log
            </button>
        </div>

        <!-- FORM -->
        <div class="bg-white border rounded-xl p-6 mt-5 shadow-sm">

            <div class="grid grid-cols-2 gap-5">

                <div>
                    <label class="text-sm font-semibold">Nama Lengkap</label>
                    <input type="text" value="Admin User" 
                           class="w-full p-2 border rounded-md mt-1">
                </div>

                <div>
                    <label class="text-sm font-semibold">Email</label>
                    <input type="text" value="scss2wfgcgf@gjsdjs.ck" 
                           class="w-full p-2 border rounded-md mt-1">
                </div>

                <div>
                    <label class="text-sm font-semibold">No. Telepon</label>
                    <input type="text" value="0812-3456-7890" 
                           class="w-full p-2 border rounded-md mt-1">
                </div>

                <div>
                    <label class="text-sm font-semibold">Perusahaan</label>
                    <input type="text" value="PT Inventory Sejahtera" 
                           class="w-full p-2 border rounded-md mt-1">
                </div>

                <div class="col-span-2">
                    <label class="text-sm font-semibold">Alamat</label>
                    <textarea class="w-full p-2 border rounded-md mt-1 h-24"></textarea>
                </div>
            </div>

            <button class="mt-6 px-5 py-3 bg-gradient-to-r from-[#0D5CFF] to-[#7B2BFF] text-white font-semibold rounded-lg flex items-center gap-2">
                <i class="ri-save-3-line"></i> Simpan Perubahan
            </button>

        </div>

    </main>

</div>

</body>
</html>
