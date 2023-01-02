@extends('layouts.app')

@section('content')

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="{{ asset('js/helper.js') }}?v={{ time() }}" defer></script>
  <script src="{{ asset('js/main.js') }}?v={{ time() }}" defer></script>
  <div class="container">
    <x-dashboard />

  <div class="row justify-content-center mt-5">
  <div class="col-12">
    <div class="card shadow  text-white bg-dark">
      <div class="card-header">Ikonic Coding Challenge - Network connections</div>
      <div class="card-body">
        <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">            
          <a href="javascript:void(0)" onclick="getSuggestions()" class="btn btn-outline-primary">Suggestions (<span id="suggestions-count">{{$suggestions_count}}</span>) </a>

          <a href="javascript:void(0)" onclick="getSentRequests()" class="btn btn-outline-primary">Sent Requests(<span id="requests-count">{{$requests_count}}</span>)</a>

          <a href="javascript:void(0)" onclick="getReceivedRequests()" class="btn btn-outline-primary">Received Requests (<span id="received-count">{{$received_count}}</span>)</a>

          <a href="javascript:void(0)" onclick="getConnections()"() class="btn btn-outline-primary">Connections (<span id="connection-count">{{$connection_count}}</span>)</a>
        </div>
        <hr>
        <div id="loader" class="d-none">
        @for ($i = 0; $i < 10; $i++)
        <x-skeleton />
         @endfor
        </div>
        <!-- Suggestions -->
        <div id="suggestions">

        </div>
        <div id="loadmore"></div>
        <!-- Suggestions-end -->
        
      </div>
    </div>
  </div>
</div>
</div>
@endsection