@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Site Detail
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input disabled type="text" class="form-control" id="name" aria-describedby="name"
                                       value="{{$site->name}}">
                            </div>
                            <div class="form-group">
                                <label for="type">Type</label>
                                <input disabled type="text" class="form-control" id="type" aria-describedby="type"
                                       value="{{$site->type}}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
