@extends('layouts.administrator.app')

@push('page-css')
@endpush

@section('content')
    <div class="alert alert-info" role="alert">
        Dont leave your phone before connencted
    </div>

    <div class="card widget widget-stats-large">
        <div class="row">
            <div class="col-xl-8">
                <div class="widget-stats-large-chart-container">
                    <div class="card-header logoutbutton">

                    </div>
                    <div class="card-body">
                        <div id="apex-earnings"></div>
                        <div class="imageee text-center">
                            <img src="{{ asset('assets/img/waiting.jpg') }}" height="300px" alt="">
                        </div>
                        <div class="statusss text-center">
                            <button class="btn btn-primary" type="button" disabled>
                                <span class="spinner-grow spinner-grow-sm me-2" role="status" aria-hidden="true"></span>
                                Witing For node server..
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="widget-stats-large-info-container">
                    <div class="card-header">
                        <h5 class="card-title">Whatsapp Info<span class="badge badge-info badge-style-light">Updated
                                5 min ago</span>
                        </h5>
                    </div>
                    <div class="card-body account">

                        <ul class="list-group account list-group-flush">
                            <li class="list-group-item name">Nama : </li>
                            <li class="list-group-item number">Nomor : {{ $number }}</li>
                            <li class="list-group-item device">Device : </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script src="https://cdn.socket.io/4.6.0/socket.io.min.js"
        integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous">
    </script>

    <script>
        let socket;
        let device = '{{ $number }}'

        if ('{{ env('APP_WA_SERVER') }}' === 'hosting') {
            socket = io()
        } else {
            socket = io('{{ config('app.wa_socket') }}', {
                transports: ['websocket', 'polling', 'flashsocket']
            })
        }

        socket.emit('StartConnection', '{{ $number }}')
        socket.on('qrcode', ({
            token,
            data,
            message
        }) => {
            if (token == device) {
                let url = data
                $('.imageee').html(`<img src="${url}" height="300px" alt="">`)
                let count = 0
                $('.statusss').html(`  <button class="btn btn-warning" type="button" disabled>
                                                     <span class="" role="status" aria-hidden="true"></span>
                                                   ${message}
                                                 </button>`)
            }
        })

        socket.on('connection-open', ({
            token,
            user,
            ppUrl
        }) => {
            if (token == device) {
                let editUrl = "{{ route('whatsapp.updateStatus', ':phone') }}"
                editUrl = editUrl.replace(':phone', device)

                $.ajax({
                    url: editUrl,
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        number: device,
                        status: 'Connected'
                    }
                })

                $('.name').html(`Nama : ${user.name}`)
                $('.number').html(`Number : ${user.id}`)
                $('.device').html(`Device / Token : Not detected - ${token}`)
                $('.imageee').html(` <img src="${ppUrl}" height="300px" alt="">`)
                $('.statusss').html(`  <button class="btn btn-success" type="button" disabled>
                                    <span class="" role="status" aria-hidden="true"></span>
                                   Connected
                                </button>`)
                $('.logoutbutton').html(` <button class="btn btn-danger" class="logout"  id="logout"  onclick="logout({{ $number }})">
                                   Logout
                               </button>`)
            }
        })

        socket.on('Unauthorized', ({
            token
        }) => {
            if (token == device) {
                $('.statusss').html(`  <button class="btn btn-danger" type="button" disabled>
                                                    <span class="" role="status" aria-hidden="true"></span>
                                                   Unauthorized
                                                </button>`)
            }

        })

        socket.on('message', ({
            token,
            message
        }) => {
            if (token == device) {
                $('.statusss').html(`  <button class="btn btn-success" type="button" disabled>
                                                    <span class="" role="status" aria-hidden="true"></span>
                                                   ${message}
                                                </button>`);
                //if there is text connection close in message
                if (message.includes('Connection closed')) {
                    // count 5 second
                    let count = 5;
                    //set interval
                    let interval = setInterval(() => {
                        //if count is 0
                        if (count == 0) {
                            //clear interval
                            clearInterval(interval);
                            //reload page
                            location.reload();
                        }
                        //change text
                        $('.statusss').html(`  <button class="btn btn-success" type="button" disabled>
                                                    <span class="" role="status" aria-hidden="true"></span>
                                                   ${message} in ${count} second
                                                </button>`);
                        //count down
                        count--;
                    }, 1000);

                }
            }
        });

        function logout(device) {
            let editUrl = "{{ route('whatsapp.updateStatus', ':phone') }}"
            editUrl = editUrl.replace(':phone', device)
            $.ajax({
                url: editUrl,
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    number: device,
                    status: 'Disconnected'
                }
            })
            socket.emit('LogoutDevice', device)
        }
    </script>
@endpush
