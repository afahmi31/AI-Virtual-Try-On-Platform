<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sellers - AI Try-On Core App</title>
    <style>
        :root {
            --bg: #060b14;
            --panel: rgba(16, 25, 40, 0.92);
            --panel-border: rgba(80, 180, 255, 0.25);
            --text: #e6edf7;
            --muted: #9db0c8;
            --primary: #22d3ee;
            --success: #2dd4bf;
            --danger: #f87171;
            --overlay: rgba(2, 7, 14, 0.72);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 20% 20%, rgba(34, 211, 238, 0.2), transparent 30%),
                radial-gradient(circle at 80% 70%, rgba(59, 130, 246, 0.2), transparent 25%),
                var(--bg);
        }
        .topbar {
            height: 74px;
            padding: 0 24px;
            border-bottom: 1px solid rgba(120, 170, 255, 0.25);
            background: linear-gradient(90deg, #0b162f, #0a1b3d);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .brand { font-size: 32px; font-weight: 700; display: flex; gap: 12px; align-items: center; }
        .brand-dot { width: 36px; height: 36px; border-radius: 10px; background: rgba(34,211,238,.15); display: inline-flex; align-items: center; justify-content: center; color: var(--primary); }
        .topnav { display: flex; gap: 16px; align-items: center; }
        .topnav a, .topnav button { color: var(--text); text-decoration: none; border: 1px solid transparent; padding: 10px 14px; border-radius: 10px; background: rgba(255,255,255,.04); font-size: 24px; }
        .topnav button { cursor: pointer; }
        .layout { display: grid; grid-template-columns: 280px 1fr; min-height: calc(100vh - 74px); }
        .sidebar { border-right: 1px solid rgba(120,170,255,.2); background: linear-gradient(180deg, rgba(11,18,32,.9), rgba(8,14,24,.95)); padding: 18px; }
        .menu-item { display: flex; align-items: center; color: var(--muted); text-decoration: none; padding: 14px 16px; border-radius: 10px; margin-bottom: 10px; font-size: 28px; }
        .menu-item.active { color: var(--primary); background: rgba(34,211,238,.12); border: 1px solid rgba(34,211,238,.3); }
        .content { padding: 26px; }
        h1 { font-size: 44px; margin: 0 0 20px; }
        .panel { background: var(--panel); border: 1px solid var(--panel-border); border-radius: 14px; padding: 18px; box-shadow: inset 0 0 32px rgba(56,189,248,.08), 0 8px 28px rgba(0,0,0,.35); margin-bottom: 18px; }
        .panel-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .panel h2 { font-size: 36px; margin: 0 0 16px; }
        .btn { border: none; border-radius: 12px; padding: 12px 20px; font-size: 26px; cursor: pointer; }
        .btn-primary { background: linear-gradient(180deg, #35e5ef, #1ac6d7); color: #052a31; font-weight: 700; box-shadow: 0 0 24px rgba(34,211,238,.4); }
        .btn-ghost { background: rgba(34,211,238,.14); color: var(--primary); border: 1px solid rgba(34,211,238,.35); }
        .btn-small { font-size: 22px; padding: 8px 12px; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; font-size: 24px; }
        th, td { padding: 12px 10px; border-bottom: 1px solid rgba(130,170,230,.18); text-align: left; vertical-align: top; }
        th { color: #b9c7da; font-weight: 600; font-size: 20px; background: rgba(255,255,255,0.03); }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 18px; border: 1px solid rgba(45,212,191,.45); color: var(--success); background: rgba(45,212,191,.12); }
        .status-suspended { color: #f9c74f; border-color: rgba(249,199,79,.45); background: rgba(249,199,79,.15); }
        .status-inactive { color: var(--danger); border-color: rgba(248,113,113,.45); background: rgba(248,113,113,.15); }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .flash { margin-bottom: 14px; padding: 12px 14px; border-radius: 10px; font-size: 22px; }
        .flash-success { border: 1px solid rgba(45,212,191,.45); color: #78f6dc; background: rgba(45,212,191,.12); }
        .flash-error { border: 1px solid rgba(248,113,113,.45); color: #fecaca; background: rgba(248,113,113,.13); }

        .modal {
            position: fixed;
            inset: 0;
            background: var(--overlay);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 16px;
        }
        .modal.active { display: flex; }
        .modal-card {
            width: min(960px, 100%);
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 14px;
            box-shadow: 0 10px 28px rgba(0,0,0,.45);
            padding: 18px;
        }
        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }
        .modal-title { font-size: 32px; margin: 0; }
        .close-btn { background: transparent; color: var(--muted); border: 1px solid rgba(157,176,200,.3); border-radius: 10px; padding: 8px 12px; font-size: 18px; cursor: pointer; }
        .form-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px 18px; }
        label { font-size: 22px; color: #cad7ea; display: block; margin-bottom: 6px; }
        input, select { width: 100%; height: 52px; border-radius: 10px; border: 1px solid rgba(54, 198, 230, .45); background: rgba(6, 14, 26, .65); color: var(--text); padding: 0 12px; font-size: 25px; }
        .modal-actions { margin-top: 16px; display: flex; gap: 10px; justify-content: flex-end; }

        @media (max-width: 1400px) {
            .layout { grid-template-columns: 96px 1fr; }
            .menu-item span { display: none; }
            .topnav a, .topnav button { font-size: 16px; }
            .brand { font-size: 22px; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<header class="topbar">
    <div class="brand"><span class="brand-dot">AI</span>AI Try-On Core App</div>
    <nav class="topnav">
        <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
        <a href="{{ route('admin.sellers.index') }}">Sellers</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>
</header>

<div class="layout">
    <aside class="sidebar">
        <a class="menu-item" href="{{ route('admin.dashboard') }}"><span>Dashboard</span></a>
        <a class="menu-item active" href="{{ route('admin.sellers.index') }}"><span>Sellers</span></a>
        <a class="menu-item" href="{{ route('admin.dashboard') }}"><span>Analytics</span></a>
        <a class="menu-item" href="{{ route('admin.dashboard') }}"><span>Settings</span></a>
    </aside>

    <main class="content">
        <h1>Manage Sellers</h1>

        @if(session('success'))
            <div class="flash flash-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="flash flash-error">
                @foreach($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <section class="panel">
            <div class="panel-head">
                <h2>Seller List</h2>
                <button class="btn btn-primary" type="button" onclick="openCreateModal()">Create New Seller</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Store</th>
                        <th>Owner</th>
                        <th>Status</th>
                        <th>Slug</th>
                        <th>Token</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sellers as $seller)
                        @php
                            $statusClass = $seller->status === 'active' ? '' : ($seller->status === 'suspended' ? 'status-suspended' : 'status-inactive');
                        @endphp
                        <tr>
                            <td>{{ $seller->id }}</td>
                            <td>{{ $seller->store_name }}</td>
                            <td>{{ $seller->owner->name }}</td>
                            <td><span class="badge {{ $statusClass }}">{{ ucfirst($seller->status) }}</span></td>
                            <td><a href="/{{ $seller->slug }}" target="_blank" style="color: var(--primary);">{{ $seller->slug }}</a></td>
                            <td>{{ number_format($seller->usageBalance->token_available ?? 0) }}</td>
                            <td>
                                <div class="actions">
                                    <button
                                        class="btn btn-ghost btn-small"
                                        type="button"
                                        onclick="openTopupModal({{ $seller->id }}, '{{ addslashes($seller->store_name) }}')"
                                    >Top up</button>
                                    <button
                                        class="btn btn-ghost btn-small"
                                        type="button"
                                        onclick="openEditModal({{ $seller->id }}, '{{ addslashes($seller->store_name) }}', '{{ addslashes($seller->slug) }}', '{{ addslashes($seller->owner->name) }}', '{{ $seller->status }}')"
                                    >Update/Edit</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">Belum ada seller.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top: 12px;">{{ $sellers->links() }}</div>
        </section>
    </main>
</div>

<div id="createModal" class="modal" onclick="closeOnBackdrop(event, 'createModal')">
    <div class="modal-card">
        <div class="modal-head">
            <h3 class="modal-title">Create New Seller</h3>
            <button class="close-btn" type="button" onclick="closeModal('createModal')">Close</button>
        </div>
        <form method="POST" action="{{ route('admin.sellers.store') }}">
            @csrf
            <div class="form-grid">
                <div>
                    <label>Store Name</label>
                    <input name="store_name" value="{{ old('store_name') }}" required>
                </div>
                <div>
                    <label>Slug</label>
                    <input name="slug" value="{{ old('slug') }}" required>
                </div>
                <div>
                    <label>Status</label>
                    <select name="status">
                        <option value="active">active</option>
                        <option value="suspended">suspended</option>
                        <option value="inactive">inactive</option>
                    </select>
                </div>
                <div>
                    <label>Initial Token</label>
                    <input type="number" name="initial_token_balance" value="{{ old('initial_token_balance', 0) }}" min="0">
                </div>
                <div>
                    <label>Owner Name</label>
                    <input name="owner_name" value="{{ old('owner_name') }}" required>
                </div>
                <div>
                    <label>Owner Password</label>
                    <input type="text" name="owner_password" value="{{ old('owner_password', 'password') }}" required>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn btn-ghost" type="button" onclick="closeModal('createModal')">Cancel</button>
                <button class="btn btn-primary" type="submit">Create Seller</button>
            </div>
        </form>
    </div>
</div>

<div id="topupModal" class="modal" onclick="closeOnBackdrop(event, 'topupModal')">
    <div class="modal-card" style="max-width: 520px;">
        <div class="modal-head">
            <h3 class="modal-title">Top up Token</h3>
            <button class="close-btn" type="button" onclick="closeModal('topupModal')">Close</button>
        </div>
        <form id="topupForm" method="POST" action="">
            @csrf
            <label id="topupSellerLabel">Seller</label>
            <input type="number" name="amount" min="1" placeholder="Amount" required>
            <div class="modal-actions">
                <button class="btn btn-ghost" type="button" onclick="closeModal('topupModal')">Cancel</button>
                <button class="btn btn-primary" type="submit">Submit Top up</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="modal" onclick="closeOnBackdrop(event, 'editModal')">
    <div class="modal-card">
        <div class="modal-head">
            <h3 class="modal-title">Update Seller</h3>
            <button class="close-btn" type="button" onclick="closeModal('editModal')">Close</button>
        </div>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PATCH')
            <div class="form-grid">
                <div>
                    <label>Store Name</label>
                    <input id="editStoreName" name="store_name" required>
                </div>
                <div>
                    <label>Slug</label>
                    <input id="editSlug" name="slug" required>
                </div>
                <div>
                    <label>Owner Name</label>
                    <input id="editOwnerName" name="owner_name" required>
                </div>
                <div>
                    <label>Status</label>
                    <select id="editStatus" name="status">
                        <option value="active">active</option>
                        <option value="suspended">suspended</option>
                        <option value="inactive">inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn btn-ghost" type="button" onclick="closeModal('editModal')">Cancel</button>
                <button class="btn btn-primary" type="submit">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    function closeOnBackdrop(event, id) {
        if (event.target.id === id) {
            closeModal(id);
        }
    }

    function openCreateModal() {
        openModal('createModal');
    }

    function openTopupModal(id, storeName) {
        const form = document.getElementById('topupForm');
        form.action = `/admin/sellers/${id}/topup`;
        document.getElementById('topupSellerLabel').textContent = `Seller: ${storeName}`;
        openModal('topupModal');
    }

    function openEditModal(id, storeName, slug, ownerName, status) {
        const form = document.getElementById('editForm');
        form.action = `/admin/sellers/${id}`;
        document.getElementById('editStoreName').value = storeName;
        document.getElementById('editSlug').value = slug;
        document.getElementById('editOwnerName').value = ownerName;
        document.getElementById('editStatus').value = status;
        openModal('editModal');
    }
</script>
</body>
</html>