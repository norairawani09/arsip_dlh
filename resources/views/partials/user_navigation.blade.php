{{-- resources/views/partials/user-navigation.blade.php --}}
<a href="{{ route('user.dashboard') }}" 
   class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('user.dashboard') ? 'bg-sidebar-primary text-sidebar-primary-foreground' : 'text-sidebar-foreground hover:bg-sidebar-accent' }}">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
    </svg>
    <span x-show="sidebarOpen">Dashboard</span>
</a>

<a href="{{ route('surat.masuk') }}" 
   class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('surat.masuk') ? 'bg-sidebar-primary text-sidebar-primary-foreground' : 'text-sidebar-foreground hover:bg-sidebar-accent' }}">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 1a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
    </svg>
    <span x-show="sidebarOpen">Surat Masuk</span>
</a>

<a href="{{ route('surat.keluar') }}" 
   class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('surat.keluar') ? 'bg-sidebar-primary text-sidebar-primary-foreground' : 'text-sidebar-foreground hover:bg-sidebar-accent' }}">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z"/>
    </svg>
    <span x-show="sidebarOpen">Surat Keluar</span>
</a>

<a href="{{ route('disposisi.index') }}" 
   class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('disposisi.index') ? 'bg-sidebar-primary text-sidebar-primary-foreground' : 'text-sidebar-foreground hover:bg-sidebar-accent' }}">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
    </svg>
    <span x-show="sidebarOpen">Disposisi</span>
</a>

<a href="{{ route('laporan.index') }}" 
   class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('laporan.*') ? 'bg-sidebar-primary text-sidebar-primary-foreground' : 'text-sidebar-foreground hover:bg-sidebar-accent' }}">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
    </svg>
    <span x-show="sidebarOpen">Laporan</span>
</a>

<a href="{{ route('user.profile') }}" 
   class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('user.profile') ? 'bg-sidebar-primary text-sidebar-primary-foreground' : 'text-sidebar-foreground hover:bg-sidebar-accent' }}">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
    </svg>
    <span x-show="sidebarOpen">Profile Saya</span>
</a>