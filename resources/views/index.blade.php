@extends('layouts.app')

@section('content')
    <div class="container">
        @if (!empty($access_token))
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div>{{ $access_token }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center mt-3">
            <div class="col-md-8">
                <div class="card border-success">
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>拉到群里先</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
