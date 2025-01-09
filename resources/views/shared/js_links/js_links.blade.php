<!-- jQuery -->
<script src="{{ asset('public/template/jquery/js/jquery.min.js') }}"></script>

<!-- Bootstrap 5 -->
<script src="{{ asset('public/template/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/template/bootstrap/js/popper.min.js') }}"></script>

<!-- AdminLTE -->
<script src="{{ asset('public/template/adminlte/js/adminlte.min.js') }}"></script>

<!-- DataTables -->
<script src="{{ asset('public/template/datatables/js/datatables.min.js') }}"></script>
{{-- <script src="{{ asset('/template/datatables/js/dataTables.bootstrap5.min.js') }}"></script> --}}

<!-- Select2 -->
<script src="{{ asset('public/template/select2/js/select2.min.js') }}"></script>

<!-- Toastr -->
<script src="{{ asset('public/template/toastr/js/toastr.min.js') }}"></script>

<script src="{{ asset('public/template/sweetalert/js/sweetalert2.min.js') }}"></script>

<!-- Bootstrap Datetimepicker -->
<script src="{{ asset('public/template/datetimepicker/js/datetimepicker.js') }}"></script>

<!-- Datepicker -->
<script src="{{ asset('public/js/bootstrap-datepicker.min.js') }}"></script>

<!-- smartWizard -->
<script src="{{ asset('public/js/jquery.smartWizard.min.js') }}"></script>

<script src="{{ asset('public/template/moment/moment.min.js') }}"></script>


<script src="{{ asset('public/template/jquerymask/js/jquery.mask.min.js') }}"></script> <!-- Only use for Second Molding -->
<script src="{{ asset('public/template/jquerytimepicker/js/jquery.timepicker.js') }}"></script> <!-- Only use for Second Molding -->
<script src="{{ asset('public/template/thirsttrap/js/thirsttrap2.js') }}"></script> <!-- Only use for Second Molding -->


<!-- Custom JS -->
<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "3000",
        "timeOut": "5000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "iconClass":  "toast-custom"
    };

    $.ajax({
        type: 'GET',
        url: 'check_user',
        dataType: 'json',
        success: function (response) {
            console.log(response);
            if(response.is_success === 'true'){
            }else{
            }
        },error: function (data, xhr, status){
           toastr.error(`Error: ${data.status}`);
        }
    });
</script>

<script src="{{ asset('public/js/main/Common.js') }}?t=<?=time()?>"></script>
<script src="{{ asset('public/js/main/User.js') }}?t=<?=time()?>"></script>
<script src="{{ asset('public/js/main/UserLevel.js') }}?t=<?=time()?>"></script>

{{-- IQC --}}
<script src="{{ asset('public/js/main/IqcInspection.js') }}?t=<?=time()?>"></script>
<script src="{{ asset('public/js/main/CnIqcInspection.js') }}?t=<?=time()?>"></script>
<script src="{{ asset('public/js/main/PpdIqcInspection.js') }}?t=<?=time()?>"></script>
<script src="{{ asset('public/js/main/YfIqcInspection.js') }}?t=<?=time()?>"></script>
<script src="{{ asset('public/js/main/Setting.js') }}?t=<?=time()?>"></script>




