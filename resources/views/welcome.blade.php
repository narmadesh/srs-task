<!DOCTYPE html>
<html lang="en">
<head>
  <title>SRS Digimind</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
</head>
<body>
    <div class="container">
      <div class="shadow bg-white p-4 mt-5">
        <button type="button" class="btn btn-primary float-right mb-5" data-toggle="modal" data-target="#upload_modal">Upload Data</button>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Column A</th>
              <th>Column B</th>
              <th>Column C</th>
            </tr>
          </thead>
          <tbody>
            @if(isset($data))
              @foreach($data as $table)
                <tr>
                  <td>{{$table->Column_A}}</td>
                  <td>{{$table->Column_B}}</td>
                  <td>{{$table->Column_C}}</td>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </div>
    </div>
    <div class="modal fade" id=upload_modal>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4>Upload excel</h4>
            <button class="close" data-dismiss="modal" type="button">&times;</button>
          </div>
          <div class="modal-body">
            <form method="post" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <input type="file" class="form-control" name="File" accept=".xls,.xlsx" required>
              </div>
              
          </div>
          <div class="modal-footer">
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">
                  Upload
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script>
      $(document).ready(function(){
        $("form").submit(function(e){
          e.preventDefault();
          $("button",this).html('Processing');
          $("button",this).attr('disabled',true);
          $.ajax({
            url:'/upload',
            type:'post',
            data:new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(data){
              $("button",this).html('Upload');
              $("button",this).attr('disabled',false);
              console.log(data);
              // var Data = JSON.parse(data);
              if(data.response=="error")
              {
                alert(data.message);
              }
              else
              {
                location.reload();
              }
            },
          });
        });
      });
    </script>
</body>

</html>