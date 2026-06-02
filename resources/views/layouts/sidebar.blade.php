<div class="sidebar">
    <div class="form-inline mt-2">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>

    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @php
                $currentRole = auth()->check() ? auth()->user()->getRole() : null;
                $perms = config('permissions');
            @endphp
            <li class="nav-item">
                @if(in_array($currentRole, $perms['dashboard'] ?? []))
                <a href="{{ url('/') }}" class="nav-link {{ ($activeMenu == 'dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
                @endif
            </li>

            <li class="nav-header">Data Pengguna</li>
            @if(in_array($currentRole, $perms['level'] ?? []))
            <li class="nav-item">
                <a href="{{ url('/level') }}" class="nav-link {{ ($activeMenu == 'level') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p>Level User</p>
                </a>
            </li>
            @endif
            @if(in_array($currentRole, $perms['user'] ?? []))
            <li class="nav-item">
                <a href="{{ url('/user') }}" class="nav-link {{ ($activeMenu == 'user') ? 'active' : '' }}">
                    <i class="nav-icon far fa-user"></i>
                    <p>Data User</p>
                </a>
            </li>
            @endif

            <li class="nav-header">Data Barang</li>
            @if(in_array($currentRole, $perms['kategori'] ?? []))
            <li class="nav-item">
                <a href="{{ url('/kategori') }}" class="nav-link {{ ($activeMenu == 'kategori') ? 'active' : '' }}">
                    <i class="nav-icon far fa-bookmark"></i>
                    <p>Kategori Barang</p>
                </a>
            </li>
            @endif
            @if(in_array($currentRole, $perms['barang'] ?? []))
            <li class="nav-item">
                <a href="{{ url('/barang') }}" class="nav-link {{ ($activeMenu == 'barang') ? 'active' : '' }}">
                    <i class="nav-icon far fa-list-alt"></i>
                    <p>Data Barang</p>
                </a>
            </li>
            @endif
            @if(in_array($currentRole, $perms['supplier'] ?? []))
            <li class="nav-item">
                <a href="{{ url('/supplier') }}" class="nav-link {{ ($activeMenu == 'supplier') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-dolly"></i>
                    <p>Data Supplier</p>
                </a>
            </li>
            @endif

            <li class="nav-header">Data Transaksi</li>
            @if(in_array($currentRole, $perms['stok'] ?? []))
            <li class="nav-item">
                <a href="{{ url('/stok') }}" class="nav-link {{ ($activeMenu == 'stok') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cubes"></i>
                    <p>Stok Barang</p>
                </a>
            </li>
            @endif
            @if(in_array($currentRole, $perms['penjualan'] ?? []))
            <li class="nav-item">
                <a href="{{ url('/penjualan') }}" class="nav-link {{ ($activeMenu == 'penjualan') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cash-register"></i>
                    <p>Transaksi Penjualan</p>
                </a>
            </li>
            @endif
        </ul>
    </nav>
</div>