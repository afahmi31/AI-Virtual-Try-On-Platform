<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'AI Try-On' }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f5f7fb; color: #1f2937; }
        header { background: #111827; color: #fff; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: #fff; margin-right: 12px; text-decoration: none; }
        main { padding: 20px; max-width: 1100px; margin: 0 auto; }
        .card { background: #fff; border-radius: 8px; padding: 16px; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 8px; text-align: left; vertical-align: top; }
        input, select, button { padding: 8px; width: 100%; margin: 4px 0; box-sizing: border-box; }
        button { width: auto; cursor: pointer; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(180px,1fr)); gap: 12px; }
        .text-danger { color: #b91c1c; }
        .text-success { color: #065f46; }
        .inline { display: inline; }
    </style>
</head>
<body>
<header>
    <div><strong>AI Try-On Core App</strong></div>
    <nav>
        @auth
            <a href="{{ route('seller.dashboard') }}">Dashboard</a>
            <a href="{{ route('seller.products.index') }}">Products</a>
            <a href="{{ route('seller.settings.index') }}">Settings</a>
            @php $mySeller = auth()->user()->seller; @endphp
            @if($mySeller)
                <a href="/{{ $mySeller->slug }}" target="_blank">Open Store</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @endauth
    </nav>
</header>
<main>
    @if(session('success'))
        <p class="text-success">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <div class="card text-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{ $slot }}
</main>
</body>
</html>
