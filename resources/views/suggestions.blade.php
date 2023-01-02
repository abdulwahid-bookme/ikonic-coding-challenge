@extends('layouts.app')

@section('content')

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="{{ asset('js/helper.js') }}?v={{ time() }}" defer></script>
  <script src="{{ asset('js/main.js') }}?v={{ time() }}" defer></script>

  <div class="container">
    <x-dashboard />
    <x-menu />
    
    <div class="row justify-content-center mt-5">
      <div class="col-12">
        <div class="card shadow  text-white bg-dark">
          <div class="card-header">Ikonic Coding Challenge - Network connections</div>
            <div class="card-body">
            @foreach($suggestions as $suggestion )

              <div class="my-2 shadow  text-white bg-dark p-1" id="">
                  <div class="d-flex justify-content-between">
                    <table class="ms-1">
                      <td class="align-middle">{{$suggestion->name}}</td>
                      <td class="align-middle"> - </td>
                      <td class="align-middle">{{$suggestion->email}}</td>
                      <td class="align-middle"></td>
                      <td class="align-middle"> 
                    </table>
                    <div>
                      <button id="create_request_btn_" class="btn btn-primary me-1">Connect</button>
                    </div>
                  </div>
                </div>
                @endforeach       
            <div class="d-flex justify-content-center mt-2 py-3 {{-- d-none --}}" id="load_more_btn_parent">
              <button :click="{{$loadmore}}" class="btn btn-primary" id="load_more_suggestion">Load more</button>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection

 