<x-app-layout>
    <x-slot:title>
        Buat Resep
    </x-slot>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Buat Resep</h3>
                    <p class="text-subtitle text-muted">The default layout.</p>
                </div>

            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-4">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4 class="card-title">Pilih Pasien</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="select-patients" class="form-label mb-1 ">Pasien</label>
                                <select id="select-patients" class="choices form-select">
                                    <option value="" disabled selected>Pilih pasien</option>
                                    <option value="square">Square</option>
                                    <option value="rectangle">Rectangle</option>
                                    <option value="rombo">Rombo</option>
                                    <option value="romboid">Romboid</option>
                                    <option value="trapeze">Trapeze</option>
                                    <option value="traible">Triangle</option>
                                    <option value="polygon">Polygon</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-9 col-lg-8">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4 class="card-title">Daftar Obat</h4>
                        </div>
                        <div class="card-body">
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </section>
    </div>
    
    @push('js')
    @endpush
</x-app-layout>

