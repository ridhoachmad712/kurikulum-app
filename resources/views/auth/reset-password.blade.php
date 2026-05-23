<x-guest-layout>
    <div class="mb-6">
        <h1 class="font-display font-bold text-xl text-gray-900">Buat Kata Sandi Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Pilih kata sandi yang kuat dan mudah diingat.</p>
    </div>

    @if ($errors->any())
    <div class="mb-5 flex items-start gap-2 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span>{{ $errors->first() }}</span>
    </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
            <input id="email" name="email" type="email"
                   value="{{ old('email', $request->email) }}" required autofocus
                   class="form-input @error('email') border-red-300 @enderror">
            @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi Baru</label>
            <input id="password" name="password" type="password" required
                   placeholder="Minimal 8 karakter"
                   class="form-input @error('password') border-red-300 @enderror">
            @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   placeholder="Ulangi kata sandi baru"
                   class="form-input @error('password_confirmation') border-red-300 @enderror">
            @error('password_confirmation')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-4 py-3
                       bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                       rounded-xl transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Simpan Kata Sandi Baru
        </button>
    </form>
</x-guest-layout>
