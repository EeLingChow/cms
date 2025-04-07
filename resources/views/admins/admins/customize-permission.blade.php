@php
$pageTitle = 'Customize Permission';
$isEdit = true;
$moduleName = 'admins';
@endphp

@extends('admins.layout')

@section('title', $pageTitle)

@section('breadcrumb')
<div class="kt-subheader__breadcrumbs">
    <a href="{{ route('admins.home') }}" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route($moduleName . '.list') }}" class="kt-subheader__breadcrumbs-link">
       	Admins
    </a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route($moduleName . '.edit', ['id' => $id]) }}" class="kt-subheader__breadcrumbs-link">
       	{{ $data['fullname'] }}
    </a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="javascript:;" class="kt-subheader__breadcrumbs-link">
       	{{ $pageTitle }}
    </a>
</div>
@endsection

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile form-wrapper">
			<div class="kt-portlet__head kt-portlet__head--lg" style="">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">{{ $pageTitle }}</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<a href="javascript:history.back();" class="btn btn-clean kt-margin-r-10">
						<i class="la la-arrow-left"></i>
						<span class="kt-hidden-mobile">Back</span>
					</a>
					<div class="btn-group kt-margin-r-10">

						<button type="button" class="btn btn-outline-brand " onclick="_ctrl.toggleSelectAll(true)">
							<i class="la la-list-alt"></i> 
							<span class="kt-hidden-mobile">Select All</span>
						</button>
						<button type="button" class="btn btn-outline-brand dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						</button>
						<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(123px, 38px, 0px);">
							<ul class="kt-nav">
								<li class="kt-nav__item">
									<a href="javascript:_ctrl.toggleSelectAll(false)" class="kt-nav__link">
										<span class="kt-nav__link-text">Deselect All</span>
									</a>
								</li>
							</ul>
						</div>
					</div>

					<div class="btn-group">
						<button type="button" class="btn btn-brand btn-submit" onclick="submitForm(0)">
							<i class="la la-check"></i> 
							<span class="kt-hidden-mobile">Save</span>
						</button>
						<button type="button" class="btn btn-brand dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						</button>
						<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(123px, 38px, 0px);">
							<ul class="kt-nav">
								<li class="kt-nav__item">
									<a href="javascript:submitForm(2)" class="kt-nav__link">
										<span class="kt-nav__link-text">Save & Continue</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">
				<div class="row">
					<div class="col-xl-12">
						<form action="{{ route('admins.api.customize-permission', ['id' => $id]) }}" method="post" class="ajax-form">
							<div class="card-columns custom" id="modules">
								@foreach ($modules as $masterid => $master)
								<div class="card">
									<div class="kt-portlet kt-portlet--bordered master-module" data-master-id="{{ $masterid }}">
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<label class="kt-checkbox">
													<input type="checkbox" class="submodule-toggler">
													<h3 class="kt-portlet__head-title">
														{{ $master['module'] }}
													</h3>
													<span></span>
												</label>
											</div>
											<div class="kt-portlet__head-toolbar">
												<div class="kt-portlet__head-actions">
													<a href="javascript:;" class="btn btn-outline-primary btn-pill btn-sm btn-icon btn-icon-md"
														data-toggle="kt-tooltip" data-placement="top" data-original-title="Toggle All Permission" data-toggler="all"
													>
														All
													</a>
													<a href="javascript:;" class="btn btn-outline-dark btn-pill btn-sm btn-icon btn-icon-md"
														data-toggle="kt-tooltip" data-placement="top" data-original-title="Toggle Read Permission" data-toggler="read"
													>
														R
													</a>
													<a href="javascript:;" class="btn btn-outline-brand btn-pill btn-sm btn-icon btn-icon-md"
														data-toggle="kt-tooltip" data-placement="top" data-original-title="Toggle Create Permission" data-toggler="create"
													>
														C
													</a>
													<a href="javascript:;" class="btn btn-outline-warning btn-pill btn-sm btn-icon btn-icon-md"
														data-toggle="kt-tooltip" data-placement="top" data-original-title="Toggle Update Permission" data-toggler="update"
													>
														U
													</a>
													<a href="javascript:;" class="btn btn-outline-danger btn-pill btn-sm btn-icon btn-icon-md"
														data-toggle="kt-tooltip" data-placement="top" data-original-title="Toggle Delete Permission" data-toggler="delete"
													>
														D
													</a>
												</div>
											</div>
										</div>
										<div class="kt-portlet__body">

											@foreach ($master['data'] as $id => $submodule)
												@php	
													
													$hasSubmodule = isset($profileModules[$id]);
													$binary = $hasSubmodule? $profileModules[$id] : '0000';
													$style = 'color:green';
												@endphp
											<div class="form-group row submodule-wrapper" data-module-id="{{ $id }}">
												<label class="col-5 col-form-label">
													<label class="kt-checkbox">
														<input type="checkbox" class="submodule" name="modules[]" value="{{ $id }}" {{ $hasSubmodule? 'checked' : '' }} />
														<strong style="{{ $hasSubmodule? $style : '' }}">{{ $submodule['module'] }}</strong>
														<span></span>
													</label>
												</label>
												<div class="col-7">
													<div class="kt-checkbox-inline">
														<label class="kt-checkbox kt-checkbox--dark" style="{{ $binary[3] == '1' ? $style : '' }}">
															<input type="checkbox"  name="permissions[{{ $id }}][]" data-permission="read" value="1" {{ $binary[3] == '1' ? 'checked' : '' }}> Read
															<span></span>
														</label>
														<label class="kt-checkbox kt-checkbox--brand" style="{{ $binary[2] == '1' ? $style : '' }}">
															<input type="checkbox" name="permissions[{{ $id }}][]" data-permission="create"  value="2" {{ $binary[3] == '1' ? 'checked' : '' }}> Create
															<span></span>
														</label>
														<label class="kt-checkbox kt-checkbox--warning" style="{{ $binary[1] == '1' ? $style : '' }}">
															<input type="checkbox" name="permissions[{{ $id }}][]" data-permission="update"  value="4" {{ $binary[3] == '1' ? 'checked' : '' }}> Update
															<span></span>
														</label>
														<label class="kt-checkbox kt-checkbox--danger" style="{{ $binary[0] == '1' ? $style : '' }}">
															<input type="checkbox" name="permissions[{{ $id }}][]" data-permission="delete"  value="8" {{ $binary[3] == '1' ? 'checked' : '' }}> Delete
															<span></span>
														</label>
													</div>
												</div>
											</div>
											@endforeach	
										</div>
									</div>
								</div>
								@endforeach
							</div>
							@csrf
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('page-script')
<script id="page-script">
function controller()
{
	var vm = this;
	vm.init = init;
	vm.toggleSelectAll = toggleSelectAll;

	function init()
	{
		let overrides = @json($adminModules);

		for (id in overrides) {
			let $parent = $(`.submodule-wrapper[data-module-id="${id}"]`);
			let d = overrides[id];

			if (d.plusminus == 1) {
				$(`.submodule[value="${id}"]`).prop('checked', true);
			} else {
				$(`.submodule[value="${id}"]`).prop('checked', false);
			}
			
			let permissions = ['delete', 'update', 'create', 'read'];
			for (let i = 0; i < d.permission.length; i++) {
				if (d.permission[i] == 1) {
					$(`[data-permission="${permissions[i]}"]`, $parent).prop('checked', true);
				} else {
					$(`[data-permission="${permissions[i]}"]`, $parent).prop('checked', false);
				}
			}
			
		}
	}

	function toggleSelectAll(selection)
	{
		if (selection) {
			$('#modules input:checkbox').prop('checked', true);	
		} else {
			$('#modules input:checkbox').prop('checked', false);	
		}	
	}
}
</script>
@endsection