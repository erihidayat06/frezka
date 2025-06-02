@extends('backend.layouts.app')

@section('title')
    {{ __($module_title) }}
@endsection


@push('after-styles')
    <link rel="stylesheet" href="{{ mix('modules/constant/style.css') }}">
@endpush
@section('content')
    <div class="card">
        <div class="card-body">
            <x-backend.section-header>
                <x-slot name="toolbar">
                    <div class="input-group flex-nowrap top-input-search">
                        <span class="input-group-text" id="addon-wrapping"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="form-control dt-search" placeholder="{{ __('messages.search') }}..."
                            aria-label="Search" aria-describedby="addon-wrapping">
                    </div>
                    <a href="{{ route('backend.faq.create') }}" class="btn btn-primary" title="Create Faq">
                        <i class="fas fa-plus-circle"></i>
                        {{ __('messages.new') }}
                    </a>

                </x-slot>
            </x-backend.section-header>
            <table id="datatable" class="table border table-responsive rounded">
            </table>
        </div>
    </div>

    <div data-render="app">

    </div>
@endsection

@push('after-styles')
    <!-- DataTables Core and Extensions -->
    <link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

@push('after-scripts')
    <script src="{{ mix('modules/subscriptions/script.js') }}"></script>
    <script src="{{ asset('js/form-offcanvas/index.js') }}" defer></script>

    <!-- DataTables Core and Extensions -->
    <script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>
    <script type="text/javascript" defer>
        const columns = [{
                data: 'id',
                name: 'id',
                visible: false
            },
            {
                data: 'question',
                name: 'question',
                title: "{{ __('frontend.question') }}"
            },
            {
                data: 'answer',
                name: 'answer',
                title: "{{ __('frontend.answer') }}"
            },
            {
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: true,
                title: "{{ __('frontend.status') }}"
            },
        ];

        const actionColumn = [{
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            title: "{{ __('frontend.action') }}",
            render: function(data, type, row) {
                let buttons = ` <button class="btn btn-primary btn-sm btn-edit" onclick="editFaq(${row.id})" title="Edit" data-bs-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </button>
                                    <a href="{{ route('backend.faq.delete', '') }}/${row.id}"
           id="delete-subscription-${row.id}"
           class="btn btn-danger btn-sm"
           data-type="ajax"
           data-method="DELETE"
           data-token="{{ csrf_token() }}"
           data-bs-toggle="tooltip"
           title="{{ __('messages.delete') }}"
            data-confirm="{{ __('messages.are_you_sure?', ['module' => __('messages.lbl_faq'), 'name' => '']) }} ">
            <i class="fa-solid fa-trash"></i>
        </a>
    `;
                return buttons;
            }
        }];

        let finalColumns = [
            ...columns,
            ...actionColumn
        ]

        document.addEventListener('DOMContentLoaded', (event) => {
            initDatatable({
                url: '{{ route("backend.$module_name.index_data") }}',
                finalColumns,
                order: [
                    [0, 'desc']
                ],
            })
        })

        function editFaq(faq_id) {
            var route = "{{ route('backend.faq.edit', 'faq_id') }}".replace('faq_id', faq_id);
            window.location.href = route;
        }

        function deleteFaq(faq_id) {
            var route = "{{ route('backend.faq.delete', 'faq_id') }}".replace('faq_id', faq_id);
            confirmDelete(route, faq_id);
        }
        $(document).on('click', '[data-bs-toggle="tooltip"]', function () {
            $(this).tooltip('dispose');
            $('.tooltip').remove();
        });

    </script>
@endpush
