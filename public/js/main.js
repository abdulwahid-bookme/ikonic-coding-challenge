
function getSuggestions(id=0) {
  $('#loader').removeClass('d-none');
  $.ajax({
    type:'get',
    url:"/suggestions",
    data:{id},
    success:function(response){
      if(response.status==true){
        $('#suggestions').empty();
        $('#loadmore').empty();

        // console.log(response.data.current_page,response.data.last_page);
        if(response.data.last_page > response.data.current_page){
          let loadhtml = ` <div class="d-flex justify-content-center mt-2 py-3 id="load_more_btn_parent">
        <button class="btn btn-primary" onclick="loadMoreSuggestions(${response.data.current_page})" " id="load_more_btn">Load more</button>
        </div>`;
        $('#loadmore').append(loadhtml);
        }
        response.data.data.map(
          function(suggestion) {
            
            html = `<div class="my-2 shadow  text-white bg-dark p-1" id="suggestion-sec">
            <div class="d-flex justify-content-between">
              <table class="ms-1">
                <tr>
                <td class="align-middle">${suggestion.name}</td>
                <td class="align-middle"> - </td>
                <td class="align-middle">${suggestion.email}</td>
                <td class="align-middle"></td>
                <td class="align-middle"> </td>
                </tr>
              </table>
              <div>
                <button id="create_request_btn_" onclick="connect(${suggestion.id})" class="btn btn-primary me-1">Connect</button>
              </div>
            </div>
        </div>`;
        
            $('#suggestions').append(html);
          }
        );

        
$('#loader').addClass('d-none');

      }
      else{
        console.log(response.message);
        $('#loader').addClass('d-none');

      }
    }
 });
}

function getSentRequests() {
$('#loader').removeClass('d-none');
  $.ajax({
    type:'get',
    url:"/get-sent-requests",
    data:{},
    success:function(response){
      if(response.status==true){
        $('#loadmore').empty();
        if(response.data.last_page > response.data.current_page){
          let loadhtml = ` <div class="d-flex justify-content-center mt-2 py-3 id="load_more_btn_parent">
        <button class="btn btn-primary" onclick="loadMoreRequests(${response.data.current_page})" " id="load_more_btn">Load more</button>
        </div>`;
        $('#loadmore').append(loadhtml);
        }
        $('#suggestions').empty();
        response.data.data.map(
          function(requests) {
            html = `<div class="my-2 shadow  text-white bg-dark p-1" id="requests-sec">
            <div class="d-flex justify-content-between">
              <table class="ms-1">
                <tr>
                <td class="align-middle">${requests.receiver_details.name}</td>
                <td class="align-middle"> - </td>
                <td class="align-middle">${requests.receiver_details.email}</td>
                <td class="align-middle"></td>
                <td class="align-middle"> </td>
                </tr>
              </table>
              <div>
                <button id="create_request_btn_" onclick="withdraw(${requests.id})" class="btn btn-danger me-1">Withdraw Request</button>
              </div>
            </div>
        </div>`;
            $('#suggestions').append(html);
          }
          
        );
        $('#loader').addClass('d-none');

      }
      else{
        $('#loader').addClass('d-none');
        console.log(response.message);
      }
    }
 });
}


function getReceivedRequests() {
  $('#loader').removeClass('d-none');
    $.ajax({
      type:'get',
      url:"/received-request",
      data:{},
      success:function(response){
        if(response.status==true){
          $('#loadmore').empty();
          if(response.data.last_page > response.data.current_page){
            let loadhtml = ` <div class="d-flex justify-content-center mt-2 py-3 id="load_more_btn_parent">
          <button class="btn btn-primary" onclick="loadMoreReceived(${response.data.current_page})" " id="load_more_btn">Load more</button>
          </div>`;
          $('#loadmore').append(loadhtml);
          }
    
          $('#suggestions').empty();
          response.data.data.map(
            function(received) {
              
              html = `<div class="my-2 shadow  text-white bg-dark p-1" id="received-sec">
              <div class="d-flex justify-content-between">
                <table class="ms-1">
                  <tr>
                  <td class="align-middle">${received.sender_details.name}</td>
                  <td class="align-middle"> - </td>
                  <td class="align-middle">${received.sender_details.email}</td>
                  <td class="align-middle"></td>
                  <td class="align-middle"> </td>
                  </tr>
                </table>
                <div>
                  <button id="create_request_btn_" onclick="accept(${received.id})" class="btn btn-primary me-1">Accept</button>
                </div>
              </div>
          </div>`;
              $('#suggestions').append(html);
            }
            
          );
          $('#loader').addClass('d-none');
  
        }
        else{
          $('#loader').addClass('d-none');
          console.log(response.message);
        }
      }
   });
  }
  
