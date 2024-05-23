<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CodeIgniter Ajax CRUD using jQuery</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css">
    <script src="<?php echo base_url(); ?>jquery/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <h1 class="page-header text-center">CodeIgniter Ajax CRUD using jQuery</h1>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <button class="btn btn-primary" id="add"><span class="glyphicon glyphicon-plus"></span> Add New</button>
                <table class="table table-bordered table-striped" style="margin-top:20px;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>FullName</th>
                            <th>Status</th> <!-- New column for status -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                    <!-- Table body content populated dynamically -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal for Add New User -->
        <div id="addnew" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New User</h4>
                    </div>
                    <div class="modal-body">
                        <form id="addForm">
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="text" class="form-control" name="email" required>
                            </div>
                            <div class="form-group">
                                <label>Password:</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="form-group">
                                <label>Full Name:</label>
                                <input type="text" class="form-control" name="fname" required>
                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <input type="text" class="form-control" name="status" required>
                            </div>
                            <button type="submit" class="btn btn-success">Add User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Edit User -->
        <div id="editmodal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit User</h4>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            <input type="hidden" name="id" id="userid">
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="text" class="form-control" name="email" id="email" required>
                            </div>
                            <div class="form-group">
                                <label>Password:</label>
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>
                            <div class="form-group">
                                <label>Full Name:</label>
                                <input type="text" class="form-control" name="fname" id="fname" required>
                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <input type="text" class="form-control" name="status" id="status" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Delete User -->
        <div id="delmodal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Delete User</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete user: <strong id="delfname"></strong>?</p>
                        <form id="delform">
                            <input type="hidden" name="id" id="delid">
                            <button type="button" class="btn btn-danger" id="delid">Delete</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php echo $modal; ?>

        <script type="text/javascript">
            $(document).ready(function () {
                var url = '<?php echo base_url(); ?>';

                // Fetch table data on page load
                showTable();

                // Show add modal
                $('#add').click(function () {
                    $('#addnew').modal('show');
                    $('#addForm')[0].reset();
                });

                // Submit add form
                $('#addForm').submit(function (e) {
                    e.preventDefault();
                    var user = $('#addForm').serialize();
                    $.ajax({
                        type: 'POST',
                        url: url + 'user/insert',
                        data: user,
                        success: function () {
                            $('#addnew').modal('hide');
                            showTable();
                        }
                    });
                });

                // Show edit modal
                $(document).on('click', '.edit', function () {
                    var id = $(this).data('id');
                    $.ajax({
                        type: 'POST',
                        url: url + 'user/getuser',
                        dataType: 'json',
                        data: { id: id },
                        success: function (response) {
                            $('#email').val(response.email);
                            $('#password').val(response.password);
                            $('#fname').val(response.fname);
                            $('#status').val(response.status); // Populate status field
                            $('#userid').val(response.id);
                            $('#editmodal').modal('show');
                        }
                    });
                });

                // Update selected user
                $('#editForm').submit(function (e) {
                    e.preventDefault();
                    var user = $('#editForm').serialize();
                    $.ajax({
                        type: 'POST',
                        url: url + 'user/update',
                        data: user,
                        success: function () {
                            $('#editmodal').modal('hide');
                            showTable();
                        }
                    });
                });

                // Show delete modal
                $(document).on('click', '.delete', function () {
                    var id = $(this).data('id');
                    $.ajax({
                        type: 'POST',
                        url: url + 'user/getuser',
                        dataType: 'json',
                        data: { id: id },
                        success: function (response) {
                            $('#delfname').html(response.fname);
                            $('#delid').val(response.id);
                            $('#delmodal').modal('show');
                        }
                    });
                });

                // Delete user
                $('#delform').submit(function (e) {
                    e.preventDefault();
                    var id = $('#delid').val();
                    $.ajax({
                        type: 'POST',
                        url: url + 'user/delete',
                        data: { id: id },
                        success: function () {
                            $('#delmodal').modal('hide');
                            showTable();
                        }
                    });
                });

                // Function to fetch and display table data
                function showTable() {
                    $.ajax({
                        type: 'POST',
                        url: url + 'user/show',
                        success: function (response) {
                            $('#tbody').html(response);
                        }
                    });
                }
            });
        </script>
    </div>
</body>
</html>
