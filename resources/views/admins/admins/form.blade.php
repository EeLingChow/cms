@php
$profile = new App\Models\Profile;
$profiles = $profile->getChoices(admin());

$form = [
    'username' => [
        'label' => 'User Name',
        'type' => 'text',
        'attributes' => [
            'required' => true,
        ],
    ],
    'password' => [
        'label' => 'Password',
        'type' => 'password',
        'empty' => true,
        'attributes' => [
            'required' => !$isEdit,
        ],
    ],
    'fullname' => [
        'label' => 'Full Name',
        'type' => 'text',
        'attributes' => [
            'required' => true,
        ],
    ],
	'profile_id' => [
		'label' => 'Profile',
		'type' => 'select',
		'options' => $profiles,
		'attributes' => [
			'required' => true,
		],
	],
];

@endphp

<form action="{{ $actionRoute }}" data-method="{{ $actionMethod }}"
    data-edit-url="{{ route('admins.edit', ['id' => '%ID%']) }}" class="ajax-form">
    {!! build_form($form, isset($data) ? $data : []) !!}
    @csrf
</form>
