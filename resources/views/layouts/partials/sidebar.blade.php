<x-sidebar :href="route('dashboard')" :logo="asset('assets/static/images/logo/favicon.png')">
    <x-sidebar.item icon="bi bi-grid-fill" :link="route('dashboard')" name="Dashboard" :active="Route::is('dashboard')" />
    <x-sidebar.item icon="bi bi-file-earmark-medical-fill" :link="route('prescriptions')" name="Resep" :active="Route::is('prescriptions*')" />
    <x-sidebar.item icon="bi bi-whatsapp" :link="route('whatsapp')" name="WhatsApp" :active="Route::is('whatsapp')" />
    {{-- <x-sidebar.item icon="bi bi-stack" :link="route('dashboard')" name="Components">
        <x-sidebar.subitem :link="route('component.accordion')" name="Accordion"/>
    </x-sidebar.item> --}}
</x-sidebar>
