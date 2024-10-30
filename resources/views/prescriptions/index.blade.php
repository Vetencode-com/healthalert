<x-app-layout>
    <x-slot:title>
        Resep
    </x-slot>

    @push('css')
        @include('layouts.partials.datatable-styles')
    @endpush

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3 class="mb-2">Daftar Peresepan Obat</h3>
                    {{-- <p class="text-subtitle text-muted">The default layout.</p> --}}
                </div>
                
            </div>
        </div>
        <section class="section">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Daftar Peresepan Obat</h4>
                    <button class="btn btn-primary">
                        <i class="bi bi-plus"></i> Tambah
                    </button>

                </div>
                <div class="card-body">
                    <table id="prescription-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-start">No.</th>
                                <th>Tanggal</th>
                                <th>Pasien</th>
                                <th>Jumlah Obat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prescriptions as $prescription)
                                <tr>
                                    <td class="text-start">{{ $loop->iteration }}. </td>
                                    <td>{{ date('d M Y, (H:i)', strtotime($prescription->created_at)) }}</td>
                                    <td>{{ $prescription->patient->name }}</td>
                                    <td>{{ $prescription->medicines()->count() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    
    @push('js')
        @include('layouts.partials.datatable-scripts')

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                $('#prescription-table').DataTable(); 
            });
        </script>
    @endpush
</x-app-layout>