function getConnections() {
  $('#loader').removeClass('d-none');
    $.ajax({
      type:'get',
      url:"/get-connections",
      data:{},
      success:function(response){
        if(response.status==true){
          $('#suggestions').empty();

            response.data.map(
              function(data) {
                let user_data ;
                ("sender_details" in data) ? user_data=data.sender_details : user_data=data.receiver_details;
                
                html = `<div class="my-2 shadow  text-white bg-dark p-1" id="received-sec">
                <div class="d-flex justify-content-between">
                  <table class="ms-1">
                    <tr>
                    <td class="align-middle">${user_data.name}</td>
                    <td class="align-middle"> - </td>
                    <td class="align-middle">${user_data.email}</td>
                    <td class="align-middle"></td>
                    <td class="align-middle"> </td>
                    </tr>
                  </table>
  
                  <div>
                  <button id="create_request_btn_"  class="btn btn-primary me-1"
                  ${ (data.common) >0 ? '' :"disabled" }
                    >Connection in common (${data.common})</button>
  
                    <button id="create_request_btn_" onclick="removeConnection(${data.id})" class="btn btn-danger me-1">Remove Connection</button>
                  </div>
                </div>
            </div>`;
                $('#suggestions').append(html);
              }
          );
          
          $('#loader').addClass('d-none');
  
        }
        else{
          $('#loader').addClass('d-none');
          console.log(response.message);
        }
      }
   });
  }
function connect(id){
  $.ajax({
    type:'get',
    url:"/connect-request",
    data:{id},
    success:function(response){
      if(response.status==true){
        let fresh = new Promise(function(myResolve, myReject) {
        refreshCount();
        getSuggestions();
        });
        fresh.then(console.log('completed'));
      }
      else{
        console.log(response.message);
      }
    }
 });
}

function refreshCount() {
  $.ajax({
    type:'get',
    url:"/fresh-counts",
    data:{},
    success:function(response){
      if(response.status==true){
        $('#requests-count').empty();
        $('#received-count').empty();
        $('#connection-count').empty();
        $('#suggestions-count').empty();
        console.log(response.data);
        $('#suggestions-count').text(response.data.suggestions);
        $('#requests-count').text(response.data.requests);
        $('#received-count').text(response.data.received);
        $('#connection-count').text(response.data.connections);
      }
      else{
        console.log(response.message);
      }
    }
 });
}

function withdraw(id){
  $.ajax({
    type:'get',
    url:"/withdraw-request",
    data:{id},
    success:function(response){
      if(response.status==true){
        let fresh = new Promise(function(resol, rej) {
        refreshCount();
        getSentRequests();
        });
        fresh.then(console.log('completed'));
      }
      else{
        console.log(response.message);
      }
    }
 });
}

function removeConnection(id){
  $.ajax({
    type:'get',
    url:"/withdraw-request",
    data:{id},
    success:function(response){
      if(response.status==true){
        let fresh = new Promise(function(resol, rej) {
        refreshCount();
        getConnections();
        });
        fresh.then(console.log('completed'));
      }
      else{
        console.log(response.message);
      }
    }
 });
}

function accept(id){
  $.ajax({
    type:'get',
    url:"/accept-request",
    data:{id},
    success:function(response){
      if(response.status==true){
        let fresh = new Promise(function(resol, rej) {
        refreshCount();
        getReceivedRequests();
        });
        fresh.then(console.log('completed'));
      }
      else{
        console.log(response.message);
      }
    }
 });
}

