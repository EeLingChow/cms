@php
$form = [
    'level' => [
        'label' => 'Level',
        'type' => 'text',
        'attributes' => [
            'required' => true,
        ],
    ],
];

@endphp

<form action="{{ $actionRoute }}" data-method="{{ $actionMethod }}"
    data-edit-url="{{ route('floors.edit', ['id' => '%ID%']) }}" class="ajax-form">
    {!! build_form($form, isset($data) ? $data : []) !!}
    @csrf
</form>
