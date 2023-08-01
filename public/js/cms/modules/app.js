/*==============================
 * Custom sweet alert button
 *==============================*/
const swalBootstrap = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-light px-4 mb-4',
  },
  buttonsStyling: false
});

const swalBootstrapDelete = Swal.mixin({
  title: 'Are You Sure?',
  text: 'Data will be deleted!',
  icon: "warning",
  buttonsStyling: false,
  customClass: {
    confirmButton: 'btn btn-danger btn-lg me-2 mb-3',
    cancelButton: 'btn btn-secondary btn-lg mb-3',
  },
  showCancelButton: true,
  confirmButtonText: 'Delete!'
});


/*==============================
 * SWAL notification
 *==============================*/
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  showClass: {
      popup: "animate__animated animate__fadeIn",
    },
  hideClass: {
      popup: "animate__animated animate__fadeOut",
  },
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  },
});


/*==============================
 * Set form property
 *==============================*/
$(function(){
  $('.datepicker').datepicker({
    format: "yyyy-mm-dd",
  });
});


/*==============================
 * Set form property
 *==============================*/
function disableForm(){
  $("input").prop("disabled",true);
  $("select").prop("disabled",true);
  $(".btn").prop("disabled",true);  
}

function enableForm(){
  $("input").prop("disabled",false);
  $("select").prop("disabled",false);
  $(".btn").prop("disabled",false);  
}


$(".page-link").on('click',function(event){

  event.preventDefault();

  url = $(this).attr("href");
  url_replace = url.replace("http://devel.cocorolife.id:443", BASE_URL);
  window.location=url_replace;

});