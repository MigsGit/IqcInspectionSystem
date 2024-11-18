<!-- MODALS -->
<div class="modal fade" id="modalGenBarcode">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-qrcode"></i> Generate QR Code</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <center>
            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                      ->size(150)->errorCorrection('H')
                      ->generate('0')) !!}" id="imgGenBarcode" style="max-width: 200px;">
            <br>
            <label id="lblGenBarcodeVal">...</label>
          </center>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" id="btnPrintBarcode" class="btn btn-primary"><i id="iBtnPrintBarcodeIcon" class="fa fa-print"></i> Print</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

  <!-- MODALS -->
<div class="modal fade" id="modalLogout">
  <div class="modal-dialog">
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-user"></i> Logout</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formSignOut">
        @csrf
        <div class="modal-body">
          <label>Are you sure to logout?</label>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
          <button type="submit" id="btnSignOut" class="btn btn-primary"><i id="iBtnSignOutIcon" class="fa fa-check"></i> Yes</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

  <!-- MODALS -->
  <div class="modal fade" id="modalScanQRSave">
    <div class="modal-dialog modal-dialog-center">
      <div class="modal-content modal-sm ">
        {{-- <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-user"></i> Logout</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div> --}}
        <div class="modal-body">
          {{-- hidden_scanner_input --}}
          {{-- <input type="text" class="scanner w-100 hidden_scanner_input" id="txtScanUserId" name="scan_qr_code" autocomplete="off"> --}}
          <input type="text" class="w-100 hidden_scanner_input" id="txtScanUserId" name="scan_id" autocomplete="off">
          <div class="text-center text-secondary"><span id="modalScanQRSaveText">Please scan employee ID.</span><br><br><h1><i class="fa fa-qrcode fa-lg"></i></h1></div>
      </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

<script>
  $(document).ready(function(){
    $("#formSignOut").submit(function(event){
      event.preventDefault();
      SignOut();
    });

    $("body").css({"overflow-y" : "auto"});
    $(document).on("hidden.bs.modal", function () {
        $("body").addClass("modal-open");
        $("body").css({"overflow-y" : "auto"});
    });
    $(document).on("show.bs.modal", function () {
        $("body").css({"overflow-y" : "hidden"});
    });

    $('#modalScanQRSave').on('shown.bs.modal', function () {
      $('#txtScanUserId').focus();
      const mdlScanEmployeeID = document.querySelector("#modalScanQRSave");
      const inptScanEmployeeID = document.querySelector("#txtScanUserId");
      let focus = false

      mdlScanEmployeeID.addEventListener("mouseover", () => {
          if (inptScanEmployeeID === document.activeElement) {
              focus = true
          } else {
              focus = false
          }
      });

      mdlScanEmployeeID.addEventListener("click", () => {
          if (focus) {
              inptScanEmployeeID.focus()
          }
      });
    });

    $('#modalScanQRSave').on('hidden.bs.modal', function () {
      console.log('#modalScanQRSave hidden');
      $('#modalScanQRSaveText').html('Please scan employee ID.')
    });


});
</script>