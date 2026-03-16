@extends('web.layout')

@section('title', 'Entrar – App Cifras')

@section('content')
<div class="min-h-[80vh] w-full flex items-center justify-center px-4 sm:px-6" x-data="{ tab: 'login' }">
    <div class="w-full max-w-md">
        <h1 class="font-orbitron text-3xl font-bold text-gray-900 dark:text-space-100 text-center mb-2">App Cifras</h1>
        <p class="text-gray-600 dark:text-space-400 text-center font-exo text-sm mb-8">Entre ou cadastre-se para continuar</p>

        <div class="flex rounded-xl overflow-hidden border border-gray-200 dark:border-space-600/50 bg-gray-100 dark:bg-space-900/50 p-1 mb-6">
            <button type="button" @click="tab = 'login'" :class="tab === 'login' ? 'bg-space-500 text-white' : 'text-gray-600 dark:text-space-400 hover:text-gray-900 dark:hover:text-space-200'" class="flex-1 py-3 font-exo font-semibold rounded-lg transition-colors">Entrar</button>
            <button type="button" @click="tab = 'register'" :class="tab === 'register' ? 'bg-space-500 text-white' : 'text-gray-600 dark:text-space-400 hover:text-gray-900 dark:hover:text-space-200'" class="flex-1 py-3 font-exo font-semibold rounded-lg transition-colors">Cadastrar</button>
        </div>

        {{-- Form Login --}}
        <form method="POST" action="{{ route('web.login.submit') }}" class="space-y-4 bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 rounded-2xl p-6 shadow-lg" x-show="tab === 'login'" x-cloak>
            @csrf
            <div>
                <label for="login-email" class="text-gray-700 dark:text-space-200 font-exo block mb-1">Email</label>
                <input id="login-email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 focus:ring-2 focus:ring-space-500"
                    placeholder="seu@email.com">
                @error('email')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="login-password" class="text-gray-700 dark:text-space-200 font-exo block mb-1">Senha</label>
                <input id="login-password" type="password" name="password" required
                    class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 focus:ring-2 focus:ring-space-500"
                    placeholder="••••••••">
                @error('password')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="w-full bg-space-500 hover:bg-space-600 text-white font-orbitron font-semibold rounded-xl py-3 focus:ring-2 focus:ring-space-400">Entrar</button>
        </form>

        {{-- Form Register --}}
        <form method="POST" action="{{ route('web.register.submit') }}" class="space-y-4 bg-white dark:bg-space-800/80 border border-gray-200 dark:border-space-600/50 rounded-2xl p-6 shadow-lg mt-6" x-show="tab === 'register'" x-cloak>
            @csrf
            <div>
                <label for="register-name" class="text-gray-700 dark:text-space-200 font-exo block mb-1">Nome</label>
                <input id="register-name" type="text" name="name" value="{{ old('name') }}" required
                    class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 focus:ring-2 focus:ring-space-500"
                    placeholder="Seu nome">
                @error('name')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="register-email" class="text-gray-700 dark:text-space-200 font-exo block mb-1">Email</label>
                <input id="register-email" type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 focus:ring-2 focus:ring-space-500"
                    placeholder="seu@email.com">
                @error('email')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="register-password" class="text-gray-700 dark:text-space-200 font-exo block mb-1">Senha</label>
                <input id="register-password" type="password" name="password" required minlength="6"
                    class="w-full rounded-xl bg-gray-50 dark:bg-space-900 border border-gray-200 dark:border-space-600 text-gray-900 dark:text-space-100 px-4 py-2.5 focus:ring-2 focus:ring-space-500"
                    placeholder="••••••••">
                @error('password')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="w-full bg-space-500 hover:bg-space-600 text-white font-orbitron font-semibold rounded-xl py-3 focus:ring-2 focus:ring-space-400">Cadastrar</button>
        </form>
    </div>
</div>
@endsection
