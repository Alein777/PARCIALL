<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GDA Store')</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:        #0d0d0f;
            --surface:   #16161a;
            --card:      #1e1e24;
            --border:    #2a2a35;
            --accent:    #6c63ff;
            --accent2:   #ff6584;
            --text:      #e8e8f0;
            --muted:     #7a7a95;
            --success:   #3ecf8e;
            --danger:    #ff4d6d;
            --warning:   #ffc247;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── NAVBAR ── */
        nav {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-brand {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.3rem;
            color: var(--text);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-brand span {
            color: var(--accent);
        }

        .nav-links {
            display: flex;
            gap: 0.25rem;
        }

        .nav-links a {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--text);
            background: var(--card);
        }

        .nav-links a.active {
            color: var(--accent);
        }

        /* ── MAIN ── */
        main {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }

        /* ── PAGE HEADER ── */
        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .page-header h1 span {
            display: block;
            font-size: 0.8rem;
            font-weight: 400;
            font-family: 'DM Sans', sans-serif;
            color: var(--muted);
            margin-bottom: 0.35rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* ── BUTTON ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.55rem 1.2rem;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
        }

        .btn-primary:hover { background: #7c75ff; transform: translateY(-1px); }

        .btn-danger {
            background: transparent;
            color: var(--danger);
            border: 1px solid var(--danger);
        }

        .btn-danger:hover { background: var(--danger); color: #fff; }

        .btn-edit {
            background: transparent;
            color: var(--accent);
            border: 1px solid var(--accent);
        }

        .btn-edit:hover { background: var(--accent); color: #fff; }

        .btn-sm { padding: 0.35rem 0.8rem; font-size: 0.8rem; }

        /* ── TABLE ── */
        .table-wrap {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            padding: 0.9rem 1.2rem;
            text-align: left;
            font-family: 'Syne', sans-serif;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }

        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(108,99,255,0.05); }

        tbody td {
            padding: 0.9rem 1.2rem;
            font-size: 0.9rem;
            vertical-align: middle;
        }

        .td-actions { display: flex; gap: 0.5rem; }

        /* ── BADGE ── */
        .badge {
            display: inline-block;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-active   { background: rgba(62,207,142,0.15); color: var(--success); }
        .badge-inactive { background: rgba(255,77,109,0.15);  color: var(--danger);  }

        /* ── MODAL ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            z-index: 200;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        .modal-overlay.open { display: flex; }

        .modal {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            width: 100%;
            max-width: 480px;
            animation: slideUp 0.2s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        .modal h2 {
            font-family: 'Syne', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        /* ── FORM ── */
        .form-group { margin-bottom: 1rem; }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0.65rem 0.9rem;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--accent);
        }

        .form-group select option { background: var(--surface); }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .btn-cancel {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--muted);
            padding: 0.55rem 1.2rem;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
        }

        .btn-cancel:hover { background: var(--border); color: var(--text); }

        /* ── ALERT ── */
        .alert {
            padding: 0.9rem 1.2rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success { background: rgba(62,207,142,0.12); color: var(--success); border: 1px solid rgba(62,207,142,0.3); }
        .alert-error   { background: rgba(255,77,109,0.12);  color: var(--danger);  border: 1px solid rgba(255,77,109,0.3); }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--muted);
        }

        .empty-state svg { opacity: 0.3; margin-bottom: 1rem; }
        .empty-state p { font-size: 0.9rem; }

        /* ── LOADING ── */
        .spinner {
            width: 20px; height: 20px;
            border: 2px solid var(--border);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: inline-block;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .loading-row td {
            text-align: center;
            padding: 3rem;
            color: var(--muted);
        }
    </style>
    @stack('styles')
</head>
<body>

<nav>
    <a href="/" class="nav-brand">GDA<span>Store</span></a>
    <div class="nav-links">
        <a href="/marcas"      class="{{ request()->is('marcas*')      ? 'active' : '' }}">Marcas</a>
        <a href="/categorias"  class="{{ request()->is('categorias*')  ? 'active' : '' }}">Categorías</a>
        <a href="/proveedores" class="{{ request()->is('proveedores*') ? 'active' : '' }}">Proveedores</a>
    </div>
</nav>

<main>
    @yield('content')
</main>

@stack('scripts')
</body>
</html>
