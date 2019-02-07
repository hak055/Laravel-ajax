<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    {{-- dataTables --}}
       <link href="{{ asset('public/assets/datatables/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">

      {{-- SweetAlert2 --}}
     <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

       <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
       <link href="{{ asset('public/assets/bootstrap/css/ie10-viewport-bug-workaround.css') }}" rel="stylesheet">

      <script src="{{ asset('public/assets/bootstrap/js/ie-emulation-modes-warning.js') }}"></script>
    <title>Ajax crud</title>
  </head>
  <body>
     

    <div class="container">
      <h1>CRUD Operation Ajax and Laravel</h1>

      <div class="row">
        <div class="col-lg-12">
          <a onclick="addForm()" class="btn btn-primary pull-right" >Add Contact</a>
        <table id="contact-table" class="table table-striped table-dark">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Phone</th>
                  <th scope="col">Religion</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
          <tbody>   
              </tbody>
            </table>
          </div>
        </div>
        @include('form')
      </div>
   
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    {{-- dataTables --}}
    <script src="{{ asset('public/assets/dataTables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/assets/dataTables/js/dataTables.bootstrap.min.js') }}"></script>

    {{-- Validator --}}
    <script src="{{ asset('public/assets/validator/validator.min.js') }}"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="{{ asset('public/assets/bootstrap/js/ie10-viewport-bug-workaround.js') }}"></script>
 
    <script type="text/javascript">
      var table1 = $('#contact-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('all.contact') }}",
            columns: [
              {data:'id', name:'id'},
              {data:'name', name:'name'},
              {data:'email', name:'email'},
              {data:'phone', name:'phone'},
              {data:'religion', name:'religion'},
              {data:'action', name:'action', orderable: false, searchable: false}
            ]
          });
       
       //form 
           function addForm() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Add Contact');
        $('#insertbutton').text('Add Contact');
      }

       //Insert data by Ajax

         $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('contact') }}";
                    else url = "{{ url('contact') . '/' }}" + id;
                    $.ajax({
                        url : url,
                        type : "POST",
                        //data : $('#modal-form form').serialize(),
                        data: new FormData($("#modal-form form")[0]),
                       contentType: false,
                       processData: false,
                        success : function(data) {
                            $('#modal-form').modal('hide');
                            table1.ajax.reload();
                            swal({
                              title: "Good job!",
                              text: "You clicked the button!",
                              icon: "success",
                              button: "Great!",
                            });
                        },
                        error : function(data){
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })
                        }
                    });
                    return false;
                }
            });
        });
       //show single data ajax part here
       function showData(id) {
          $.ajax({
              url: "{{ url('contact') }}" + '/' + id,
              type: "GET",
              dataType: "JSON",
            success: function(data) {
              $('#single-data').modal('show');
              $('.modal-title').text(data.name +' '+'Informations');
              $('#contactid').text(data.id); 
                $('#fullname').text(data.name);
              $('#contactemail').text(data.email);
              $('#contactnumber').text(data.phone);
              $('#creligion').text(data.religion);
            },
            error : function() {
                alert("Ghorar DIm");
            }
          });
        }
      //edit ajax request are here
         function editForm(id) {
         save_method = 'edit';
          $('input[name=_method]').val('PATCH');
          $('#modal-form form')[0].reset();
          $.ajax({
            url: "{{ url('contact') }}" + '/' + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
              $('#modal-form').modal('show');
              $('.modal-title').text('Edit Contact');
              $('#insertbutton').text('Update Contact');
              $('#id').val(data.id);
              $('#name').val(data.name);
              $('#email').val(data.email);
              $('#phone').val(data.phone);
              $('#religion').val(data.religion);
            },
            error : function() {
                alert("Nothing Data");
            }
          });
        }    
//delete ajax request are here
      function deleteData(id){
          var csrf_token = $('meta[name="csrf-token"]').attr('content');
          swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this imaginary file!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              $.ajax({
                  url : "{{ url('contact') }}" + '/' + id,
                  type : "POST",
                  data : {'_method' : 'DELETE', '_token' : csrf_token},
                  success : function(data) {
                      table1.ajax.reload();
                      swal({
                        title: "Delete Done!",
                        text: "You clicked the button!",
                        icon: "success",
                        button: "Done",
                      });
                  },
                  error : function () {
                      swal({
                          title: 'Oops...',
                          text: data.message,
                          type: 'error',
                          timer: '1500'
                      })
                  }
              });
            } else {
              swal("Your imaginary file is safe!");
            }
          });

        }
    </script>
    
  </body>
</html>