@extends('index')

@section('title')
    By_date_by_iso
@endsection

@section('by_date_by_iso')
    <div class="row">
        <form method="post" name="get_data" id="get_data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <span class="inputs">
                <label for="date">Enter date</label>
                <input type="date" value="{{ $date }}" name="date" id="date" onchange="getDataFromSoap()">
            </span>

            <span class="inputs">
                <label for="count">Change</label>
                <input type="text" name="count" id="count" class="inputs" value="{{ $count }}">
                <select name="iso" id="iso" title="ISO">
                    @foreach($iso_codes as $val)
                        <option value="{{ $val }}" @if( $val == $iso) selected @endif >
                            {{ $val }}
                        </option>
                    @endforeach
                </select>
            </span>

            <span class="inputs">
                <input type="submit" value="Convert to" id="get_values" class="btn btn-success" onclick="changeData()">
            </span>

            @if( $converted_value != '')
                <span id="convert_value" class="result">
                    {{ $converted_value }} դրամ
                </span>
            @endif
        </form>
    </div>

    <div id="response">

    </div>

@endsection
