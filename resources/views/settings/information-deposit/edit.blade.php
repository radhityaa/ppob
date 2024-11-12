@extends('layouts.settings.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
@endpush

@section('content-tab')
    <div class="card">
        <h5 class="card-header">Edit Informasi Deposit</h5>

        <div class="card-body">
            <form action="{{ route('admin.settings.informationDeposit') }}" method="POST" onsubmit="return submitForm()">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fs-6" for="val1">Langkah Langkah Deposit:</label>
                    <div id="editor-information"></div>
                    <input type="hidden" name="val1" id="val1" value="{{ old('val1', $data->val1) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6" for="val2">Jam Operasional:</label>
                    <div id="editor-information2"></div>
                    <input type="hidden" name="val2" id="val2" value="{{ old('val2', $data->val2) }}">
                </div>

                <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
            </form>
        </div>
    </div>
@endsection

@push('page-js')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>

    <script>
        const fullToolbar = [
            ['bold', 'italic', 'underline', 'strike'],
            [{
                    color: []
                },
                {
                    background: []
                }
            ],
            [{
                    script: 'super'
                },
                {
                    script: 'sub'
                }
            ],
            [{
                    header: '1'
                },
                {
                    header: '2'
                },
                'blockquote',
                'code-block'
            ],
            [{
                    list: 'ordered'
                },
                {
                    list: 'bullet'
                },
                {
                    indent: '-1'
                },
                {
                    indent: '+1'
                }
            ],
            [{
                direction: 'rtl'
            }],
            ['link'],
        ];

        const quill = new Quill('#editor-information', {
            bounds: '#editor-information',
            placeholder: 'Masukan Konten Disni...',
            modules: {
                formula: true,
                toolbar: fullToolbar
            },
            theme: 'snow'
        })

        const quill2 = new Quill('#editor-information2', {
            bounds: '#editor-information2',
            placeholder: 'Masukan Konten Disni...',
            modules: {
                formula: true,
                toolbar: fullToolbar
            },
            theme: 'snow'
        })

        const val1 = {!! json_encode($data->val1) !!}
        quill.root.innerHTML = val1

        const val2 = {!! json_encode($data->val2) !!}
        quill2.root.innerHTML = val2

        function submitForm() {
            const val1 = quill.root.innerHTML
            const val2 = quill2.root.innerHTML

            document.getElementById('val1').value = val1;
            document.getElementById('val2').value = val2;
            return true
        }

        $(document).ready(function() {
            @if (session('error'))
                Swal.fire({
                    title: 'Gagal!',
                    text: ' {{ session('error.message') }}',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-primary waves-effect waves-light'
                    },
                    buttonsStyling: false
                });
            @endif

            @if (session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: ' {{ session('success.message') }}',
                    icon: 'success',
                    customClass: {
                        confirmButton: 'btn btn-primary waves-effect waves-light'
                    },
                    buttonsStyling: false
                });
            @endif
        })
    </script>
@endpush
