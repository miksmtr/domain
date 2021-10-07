<?php $__env->startSection('content'); ?>





<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Content List </h2>
        </div>
        <div class="pull-right">
        <button onclick="getContent('Düzenlenmemiş');" class="btn btn-danger">Düzenlenmemiş</button>
        <button onclick="getContent('Taslak');"class="btn btn-warning">Taslak</button>
        <button onclick="getContent('Yayınlanmış');"class="btn btn-success">Yayınlanmış</button>
        </div>
    </div>
</div>



<table class="table table-bordered data-table">
        <thead>
            <tr>
             <th>id</th>
             <th>First Title</th>
             <th>Last Link</th>
             <th>Last Title</th>
             <th>Last Description</th>
             <th width="10px">Status</th>
             <th width="10px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>


    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js" ></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js" ></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js "  defer></script>

  

    
    <script type="text/javascript">
     function getContent(type) {
        $('.data-table').dataTable().fnFilter(type);

    }
      
    $.noConflict();
   
  $(function () {


  
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "<?php echo e(route('contents.index')); ?>",
          data: function (d) {
                d.approved = $('#approved').val(),
                
                d.search = $('input[type="search"]').val()
            }
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'first_title', name: 'first_title'},
            {data: 'last_link', name: 'last_link'},
            {data: 'last_title', name: 'last_title'},
            {data: 'last_description', name: 'last_description'},
            {data: 'statustext', name: 'statustext'},
            {data: 'action', name: 'action'},

        ]
    });
  
    $('#approved').change(function(){
        table.draw();
    });
      
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/nox/Sites/domain/resources/views/contents/index.blade.php ENDPATH**/ ?>