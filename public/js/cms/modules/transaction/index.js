/*==============================
 * Select product filter
 *==============================*/
$("#select-product").on('change',function(){
  var status = $("#select-status").val();
  var type= $("#select-product").val();
  window.location = BASE_URL+'/transactions?type='+type+'&status='+status;
});


/*==============================
 * Select status filter
 *==============================*/
$("#select-status").on('change',function(){
  var status = $("#select-status").val();
  var type= $("#select-product").val();
  window.location = BASE_URL+'/transactions?type='+type+'&status='+status;
});


/*==============================
 * Modal detail
 *==============================*/
$(".detail-btn").on('click',function(){
  id = $(this).attr('data-id');
  $("#detailModal").modal('show');
  $("#modal-body").html('Loading...').load(BASE_URL+'/transaction/'+id); 
});



/*==============================
 * Export
 *==============================*/
$("#export-btn").on('click',function(){

  window.location=BASE_URL+'/transaction/export'
});