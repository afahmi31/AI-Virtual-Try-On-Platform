<x-layouts.app :title="'Login'">
    <div class="card" style="max-width: 480px; margin: 50px auto;">
        <h1>Login Core App</h1>
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p>Admin default: admin@tryon.test / password</p>
        <p>Seller default: seller@tryon.test / password</p>
    </div>
</x-layouts.app>