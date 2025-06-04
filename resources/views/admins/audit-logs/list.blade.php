@php
$pageTitle = 'Audit Logs';
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
                            field: 'admin.username',
                            title: 'Admin',
                            textAlign: 'center',
                        },
                        {
                            field: 'action',
                            title: 'Action',
                            textAlign: 'center',
                            width: 100,
                        },
                        {
                            field: 'module',
                            title: 'Module',
                            textAlign: 'center',
                            width: 100,
                        },
                        {
                            field: 'ip',
                            title: 'IP Address',
                            textAlign: 'center',
                        },
                        {
                            field: 'uri',
                            title: 'URI',
                            textAlign: 'center',
                        },
                        {
                            field: 'postparams',
                            title: 'Postparams',
                            textAlign: 'left',
                            width: 250,
                            template: function(row) {
                                return '<pre>' + JSON.stringify(row['postparams'], null, 2) + '</pre>';
                            }
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
                    ],
                };

                vm.dt = $('.kt-datatable').KTDatatable(settings);
            }
        }

    </script>
@endsection
