@php
$required = isset($settings['attributes']) && isset($settings['attributes']['required']) && ($settings['attributes']['required'] == 'required' || $settings['attributes']['required'] == true);
$value = !empty($data) ? $data->parseFormValue($key) : '';

$attributes = [];
if (isset($settings['attributes'])) {
    foreach ($settings['attributes'] as $k => $v) {
        if ($k == 'data-href') {
            $attributes[] = $k . '=' . $v;
        } else {
            $attributes[] = $k . (!is_bool($v) ? '="' . $v . '"' : '');
        }
    }
}

$classes = isset($settings['classes']) ? $settings['classes'] : '';

$carbon = new Carbon\Carbon();
$now = $carbon->now()->format('Y-m-d\TH:i');
@endphp

<div class="form-group row" data-group="{{ $key }}">
    <label class="col-3 col-form-label" for="input-{{ $key }}">
        {{ $settings['label'] }}
    </label>
    <div class="col-{{ $inputWidth }}">
        @switch ($settings['type'])
            @case('select')
            <select name="{{ $key }}" id="input-{{ $key }}" class="form-control {{ $classes }}"
                {{ implode(' ', $attributes) }}>
                @if (isset($settings['options']))
                    @foreach ($settings['options'] as $k => $v)
                        <option value="{{ $k }}" {{ $value === $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                @endif
            </select>
            @break
            @case('multiselect')
            <select name="{{ $key }}" id="input-{{ $key }}" class="form-control {{ $classes }}" {{ implode(' ', $attributes) }}>
                @if (isset($settings['options']))
                    @if ($key == 'categories[]')
                        @foreach ($settings['options'] as $k => $v)
                            <option value="{{ $k }}" {{ !empty($data) && in_array($k, $data->categories->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    @endif
                @endif
            </select>
            @break
            @case('textarea')
            <textarea name="{{ $key }}" id="input-{{ $key }}" rows="5"
                class="form-control {{ $classes }}" {{ implode(' ', $attributes) }}>{{ $value }}</textarea>
            @break
            @case('html')
            <textarea name="{{ $key }}" id="input-{{ $key }}" rows="30"
                class="summernote form-control {{ $classes }}"
                {{ implode(' ', $attributes) }}>{{ $value }}</textarea>
            @break
            @case('json')
            <textarea name="{{ $key }}" id="input-{{ $key }}" rows="30"
                class="form-control {{ $classes }}" style="display:none;"
                {{ implode(' ', $attributes) }}>{{ $value }}</textarea>
            <div id="json-editor" class="json" data-for="input-{{ $key }}">{{ $value }}</div>
            @break
            @case('switch')
            <span class="kt-switch kt-switch--icon">
                <label>
                    <input type="checkbox" {{ isset($data[$key]) && $data[$key] == 1 ? 'checked' : '' }}
                        name="{{ $key }}" />
                    <span></span>
                </label>
            </span>
            @break
            @case('datetime')
            <input id="input-{{ $key }}" class="form-control {{ $classes }}" name="{{ $key }}"
                type="datetime-local"
                value="{{ isset($settings['empty']) && $settings['empty'] ? $now : $carbon->parse($value)->format('Y-m-d\TH:i') }}"
                {{ implode(' ', $attributes) }} />
            @break;
            @case('text')
            @default
            <input id="input-{{ $key }}" class="form-control {{ $classes }}" name="{{ $key }}"
                type="{{ $settings['type'] }}"
                value="{{ isset($settings['empty']) && $settings['empty'] ? '' : $value }}"
                {{ implode(' ', $attributes) }} />
            @break;
        @endswitch
        <div class="invalid-feedback"></div>
        @if (isset($settings['helpBlock']))
            <span class="form-text text-muted">{!! $settings['helpBlock'] !!}</span>
        @endif
    </div>
</div>
