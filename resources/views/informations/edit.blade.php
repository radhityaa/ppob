@extends('layouts.administrator.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
@endpush

@section('content')
    <div class="card">
        <h5 class="card-header">Edit Informasi</h5>

        <div class="card-body">
            <form action="{{ route('information.update', $information->slug) }}" method="POST"
                onsubmit="return submitForm()">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="title">Judul</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Masukan Judul"
                            value="{{ old('title', $information->title) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="category">Kategori</label>
                        <select name="category" id="category" class="form-control">
                            <option value="" selected disabled>-- Pilih Kategori --</option>
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}"
                                    {{ $item->id == $information->category_information_id ? 'selected' : '' }}>
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="type">Tipe</label>
                        <select name="type" id="type" class="form-control">
                            <option value="" disabled>-- Pilih Tipe --</option>
                            <option value="Informasi" {{ $information->type === 'Informasi' ? 'selected' : '' }}>Informasi
                            </option>
                            <option value="Peringatan" {{ $information->type === 'Peringatan' ? 'selected' : '' }}>
                                Peringatan</option>
                            <option value="Penting" {{ $information->type === 'Penting' ? 'selected' : '' }}>Penting
                            </option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="description">Konten</label>
                    <div id="editor-information"></div>
                    <input type="hidden" name="description" id="description"
                        value="{{ old('description', $information->description) }}">
                </div>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                <a href="{{ route('information.index') }}" class="btn btn-secondary waves-effect waves-light">Kembali</a>
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

        const savedContent = {!! json_encode($information->description) !!}
        quill.root.innerHTML = savedContent

        function submitForm() {
            const description = quill.root.innerHTML
            document.getElementById('description').value = description;
            return true
        }

        $(document).ready(function() {
            $('#category').select2({
                placeholder: '-- Pilih Kategori --',
            });
        });
    </script>
@endpush
