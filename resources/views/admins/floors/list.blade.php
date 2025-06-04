@php
$pageTitle = 'Floors';
@endphp

@extends('admins.layout')

@section('title', $pageTitle)

@section('breadcrumb')
    <div class="kt-subheader__breadcrumbs">
        <a href="{{ route('admins.home') }}" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
        <span class="kt-subheader__breadcrumbs-separator"></span>
        <a href="javascript:;" class="kt-subheader__breadcrumbs-link">
            {{ $pageTitle }}
        </a>
    </div>
@endsection

@section('subheader-tools')
    @if (admin()->permissionAllowed($modulekey, 'create'))
        <a href="{{ route("{$modulename}.create") }}" class="btn kt-subheader__btn-primary">+ Add</a>
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="kt-portlet" data-ktportlet="true">
                <div class="kt-portlet__body">
                    <div class="kt-portlet__content">
                        <div class="row">
                            <div class="col-md-4 offset-md-8">
                                <div class="kt-input-icon kt-input-icon--left">
                                    <input type="text" class="form-control" placeholder="Search..." id="generalSearch">
                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                        <span><i class="la la-search"></i></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body kt-portlet__body--fit">
                    <div class="kt-datatable" id="kt-datatable"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script id="page-script">
        function controller() {
            var vm = this;
            vm.dt = null;
            vm.init = init;

            function init() {
                _p = {
                    editable: {{ admin()->permissionAllowed($modulekey, 'update') ? 'true' : 'false' }},
                    deletable: {{ admin()->permissionAllowed($modulekey, 'delete') ? 'true' : 'false' }},
                };

                Object.freeze(_p);

                loadData();
            }

            function loadData() {
                var settings = {
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                method: 'GET',
                                url: '{{ route("api.{$modulename}.list") }}',
                                pageSize: 100,
                                serverPaging: true,
                                serverFiltering: true,
                                serverSorting: false,
                            }
                        },
                        saveState: {
                            cookie: false,
                            webstorage: false,
                        }
                    },
                    layout: {
                        scroll: false,
                        footer: false,
                    },
                    sortable: true,
                    pagination: true,
                    search: {
                        input: $('#generalSearch'),
                    },
                    columns: [{
                            field: 'id',
                            title: '#',
                            sortable: 'asc',
                            width: 40,
                            type: 'number',
                            selector: false,
                            textAlign: 'center',
                        },
                        {
                            field: 'level',
                            title: 'Level',
                            textAlign: 'center',
                        },
                        {
                            field: 'meta.created',
                            title: 'Created At',
                            textAlign: 'center',
                        },
                        {
                            field: 'meta.updated',
                            title: 'Last Updated',
                            textAlign: 'center',
                        },
                        {
                            field: 'Actions',
                            title: 'Actions',
                            textAlign: 'center',
                            sortable: false,
                            overflow: 'visible',
                            autoHide: false,
                            template: function(row) {
                                var html = '',
                                    editRoute = '{{ route("{$modulename}.edit", ['id' => '%ID%']) }}',
                                    deleteRoute = '{{ route("api.{$modulename}.delete", ['id' => '%ID%']) }}';

                                if (_p.editable) {
                                    html +=
                                        `<a href="${editRoute.replace('%ID%', row.id)}" class="btn btn-sm btn-clean btn-icon btn-icon-sm" title="Edit"><i class="flaticon2-paper"></i></a>`
                                }

                                if (_p.deletable) {
                                    html +=
                                        `<a href="javascript:;" data-href="${deleteRoute.replace('%ID%', row.id)}" class="deletable btn btn-sm btn-clean btn-icon btn-icon-sm" title="Delete"><i class="flaticon2-trash"></i></a>`
                                }

                                return html;
                            }

                        },
                    ],
                };

                vm.dt = $('.kt-datatable').KTDatatable(settings);
            }
        }

    </script>
@endsection