function loadMoreSuggestions(page){
  page = page+1;
  $.ajax({
    type:'get',
    url:"/suggestions",
    data:{page},
    success:function(response){
      if(response.status==true){
        $('#loadmore').empty();
        if(response.data.last_page > response.data.current_page){
          let loadhtml = ` <div class="d-flex justify-content-center mt-2 py-3 id="load_more_btn_parent">
        <button class="btn btn-primary" onclick="loadMoreSuggestions(${response.data.current_page})" " id="load_more_btn">Load more</button>
        </div>`;
        $('#loadmore').append(loadhtml);
        }

        response.data.data.map(
          function(suggestion) {
            html = `<div class="my-2 shadow  text-white bg-dark p-1" id="suggestion-sec">
            <div class="d-flex justify-content-between">
              <table class="ms-1">
                <tr>
                <td class="align-middle">${suggestion.name}</td>
                <td class="align-middle"> - </td>
                <td class="align-middle">${suggestion.email}</td>
                <td class="align-middle"></td>
                <td class="align-middle"> </td>
                </tr>
              </table>
              <div>
                <button id="create_request_btn_" onclick="connect(${suggestion.id})" class="btn btn-primary me-1">Connect</button>
              </div>
            </div>
        </div>`;
        
            $('#suggestions').append(html);
          });
      }
      else{
        console.log(response.message);
      }
    }
 });
}
 function loadMoreRequests(page){
  page = page+1;
  $.ajax({
    type:'get',
    url:"/get-sent-requests",
    data:{page},
    success:function(response){
      if(response.status==true){
        if(response.data.last_page > response.data.current_page){
          let loadhtml = ` <div class="d-flex justify-content-center mt-2 py-3 id="load_more_btn_parent">
        <button class="btn btn-primary" onclick="loadMoreRequests(${response.data.current_page})" " id="load_more_btn">Load more</button>
        </div>`;
        $('#loadmore').append(loadhtml);
        }
        else{
          $('#loadmore').empty();
        }
        response.data.data.map(
          function(requests) {
            html = `<div class="my-2 shadow  text-white bg-dark p-1" id="requests-sec">
            <div class="d-flex justify-content-between">
              <table class="ms-1">
                <tr>
                <td class="align-middle">${requests.receiver_details.name}</td>
                <td class="align-middle"> - </td>
                <td class="align-middle">${requests.receiver_detailsemail}</td>
                <td class="align-middle"></td>
                <td class="align-middle"> </td>
                </tr>
              </table>
              <div>
              <button id="create_request_btn_" onclick="withdraw(${requests.id})" class="btn btn-danger me-1">Withdraw Request</button>
              </div>
            </div>
        </div>`;
        
            $('#suggestions').append(html);
          });
      }
      else{
        console.log(response.message);
      }
    }
 });
}

function loadMoreReceived(page){
  page = page+1;
  $.ajax({
    type:'get',
    url:"/received-request",
    data:{page},
    success:function(response){
      if(response.status==true){
        $('#loadmore').empty();
        if(response.data.last_page > response.data.current_page){
          let loadhtml = ` <div class="d-flex justify-content-center mt-2 py-3 id="load_more_btn_parent">
        <button class="btn btn-primary" onclick="loadMoreRequests(${response.data.current_page})" " id="load_more_btn">Load more</button>
        </div>`;
        $('#loadmore').append(loadhtml);
        }
        response.data.data.map(
          function(received) {
            html = `<div class="my-2 shadow  text-white bg-dark p-1" id="received-sec">
            <div class="d-flex justify-content-between">
              <table class="ms-1">
                <tr>
                <td class="align-middle">${received.sender_details.name}</td>
                <td class="align-middle"> - </td>
                <td class="align-middle">${received.sender_details.email}</td>
                <td class="align-middle"></td>
                <td class="align-middle"> </td>
                </tr>
              </table>
              <div>
              <button id="create_request_btn_" onclick="accept(${received.id})" class="btn btn-primary me-1">Accept</button>
              </div>
            </div>
        </div>`;
        
            $('#suggestions').append(html);
          });
      }
      else{
        console.log(response.message);
      }
    }
 });
}