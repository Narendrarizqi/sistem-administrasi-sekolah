{{-- <div
    x-show="$store.sidebar.isMobileOpen"
    @click="$store.sidebar.toggleMobileOpen()"
    class="fixed inset-0 bg-gray-900/50 z-[9999] xl:hidden"
>
sidebarToggle ? 'block xl:hidden' : 'hidden'
</div> --}}

<div
  :class="$store.sidebar.isMobileOpen ? 'block xl:hidden' : 'hidden'"
  class="fixed z-50 h-screen w-full bg-gray-900/50"
></div>
