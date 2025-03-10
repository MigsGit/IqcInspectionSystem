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

<!-- moment js -->
<script src="{{ asset('public/template/moment/moment.min.js') }}"></script>

<script src="{{ asset('/public/template/echarts/dist/echarts.js') }}"></script>


{{-- <script src="{{ asset('/resources/js/bootstrap.js') }}"></script> --}}


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
    globalVar = {
        department : "add"
    }
    $.ajax({
        type: 'GET',
        url: 'check_user',
        dataType: 'json',
        success: function (response) {
            console.log('IS SESSION TRUE');
        },error: function (data, xhr, status){
           toastr.error(`Error: ${data.status}`);
        }
    });
    $.ajax({
        type: 'GET',
        url: 'check_department',
        dataType: 'json',
        success: function (response) {
            if(response.is_success === 'true'){
                globalVar.department = response.department;
                if(globalVar.department === 'TS' || globalVar.department === 'ISS'){
                    $('.nav-item-ts').removeClass('d-none',true)
                }
                if(globalVar.department === 'CN' || globalVar.department === 'ISS'){
                    $('.nav-item-cn').removeClass('d-none',true)
                }
                if(globalVar.department === 'PPS' || globalVar.department === 'PPD' || globalVar.department === 'ISS'){
                    $('.nav-item-ppd').removeClass('d-none',true)
                }
                if(globalVar.department === 'YF' || globalVar.department === 'ISS'){
                    $('.nav-item-yf').removeClass('d-none',true)
                }
                console.log('j',globalVar.department);

            }
        },error: function (data, xhr, status){
           toastr.error(`Error: ${data.status}`);
        }
    });
</script>

<script src="{{ asset('public/js/main/Common.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/main/User.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/main/UserLevel.js') }}?<?=time()?>"></script>

{{-- IQC --}}
<script src="{{ asset('public/js/main/IqcInspection.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/main/CnIqcInspection.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/main/PpdIqcInspection.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/main/YfIqcInspection.js') }}?<?=time()?>"></script>
<script src="{{ asset('public/js/main/Setting.js') }}?<?=time()?>"></script>




