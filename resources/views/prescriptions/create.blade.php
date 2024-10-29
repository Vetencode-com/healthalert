<x-app-layout>
    <x-slot:title>
        Buat Resep
    </x-slot>
    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/clockpicker/clockpicker.min.css') }}">
    @endpush
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
                        <div class="card-body">
                            <div class="form-group">
                                <label for="select-patients" class="form-label mb-1 d-block">Pasien</label>
                                <select id="select-patients" class="select2 form-select" data-submit-url="{{ route('prescriptions.store') }}" data-back-url="{{ route('prescriptions') }}">
                                    <option value="" disabled selected>Pilih pasien</option>
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="select-medicines" class="form-label mb-1 d-block">Obat</label>
                                <select id="select-medicines" class="select2 form-select" data-url="{{ route('prescriptions.medicines.store') }}">
                                    <option value="" disabled selected>Pilih obat</option>
                                    @foreach ($medicines as $medicine)
                                        <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                    @endforeach
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
                        <div class="d-none" id="medicine-item-template">
                            <div class="row g-2 mb-3 mt-2 medicine-item partof-medicine-{medicine_id}">
                                <div class="col-12 d-md-none d-flex justify-content-end">
                                    <button class="btn btn-danger btn-sm delete-medicine" data-url="{{ route('prescriptions.medicines.delete', '_medicine_id_') }}" data-target=".partof-medicine-{medicine_id}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" value="{medicine_name}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <select
                                        id="select-frequency-template"
                                        class="form-select select-frequencies"
                                        data-id="{medicine_id}"
                                        data-target=".partof-medicine-{medicine_id}"
                                        data-url="{{ route('prescriptions.medicines.frequency.change', '_medicine_id_') }}"
                                        >
                                        <option value="" disabled selected>Dosis harian</option>
                                        <option value="1">1&times;/hari</option>
                                        <option value="2">2&times;/hari</option>
                                        <option value="3">3&times;/hari</option>
                                        <option value="4">4&times;/hari</option>
                                        <option value="5">5&times;/hari</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-none d-md-flex align-items-center">
                                    <button class="btn btn-danger btn-sm delete-medicine" data-url="{{ route('prescriptions.medicines.delete', '_medicine_id_') }}" data-target=".partof-medicine-{medicine_id}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <h6 class="d-none partof-medicine-{medicine_id}">Waktu</h6>
                            <div class="row g-2 partof-medicine-{medicine_id} times-container">

                            </div>
                            <hr class="partof-medicine-{medicine_id}">
                        </div>
                        <div class="card-body" id="container-of-medicines">
                            @foreach ($registeredMedicines as $medicine)
                                <div class="row g-2 mb-3 mt-2 medicine-item partof-medicine-{{ $medicine->id }}">
                                    <div class="col-12 d-md-none d-flex justify-content-end">
                                        <button class="btn btn-danger btn-sm delete-medicine" data-url="{{ route('prescriptions.medicines.delete', $medicine->id) }}" data-target=".partof-medicine-{{ $medicine->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" value="{{ $medicine->name }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <select
                                            id="select-frequency-{{ $medicine->id }}"
                                            class="form-select select-frequencies"
                                            data-id="{{ $medicine->id }}"
                                            data-target=".partof-medicine-{{ $medicine->id }}"
                                            data-url="{{ route('prescriptions.medicines.frequency.change', $medicine->id) }}"
                                            >
                                            <option value="" disabled {{ is_null($medicine->frequency) ? 'selected' : '' }}>Dosis harian</option>
                                            <option value="1" {{ $medicine->frequency == 1 ? 'selected' : '' }}>1&times;/hari</option>
                                            <option value="2" {{ $medicine->frequency == 2 ? 'selected' : '' }}>2&times;/hari</option>
                                            <option value="3" {{ $medicine->frequency == 3 ? 'selected' : '' }}>3&times;/hari</option>
                                            <option value="4" {{ $medicine->frequency == 4 ? 'selected' : '' }}>4&times;/hari</option>
                                            <option value="5" {{ $medicine->frequency == 5 ? 'selected' : '' }}>5&times;/hari</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-none d-md-flex align-items-center">
                                        <button class="btn btn-danger btn-sm delete-medicine" data-url="{{ route('prescriptions.medicines.delete', $medicine->id) }}" data-target=".partof-medicine-{{ $medicine->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <h6 class="{{ $medicine->times()->count() ? '' : 'd-none' }} partof-medicine-{{ $medicine->id }}">Waktu</h6>
                                <div class="row g-2 partof-medicine-{{ $medicine->id }} times-container">
                                    @foreach ($medicine->times as $item)
                                        <div class="col-lg-2 col-md-4 col-6">
                                            <input type="text" class="form-control clockpicker" value="{{ $item->time ? date('H:i', strtotime($item->time)) : null }}" data-url="{{ route('prescriptions.medicines.times.change', $item->id) }}" data-order="{{ $loop->iteration }}" readonly>
                                        </div>
                                    @endforeach
                                </div>
                                <hr class="partof-medicine-{{ $medicine->id }}">
                            @endforeach
                            <div class="bg-light p-5 rounded-4 text-center no-medicines {{ !empty($registeredMedicines->count()) ? 'd-none' : '' }}">
                                <i class="bi bi-database-fill-slash fs-1"></i>
                                <small class="d-block fs-5">Belum ada obat ditambahkan</small>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn btn-primary" type="button" id="submit-prescription">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            
        </section>
    </div>
    
    @push('js')
    <script src="{{ asset('assets/vendor/clockpicker/clockpicker.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        let activeClockPicker = null;
        $(document).ready(function () {
            $('.select2').select2();

            function initClockPicker() {
                $('.clockpicker').clockpicker({
                    autoclose: true,
                    donetext: 'Ok',
                    vibrate: true,
                    afterDone: function () {
                        const url = activeClockPicker.data('url')
                        const order = activeClockPicker.data('order')
                        const time = activeClockPicker.val();
                        
                        api.patch(url, {
                            data: { time, order },
                            success: function (resp) {}
                        });
                    }
                });
            }

            initClockPicker();

            // Menangkap input mana yang menampilkan clockpicker
            $(document).on('focus', 'input.clockpicker', function() {
                activeClockPicker = $(this);
            });

            $('#select-medicines').change(function (e) {
                const url = $(this).data('url');
                const id = $(this).val();
    
                if (id) {
                    api.post(url, {
                        data: { id },
                        success: (resp) => {
                            $(this).val(null).trigger('change');
                            $(this).find(`option[value="${id}"]`).remove();
                            $(this).select2();
    
                            // Hide no elements
                            $('.no-medicines').addClass('d-none');
                            
                            // Proses append ke list of medicines
                            const medicine = resp.data.medicine;
                            const $medicineItemTemp = $('#medicine-item-template').clone();
                            let updatedHTML = $medicineItemTemp.html().replaceAll('{medicine_id}', resp.data.id);
                            updatedHTML = updatedHTML.replaceAll('_medicine_id_', resp.data.id);
                            updatedHTML = updatedHTML.replaceAll('{medicine_name}', medicine.name);
        
                            $('#container-of-medicines').append(updatedHTML);
                        },
                    });
                }
            });
    
            $(document).on('change', '.select-frequencies', function (e) {
                const id = $(this).data('id');
                const url = $(this).data('url');
                const frequency = $(this).val();
                const target = $(this).data('target');
    
                api.patch(url, {
                    data: { frequency },
                    success: (resp) => {
                        $(target).removeClass('d-none');
                        $(`${target}.times-container`).empty();
                        let order = 1;
                        resp.data.times.forEach(item => {
                            $(`${target}.times-container`).append(`
                                <div class="col-lg-2 col-md-4 col-6">
                                    <input type="text" class="form-control clockpicker" value="${item.time ?? ''}" data-url="${item.url}" data-order="${order}" readonly>
                                </div>
                            `);
                            order++;
                        });
                        initClockPicker();
                    }
                })
            });
    
            $(document).on('click', '.delete-medicine', function () {
                const url = $(this).data('url');
                const target = $(this).data('target');
    
                interact.confirm({
                    title: "Apakah anda yakin?",
                    icon: "warning",
                    onConfirmed: () => {
                        api.delete(url, {
                            success:  (resp) => {
                                $(target).remove();
    
                                // When the left one is only the template
                                if($('.medicine-item').length == 1) {
                                    $('.no-medicines').removeClass('d-none');
                                }
    
                                const medicine = resp.data.medicine;
                                $('#select-medicines').append(`
                                    <option value="${medicine.id}">${medicine.name}</option>
                                `).select2();
                            }
                        })
                    },
                })
            });

            function toastifyError(message) {
                Toastify({
                    text: message,
                    duration: 3000, // Show for 3 seconds
                    close: true,    // Show a close button
                    gravity: "top", // Position on top
                    position: "right", // Align to the right
                    backgroundColor: "linear-gradient(135deg, #ff3e3e, #ff6f6f)", // Red gradient
                    stopOnFocus: true // Stop timeout on hover
                }).showToast();
            }

            function getFirstEmptyElement(selector) {
                let emptyElement = null;

                $(selector).each(function() {
                    if (!$(this).val()) {
                        emptyElement = this;
                        return false;
                    }
                });

                return emptyElement;
            }

            $('#submit-prescription').click(function () {
                $('.is-invalid').removeClass('is-invalid');

                const patient = $('#select-patients').val();
                if (!patient) {
                    $('#select-patients').select2('open');
                    toastifyError('Pasien harus diisi');
                    return;
                }

                if (!$('#container-of-medicines .medicine-item').length) {
                    toastifyError('Belum ada obat yang disertakan');
                    return;
                }

                let emptySelectFreq = getFirstEmptyElement('#container-of-medicines .select-frequencies');
                if (emptySelectFreq) {
                    $(emptySelectFreq).addClass('is-invalid');
                    $(emptySelectFreq).focus();
                    toastifyError('Pilih dosis harian dari obat yang disertakan');
                    return;
                }
                
                let emptyInputTime = getFirstEmptyElement('#container-of-medicines .clockpicker');
                if (emptyInputTime) {
                    $(emptyInputTime).addClass('is-invalid');
                    $(emptyInputTime).focus();
                    toastifyError('Tentukan waktu untuk meminum obat');
                    return;
                }

                const submit_url = $('#select-patients').data('submit-url');
                const back_url = $('#select-patients').data('back-url');
                api.post(submit_url, {
                    data: {
                        patient_id: patient,
                    },
                    success: (resp) => {
                        window.location.href= back_url;
                    }
                })
            });

        });

    </script>
    @endpush
</x-app-layout>

