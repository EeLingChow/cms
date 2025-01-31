@php

$carbon = new Carbon\Carbon;
$today = $carbon->today()->format('Y-m-d');
$tomorrow = $carbon->tomorrow()->format('Y-m-d');

@endphp

<div class="row">
    <label class="col-3 col-form-label">{{ $r['label'] }}:</label>
    <div class="col-6 row">
        @if ($r['type'] == 'string')
            <input type="text" class="form-control" name="{{ rawurlencode($r['key']) }}"
                placeholder="{{ $r['placeholder'] ?: '' }}">
        @elseif ($r['type'] == 'date')
            <input type="date" class="form-control col-6" name="from_date" value="{{ $today }}">
            <input type="date" class="form-control col-6" name="to_date" value="{{ $tomorrow }}">
        @elseif ($r['type'] == 'multiselect')
            <select multiple="multiple" class="form-control selectpicker" data-live-search="true"
                name="{{ $r['key'] }}">
                @foreach ($r['options'] as $k => $v)
                    <option value="{{ $k }}">{{ $v }}
                    </option>
                @endforeach
            </select>
        @elseif ($r['type'] == 'select')
            <select class="form-control" name="{{ $r['key'] }}">
                @foreach ($r['options'] as $k => $v)
                    <option value="{{ $k }}">{{ $v }}
                    </option>
                @endforeach
            </select>
        @endif
        @if (isset($r['helper_text']))
            <span class="form-text text-muted">{{ $r['helper_text'] }}</span>
        @endif
    </div>
</div>
