<x-app-layout>
    <x-slot:title>
        Buat Janji Temu
    </x-slot>
    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="{{ asset('assets/vendor/clockpicker/clockpicker.min.css') }}">
        <style>
            .select2-container .select2-selection--single {
                height: calc(2.25rem + 2px); /* Matches Bootstrap's form-select height */
                padding: 0.375rem 0.75rem; /* Matches padding of form-select */
                font-size: 1rem; /* Matches font-size of form-select */
                line-height: 1.5; /* Matches line-height of form-select */
                border: 1px solid #ced4da; /* Matches border color of form-select */
                border-radius: 0.25rem; /* Matches border-radius of form-select */
                background-color: #fff; /* White background to match form-select */
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #495057; /* Matches text color of form-select */
                padding-left: 0; /* Removes extra padding */
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: calc(2.25rem + 2px); /* Matches height to center the arrow */
                right: 0.75rem; /* Adjusts arrow position */
            }

            .select2-container--default .select2-selection--single {
                box-shadow: none; /* Removes Select2's default shadow */
            }

            /* Additional styling to improve consistency with Bootstrap's focus effect */
            .select2-container--default .select2-selection--single:focus {
                border-color: #86b7fe;
                outline: 0;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25); /* Matches form-select focus shadow */
            }           
        </style>
    @endpush
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Buat Janji Temu</h3>
                    <p class="text-subtitle text-muted">Isi data data berikut</p>
                </div>

            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12 col-md-4 col-lg-5">
                    <div class="card shadow">
                        <div class="card-body">
                            <form action="{{ route('appointments.store') }}" method="POST" id="store-appointment" data-back-url="{{ route('appointments') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="select-patients" class="form-label mb-1 d-block">Pasien <x-required /></label>
                                    <select id="select-patients" name="patient_id" class="select2 form-select" data-submit-url="{{ route('prescriptions.store') }}" data-back-url="{{ route('prescriptions') }}">
                                        <option value="" disabled selected>Pilih pasien</option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="select-doctors" class="form-label mb-1 d-block">Dokter <x-required /></label>
                                    <select id="select-doctors" name="doctor_id" class="select2 form-select" data-submit-url="{{ route('prescriptions.store') }}" data-back-url="{{ route('prescriptions') }}">
                                        <option value="" disabled selected>Pilih dokter</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="date" class="form-label">Tanggal <x-required /></label>
                                    <input type="text" class="form-control" id="date" name="date" placeholder="Tanggal pertemuan">
                                    <span class="text-danger error"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="time" class="form-label">Waktu <x-required /></label>
                                    <input type="text" class="form-control" id="time" name="time" placeholder="07:00 - 17:00" readonly>
                                    <span class="text-danger error"></span>
                                </div>
                                <div class="d-flex justify-content-end mb-3">
                                    <button class="btn btn-primary" type="submit">
                                        <div class="spinner-border spinner-border-sm d-none" role="status"></div>
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    @push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('assets/vendor/clockpicker/clockpicker.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            if (!$('#select-patients').val()) {
                $('#select-patients').select2('open');
            }

            $('#date').flatpickr({
                altInput: true,
                altFormat: "d M Y",
                dateFormat: "Y-m-d",
                minDate: "today",
            });

            $('#time').clockpicker({
                autoclose: true,
                donetext: 'Ok',
                vibrate: true,
            });

            $('#store-appointment').submit(function (e) {
                e.preventDefault();

                const $submitBtn = $(this).find('button[type="submit"]');
                $submitBtn.prop('disabled', true);
                $submitBtn.find('.spinner-border').toggleClass('d-none');


                $('.error').text('');
                let hasError = false;

                const fields = [
                    { selector: '#select-patients', message: 'Pasien harus dipilih.' },
                    { selector: '#select-doctors', message: 'Dokter harus dipilih.' },
                    { selector: '#date', message: 'Tanggal harus diisi.' },
                    { selector: '#time', message: 'Waktu harus diisi.' }
                ];

                fields.forEach(field => {
                    const fieldElement = $(field.selector);

                    if (fieldElement.val() === null || fieldElement.val().trim() === '') {
                        fieldElement.parent().find('.error').text(field.message);
                        hasError = true;
                    }
                });

                if (hasError) {
                    $submitBtn.prop('disabled', false);
                    $submitBtn.find('.spinner-border').toggleClass('d-none');
                } else {
                    const url = $(this).attr('action');
                    const back_url = $(this).data('back-url');
                    const data = $(this).serialize();

                    api.post(url, {
                        loading: true,
                        data,
                        success: (resp) => {
                            window.location.href = back_url;
                        },
                        error: (xhr) => {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                for (const field in errors) {
                                    const errorMessage = errors[field][0]; // Get the first error message for each field
                                    const fieldElement = $(`[name="${field}"]`);
                                    
                                    fieldElement.parent().find('.error').text(errorMessage);
                                }
                                $submitBtn.prop('disabled', false);
                                $submitBtn.find('.spinner-border').toggleClass('d-none');
                            } else {
                                alert("Terjadi kesalahan. Silakan coba lagi.");
                            }
                        }
                    });
                }
            });

        })
    </script>
    @endpush
</x-app-layout>

