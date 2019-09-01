@extends('index')

@section('title')
    Chart drawer
@endsection

@section('draw_graphic')
    <div class="row">
        <form method="post" name="draw_form" id="draw_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <span class="inputs">
                <label for="start_date">Start date</label>
                <input type="date" name="start_date" id="start_date">
            </span>

            <span class="inputs">
                <label for="end_date">End date</label>
                <input type="date" name="end_date" id="end_date">
            </span>

            <span class="inputs">
                <label for="iso">ISO</label>
                <select name="iso" id="iso">
                    @foreach($iso_codes as $iso)
                        <option value="{{ $iso }}">
                            {{ $iso }}
                        </option>
                    @endforeach
                </select>
            </span>

            <span class="inputs">
                <span id="draw" class="btn btn-success" onclick="drawGoogleChart()"> Draw </span>
            </span>
        </form>

        <!-- GOOGLE CHARTS -->
        <div id="curve_chart"></div>
    </div>
@endsection
