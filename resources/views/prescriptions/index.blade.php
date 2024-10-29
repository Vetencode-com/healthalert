<x-app-layout>
    <x-slot:title>
        Resep
    </x-slot>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Daftar Peresepan Obat</h3>
                    <p class="text-subtitle text-muted">The default layout.</p>
                </div>
                
            </div>
        </div>
        <section class="section">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="card-title">Default Layout</h4>
                </div>
                <div class="card-body">
                    <table id="test-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>John Doe</td>
                                <td>johndoe@example.com</td>
                                <td>2023-01-01</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jane Smith</td>
                                <td>janesmith@example.com</td>
                                <td>2023-02-01</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Mike Johnson</td>
                                <td>mikejohnson@example.com</td>
                                <td>2023-03-01</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    
    @push('js')
        @vite(['resources/js/extensions/datatable.js'])
        {{-- @include('layouts.partials.datatable') --}}
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                $('#test-table').DataTable(); 
            });
        </script>
    @endpush
</x-app-layout>

