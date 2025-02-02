@php
$category = new App\Models\Category();
$categories = $category->getChoices();

$floor = new App\Models\Floor();
$floors = $floor->getChoices();

$form = [
    'categories[]' => [
        'label' => 'Category',
        'type' => 'multiselect',
        'classes' => 'selectpicker',
        'options' => $categories,
        'attributes' => [
            'required' => true,
            'multiple' => true,
            'data-live-search' => 'true',
        ],
    ],
    'floor_id' => [
        'label' => 'Floor',
        'type' => 'select',
        'options' => $floors,
        'attributes' => [
            'required' => true,
        ],
    ],
    'name' => [
        'label' => 'Shop',
        'type' => 'text',
        'attributes' => [
            'required' => true,
        ],
    ],
];

@endphp

<form action="{{ $actionRoute }}" data-method="{{ $actionMethod }}"
    data-edit-url="{{ route('shops.edit', ['id' => '%ID%']) }}" class="ajax-form">
    {!! build_form($form, isset($data) ? $data : []) !!}
    @csrf
</form>
