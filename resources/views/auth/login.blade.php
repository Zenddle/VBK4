@extends('layouts.app')
@section('title', 'Masuk')

@section('content')
<div class="relative isolate flex min-h-[76vh] items-center justify-center" x-data="{ tab: '{{ old('type', 'student') }}' }">
    {{-- Cahaya lampu baca di atas kartu --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 -top-6 -z-10 mx-auto h-72 max-w-lg rounded-full bg-lamp-400/25 blur-3xl"></div>

    <div class="w-full max-w-md">
        <div class="overflow-hidden rounded-2xl border border-ink-600/70 bg-ink-800/90 shadow-2xl shadow-black/50 backdrop-blur">
            <div class="px-8 pb-5 pt-9 text-center">
                <div class="mb-4 flex justify-center">
                    <span class="grid h-12 w-12 place-items-center rounded-xl bg-lamp-400 text-ink-950" aria-hidden="true">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/>
                        </svg>
                    </span>
                </div>
                <h1 class="font-display text-2xl font-semibold tracking-tight text-fog-100">Masuk Perpustakaan</h1>
                <p class="mt-1 text-sm text-fog-400">Silakan masuk untuk melanjutkan</p>
            </div>

            <!-- Tabs -->
            <div class="mx-8 grid grid-cols-2 gap-1 rounded-lg bg-ink-900 p-1" role="tablist">
                <button type="button" role="tab" @click="tab = 'student'" :aria-selected="tab === 'student'"
                        :class="tab === 'student' ? 'bg-ink-700 text-fog-100 shadow-sm' : 'text-fog-400 hover:text-fog-200'"
                        class="rounded-md py-2 text-sm font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-lamp-400/60">
                    Mahasiswa
                </button>
                <button type="button" role="tab" @click="tab = 'admin'" :aria-selected="tab === 'admin'"
                        :class="tab === 'admin' ? 'bg-ink-700 text-fog-100 shadow-sm' : 'text-fog-400 hover:text-fog-200'"
                        class="rounded-md py-2 text-sm font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-lamp-400/60">
                    Admin
                </button>
            </div>

            <div class="p-8">
                <!-- Student form -->
                <form x-show="tab === 'student'" method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="student">
                    <div>
                        <label for="student-identifier" class="label">NIM</label>
                        <input id="student-identifier" name="identifier" value="{{ old('type') === 'student' ? old('identifier') : '' }}"
                               class="field" placeholder="Masukkan NIM" autocomplete="username" inputmode="numeric">
                    </div>
                    <div>
                        <label for="student-password" class="label">Kata Sandi</label>
                        <input id="student-password" type="password" name="password"
                               class="field" placeholder="Masukkan kata sandi" autocomplete="current-password">
                    </div>
                    <label class="flex items-center gap-2 text-sm text-fog-300">
                        <input type="checkbox" name="remember" class="h-4 w-4">
                        Ingat saya
                    </label>
                    <button type="submit" class="btn btn-primary w-full">
                        Masuk sebagai Mahasiswa
                    </button>
                </form>

                <!-- Admin form -->
                <form x-show="tab === 'admin'" x-cloak method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="admin">
                    <div>
                        <label for="admin-identifier" class="label">Nama Pengguna</label>
                        <input id="admin-identifier" name="identifier" value="{{ old('type') === 'admin' ? old('identifier') : '' }}"
                               class="field" placeholder="Masukkan nama pengguna" autocomplete="username">
                    </div>
                    <div>
                        <label for="admin-password" class="label">Kata Sandi</label>
                        <input id="admin-password" type="password" name="password"
                               class="field" placeholder="Masukkan kata sandi" autocomplete="current-password">
                    </div>
                    <button type="submit" class="btn btn-primary w-full">
                        Masuk sebagai Admin
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
