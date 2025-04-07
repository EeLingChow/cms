@php
$module = new App\Models\Module;
$masters = $module->getMasterChoices(true);

$form = [
	'master_id' => [
		'label' => 'Master Module',
		'type' => 'select',
		'options' => $masters,
		'attributes' => [
			'required' => true,		
		],
	],
	'name' => [
		'label' => 'Module Name',
		'type' => 'text',
		'attributes' => [
			'required' => true,
		],
	],
	'modulekey' => [
		'label' => 'Module Key',
		'type' => 'text',
		'attributes' => [
			'required' => true,
		],
	],
	'sequence' => [
		'label' => 'Sequence',
		'type' => 'text',
		'attributes' => [
			'required' => true,
		],
	],
	'route' => [
		'label' => 'Module Route',
		'type' => 'text',
	],
	'is_superadmin' => [
		'label' => 'Super Admin',
		'type' => 'switch',
	],
	'is_hidden' => [
		'label' => 'Hidden',
		'type' => 'switch',
	],
];

@endphp

<form action="{{ $actionRoute }}" data-method="{{ $actionMethod }}"
    data-edit-url="{{ route('modules.edit', ['id' => '%ID%']) }}" class="ajax-form">
    {!! build_form($form, isset($data) ? $data : []) !!}
    @csrf
</form>
