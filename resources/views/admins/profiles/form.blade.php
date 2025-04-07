@php

$form = [
	'name' => [
		'label' => 'Profile Name',
		'type' => 'text',
		'attributes' => [
			'required' => true,		
		],
	],
	'is_superadmin' => [
		'label' => 'Super Admin',
		'type' => 'switch',
	],
];
@endphp

<form action="{{ $actionRoute }}" data-edit-url="{{ route('profiles.edit', ['id' => '%ID%']) }}" class="ajax-form">
	<h3 class="kt-section__title kt-section__title-lg">Profile Information</h3>

	{!! build_form($form, isset($data)? $data : []) !!}
	@csrf
	
	<h3 class="kt-section__title kt-section__title-lg">Modules</h3>
	<div class="card-columns custom" id="modules">
		@foreach ($modules as $masterid => $master)
		<div class="card">
			<div class="kt-portlet kt-portlet--bordered master-module">
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
							$hasSubmodule = $isEdit && isset($profileModules[$id]);
							$binary = $hasSubmodule? $profileModules[$id] : '0000';
						@endphp
					<div class="form-group row">
						<label class="col-5 col-form-label">
							<label class="kt-checkbox">
								<input type="checkbox" class="submodule" name="modules[]" value="{{ $id }}" {{ $hasSubmodule? 'checked' : '' }} />
								<strong>{{ $submodule['module'] }}</strong>
								<span></span>
							</label>
						</label>
						<div class="col-7">
							<div class="kt-checkbox-inline">
								<label class="kt-checkbox kt-checkbox--dark">
									<input type="checkbox"  name="permissions[{{ $id }}][]" data-permission="read" value="1"  {{ $binary[3] == '1' ? 'checked' : '' }}> Read
									<span></span>
								</label>
								<label class="kt-checkbox kt-checkbox--brand">
									<input type="checkbox" name="permissions[{{ $id }}][]" data-permission="create"  value="2"  {{ $binary[2] == '1' ? 'checked' : '' }}> Create
									<span></span>
								</label>
								<label class="kt-checkbox kt-checkbox--warning">
									<input type="checkbox" name="permissions[{{ $id }}][]" data-permission="update"  value="4"  {{ $binary[1] == '1' ? 'checked' : '' }}> Update
									<span></span>
								</label>
								<label class="kt-checkbox kt-checkbox--danger">
									<input type="checkbox" name="permissions[{{ $id }}][]" data-permission="delete"  value="8"  {{ $binary[0] == '1' ? 'checked' : '' }}> Delete
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
</form>

@section('page-script')
<script id="page-script">
function controller()
{
	var vm = this;
	vm.init = init;
	vm.toggleSelectAll = toggleSelectAll;

	function init()
	{
		$('[data-permission]').change(function() {
			let p = $(this).attr('data-permission');
			let $parent = $(this).parents('.master-module:first');
			$('[data-toggler="all"]', $parent).removeClass('active');
			$('[data-toggler="' + p + '"]', $parent).removeClass('active');
		});

		$('[data-toggler]').click(function() {
			let p = $(this).attr('data-toggler');
			let $parent = $(this).parents('.master-module:first');
			let $e = p == 'all'? $('[data-permission]', $parent) : $('[data-permission="' + p + '"]', $parent)	

			if ($(this).is('.active')) {
				$(this).removeClass('active');
				$e.prop('checked', false);
			} else {
				$(this).addClass('active');
				$e.prop('checked', true);
			}
		});

		$('.submodule-toggler').change(function() {
			if ($(this).is(':checked')) {
				$('input:checkbox', $(this).parents('.master-module:first')).prop('checked', true);
			} else {
				$('input:checkbox', $(this).parents('.master-module:first')).prop('checked', false);
			}
		});
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