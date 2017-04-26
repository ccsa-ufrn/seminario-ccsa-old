        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Enquire.js -->
    <script src="<?php echo asset_url(); ?>js/vendor/enquire.js"></script>

    <!-- jQuery -->
    <script src="<?php echo asset_url(); ?>js/vendor/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="<?php echo asset_url(); ?>js/vendor/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo asset_url(); ?>js/bckp/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Forms JavaScript -->
    <script src="<?php echo asset_url(); ?>js/bckp/jqBootstrapValidation.js"></script>
    <script src="<?php echo asset_url(); ?>js/bckp/validation.js"></script>

    <!-- Jquery File Uploader -->
    <script src="<?php echo asset_url(); ?>js/bckp/jquery.ui.widget.js"></script>
    <script src="<?php echo asset_url(); ?>js/bckp/jquery.iframe-transport.js"></script>
    <script src="<?php echo asset_url(); ?>js/bckp/jquery.fileupload.js"></script>

    <!-- Datable.js -->
    <script src="<?php echo asset_url(); ?>js/vendor/jquery.dataTables.min.js"></script>
    <script src="<?php echo asset_url(); ?>js/vendor/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo asset_url(); ?>js/bckp/sb-admin-2.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    <!-- Jquery Masked Input -->
    <script src="<?php echo asset_url(); ?>js/bckp/jquery.maskedinput.min.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.3.1/fullcalendar.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.3.1/locale-all.js"></script>

    <!-- Custom JavaScript -->
    <script src="<?php echo asset_url(); ?>js/bckp/custom.js?v=1.3.3"></script>

    <script>
      $(document).ready(function() {

          // page is now ready, initialize the calendar...

          // https://fullcalendar.io/docs/views/Available_Views/

          $('#calendar').fullCalendar({
              // put your options and callbacks here
              header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
              },
              defaultView: 'basicWeek',
              locale: 'pt',
              defaultDate: '2017-05-08',
          })

      });
    </script>

</body>

</html>
