<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <meta name="theme-color" content="#0a0f1c">
    <title>@yield('title', 'Perpustakaan') — Perpustakaan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Literata:opsz,wght@7..72,500..700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            // class "dark" di <html> membuat varian dark: bawaan pagination Laravel ikut aktif
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        // latar & permukaan (biru malam)
                        ink: {
                            950: '#0a0f1c',
                            900: '#0f1526',
                            800: '#151c32',
                            700: '#1d2641',
                            600: '#2a3558',
                            500: '#3b4872',
                        },
                        // teks
                        fog: {
                            100: '#eceef6',
                            200: '#d3d8e8',
                            300: '#b1b8d0',
                            400: '#8b93b0',
                            500: '#7a82a2',
                        },
                        // aksen: cahaya lampu baca
                        lamp: {
                            300: '#f8d58a',
                            400: '#f4c15d',
                            500: '#eeab3a',
                            600: '#d6931f',
                        },
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['Literata', 'Georgia', 'serif'],
                    },
                },
            },
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
    <style type="text/tailwindcss">
        @layer base {
            html { color-scheme: dark; }
            ::selection { background: rgb(244 193 93 / .3); }
            input[type="checkbox"], input[type="radio"] { accent-color: theme('colors.lamp.400'); }
        }

        @layer components {
            /* ---------- Teks halaman ---------- */
            .page-head   { @apply mb-6; }
            .page-title  { @apply font-display text-2xl sm:text-3xl font-semibold tracking-tight text-fog-100; }
            .page-sub    { @apply mt-1 text-sm text-fog-400; }
            .section-title { @apply font-display text-lg font-semibold text-fog-100; }

            /* ---------- Permukaan ---------- */
            .panel       { @apply rounded-xl border border-ink-600/70 bg-ink-800; }
            .table-wrap  { @apply overflow-x-auto rounded-xl border border-ink-600/70 bg-ink-800; }

            /* ---------- Tabel (:where agar utilitas seperti text-right tetap menang) ---------- */
            .data-table  { @apply min-w-full text-sm text-fog-300; }
            :where(.data-table) thead tr { @apply border-b border-ink-600/70 bg-ink-700/40 text-left text-fog-400; }
            :where(.data-table) th       { @apply whitespace-nowrap px-4 py-3 font-medium; }
            :where(.data-table) td       { @apply px-4 py-3; }
            :where(.data-table) tbody tr { @apply border-t border-ink-600/50 transition-colors; }
            :where(.data-table) tbody tr:first-child { @apply border-t-0; }
            :where(.data-table) tbody tr:hover       { @apply bg-ink-700/30; }
            .data-table tbody tr.is-overdue              { @apply bg-rose-500/[0.07]; }
            .data-table tbody tr.is-overdue:hover        { @apply bg-rose-500/10; }
            .data-table tbody tr.is-overdue td:first-child { box-shadow: inset 3px 0 0 theme('colors.rose.400'); }

            /* ---------- Form ---------- */
            .label  { @apply mb-1.5 block text-sm font-medium text-fog-300; }
            .field  { @apply w-full rounded-lg border border-ink-600 bg-ink-900 px-3.5 py-2.5 text-sm text-fog-100 transition placeholder:text-fog-500 focus:border-lamp-400/70 focus:outline-none focus:ring-2 focus:ring-lamp-400/30; }
            .field-sm { @apply px-3 py-1.5 text-xs; }

            /* ---------- Tombol ---------- */
            .btn { @apply inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-lamp-400/70 focus-visible:ring-offset-2 focus-visible:ring-offset-ink-900; }
            .btn-sm        { @apply px-3 py-1.5 text-xs; }
            .btn-primary   { @apply bg-lamp-400 text-ink-950 hover:bg-lamp-300; }
            .btn-success   { @apply bg-emerald-400 text-ink-950 hover:bg-emerald-300; }
            .btn-secondary { @apply border border-ink-500 text-fog-200 hover:bg-ink-700 hover:text-fog-100; }
            .btn-accent    { @apply border border-lamp-400/30 text-lamp-300 hover:bg-lamp-400/10; }
            .btn-danger    { @apply border border-rose-400/30 text-rose-300 hover:bg-rose-400/10; }
            .btn-ghost     { @apply text-fog-400 hover:bg-ink-700/60 hover:text-fog-100; }
            .btn-disabled  { @apply cursor-not-allowed bg-ink-700 text-fog-500; }

            /* ---------- Lencana ---------- */
            .badge          { @apply inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset; }
            .badge-pending  { @apply bg-amber-400/10 text-amber-300 ring-amber-400/25; }
            .badge-approved { @apply bg-emerald-400/10 text-emerald-300 ring-emerald-400/25; }
            .badge-declined { @apply bg-fog-400/10 text-fog-300 ring-fog-400/20; }
            .badge-returned { @apply bg-sky-400/10 text-sky-300 ring-sky-400/25; }
            .badge-overdue  { @apply bg-rose-400/10 text-rose-300 ring-rose-400/25; }
            .badge-neutral  { @apply bg-ink-700 text-fog-300 ring-ink-500; }
            .badge-admin    { @apply bg-lamp-400/10 text-lamp-300 ring-lamp-400/25; }

            /* ---------- Navigasi ---------- */
            .nav-link { @apply rounded-lg px-3 py-2 text-sm font-medium text-fog-400 transition hover:bg-ink-700/70 hover:text-fog-100; }
            .nav-link[aria-current="page"] { @apply bg-ink-700 text-fog-100; }

            /* ---------- Modal ---------- */
            .modal-backdrop { @apply fixed inset-0 bg-ink-950/75 backdrop-blur-sm; }
            .modal-panel    { @apply relative w-full rounded-2xl border border-ink-600 bg-ink-800 text-left shadow-2xl shadow-black/60; }
        }
    </style>
</head>
<body class="min-h-screen bg-ink-950 font-sans text-fog-200 antialiased">
    @yield('nav')
    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
        @include('partials.flash')
        @yield('content')
    </main>
</body>
</html>
