<x-app-layout>
    <x-slot:title>
        WhatsApp Device
    </x-slot>
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-last order-md-1">
                    <h3>Perangkat WhatsApp</h3>
                    <p class="text-subtitle text-muted">Atur koneksi ke perangkat WhatsApp</p>
                </div>
                <div class="col-6 offset-6 col-md-3 offset-md-3 order-first order-md-2 mb-3">
                    <div id="server-status"></div>
                </div>
            </div>
        </div>

        {{-- Start --}}
        <div class="row">
            <div class="col-12 col-xl-8 col-lg-8">
                <div class="card mb-3 shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center" style="height: 25rem" id="qr-container">
                            {{-- Connected Condition --}}
                            @if ($device_status == 'CONNECTED')
                                <div class="d-flex justify-content-center flex-column align-items-center">
                                    <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="d-block" style="padding-top:40px">
                                        <div class="text-muted">WAITING FOR SERVER RESPONSE</div>
                                    </div>
                                </div>
                            @else
                                <div class="d-block">
                                    <div class="d-flex justify-content-center">
                                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                    <div class="d-block" style="padding-top:40px">
                                        <div class="text-muted" id="status-waiting">CLICK START SESSION !</div>
                                    </div>
                                </div>
                                <div class="d-block">
                                    <div class="text-center" style="position: absolute; right: 0; bottom: 30px; left: 0;">
                                        <button class="btn btn-primary startbutton">START SESSION</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card mb-3 shadow">
                    <div class="card-body">
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <span id="content-detail">
                                    <li class="mb-2">
                                        <span class="fw-semibold me-1">Session Name :</span>
                                        <span>-</span>
                                    </li>
                                    <li class="mb-2">
                                        <span class="fw-semibold me-1">Whatsapp Number :</span>
                                        <span>-</span>
                                    </li>
                                </span>
                            </ul>
                            <div class="d-flex justify-content-center mt-3">
                                <button class="btn w-75 btn-outline-danger waves-effect device-logout">Log out</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <h6 class="card-title">Logs</h6>
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped border-top">
                        <tbody id="logger">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
        {{-- End --}}

    </div>

    @push('js')
    <script src="https://cdn.socket.io/4.8.0/socket.io.min.js" integrity="sha384-OoIbkvzsFFQAG88r+IqMAjyOtYDPGO0cqK5HF5Uosdy/zUEGySeAzytENMDynREd" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/vendor/blitzmes/Client.js') }}"></script>
    <script>
        let limit_attempts = 10;
        let attempts = 0;
        const socket = io("wss://api.blitzmes.com", {
            transports: ['websocket']
        });

        socket.on('connect_error', () => {
            $("#server-status").html('<span class="badge rounded-pill border border-secondary text-secondary d-flex justify-content-center align-items-center p-2"><i class="bi bi-database-slash me-1 fs-5" style="width: 1.25rem; height: 1.25rem;"></i><span style="padding-top: 2px"><span class="d-none d-xl-inline d-lg-inline d-md-inline">SERVER - </span>DISCONNECTED</span></span>')
            attempts++;
            if (attempts >= limit_attempts) {
                socket.disconnect();
            }
        });

        socket.on('connect', () => {
            $("#server-status").html('<span class="badge rounded-pill border border-success text-success d-flex justify-content-center align-items-center p-2"><i class="bi bi-hdd-stack me-1 fs-5" style="width: 1.25rem; height: 1.25rem;"></i><span style="padding-top: 2px"><span class="d-none d-xl-inline d-lg-inline d-md-inline">SERVER - </span>CONNECTED</span></span>')
            attempts = 0;
        });
    </script>
    <script>
        const session = '{{ config("blitzmes.session") }}';
        const device_status = '{{ $device_status }}';
        const blitzmes = new Client(session, device_status, socket);

        blitzmes.init();

        $(document).on('click', ".startbutton", function(e) {
            e.preventDefault()
            $(this).attr('disabled', true)
            $("#status-waiting").html('WAITING FOR SERVER RESPONSE');
            $(this).html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>LOADING... ')
            blitzmes.startSession()
        });

        $(document).on('click', ".refresh-page", function(e) {
            e.preventDefault()
            location.reload();
        });

        $(document).on('click', ".device-logout", function(e) {
            e.preventDefault()
            $(this).attr('disabled', true)
            $("#status-waiting").html('WAITING FOR SERVER RESPONSE');
            $(this).html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Loading... ')
            blitzmes.logout()

            setTimeout(function() {
                $(".device-logout").attr('disabled', false)
                $(".device-logout").html('Log out')
            }, 8000);
        });
    </script>
        
    @endpush
</x-app-layout>
