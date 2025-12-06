<?php
$suppliers = [
    ['name' => 'PT Bogasari', 'person' => 'Budi Santoso', 'phone' => '021-5551234', 'email' => 'info@bogasari.co.id', 'address' => 'Jakarta Utara', 'category' => 'Tepung', 'color' => 'from-blue-500 to-blue-400'],
    ['name' => 'CV Sumber Manis', 'person' => 'Siti Aminah', 'phone' => '021-5559876', 'email' => 'cs@sumbermanis.com', 'address' => 'Bekasi', 'category' => 'Gula', 'color' => 'from-purple-500 to-fuchsia-500'],
    ['name' => 'CV Sumber Rejeki', 'person' => 'Ahmad Yani', 'phone' => '021-5554567', 'email' => 'contact@sumrerejeki.com', 'address' => 'Tangerang', 'category' => 'Minyak', 'color' => 'from-emerald-500 to-green-500'],
    ['name' => 'PT Coklat Nusantara', 'person' => 'Rina Dewi', 'phone' => '021-5558901', 'email' => 'sales@coklatnusantara.com', 'address' => 'Jakarta Selatan', 'category' => 'Coklat', 'color' => 'from-orange-500 to-amber-500'],
];
?>

<section class="p-6 md:p-10 space-y-6">
    <div class="flex flex-col gap-2">
        <h1 class="text-2xl font-semibold text-slate-900">Data Supplier</h1>
        <p class="text-sm text-slate-500">Kelola data supplier dan kontak mereka</p>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
        <div class="relative flex-1">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" /></svg>
            </span>
            <input type="text" placeholder="Cari supplier berdasarkan nama atau kategori..." class="w-full rounded-2xl border border-slate-200 pl-12 pr-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-400" />
        </div>
        <button class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-pink-500 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5" /></svg>
            Tambah Supplier
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <?php foreach ($suppliers as $supplier): ?>
            <article class="rounded-3xl border border-slate-100 bg-white shadow-sm p-5 flex flex-col gap-4">
                <div class="flex items-start gap-4">
                    <div class="h-12 w-12 rounded-2xl text-white flex items-center justify-center text-xl font-semibold bg-gradient-to-br <?= $supplier['color'] ?>">
                        <?= strtoupper(substr($supplier['name'], 0, 1)) ?>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-slate-400"><?= e($supplier['person']) ?></p>
                        <h2 class="text-lg font-semibold text-slate-900"><?= e($supplier['name']) ?></h2>
                        <p class="text-xs text-slate-400 uppercase tracking-wide mt-1"><?= e($supplier['category']) ?></p>
                    </div>
                    <div class="flex items-center gap-3 text-slate-400">
                        <button title="Edit" class="hover:text-blue-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5h2m-1 14v-4m0 0l-4-4m4 4l4-4" /></svg>
                        </button>
                        <button title="Hapus" class="hover:text-rose-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V4h6v3m2 0v12a2 2 0 01-2 2H8a2 2 0 01-2-2V7h12z" /></svg>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 text-sm text-slate-600">
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m-6 7h12m-6-2v2m-6 7h12m-6-2v2m4 3l4-4-4-4" /></svg>
                        </span>
                        <?= e($supplier['phone']) ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v16H4z" /></svg>
                        </span>
                        <?= e($supplier['email']) ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" /></svg>
                        </span>
                        <?= e($supplier['address']) ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
