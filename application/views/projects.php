<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $title; ?></title>
    <meta charset="utf-8">
    <meta name="app-url" content="<?php echo base_url('/'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-5 mb-3"><?php echo $title; ?></h2>
        <div class="card">
            <div class="card-header">
                <button class="btn btn-outline-primary" onclick="createProject()">Create New Project</button>
            </div>
            <div class="card-body">
                <div id="alert-div"></div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th width="240px">Action</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="projects-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- modal for creating and editing function -->
    <div class="modal" tabindex="-1" role="dialog" id="form-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Project Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modal-alert-div"></div>
                    <form>
                        <input type="hidden" name="update_id" id="update_id">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" rows="3" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="Completed">Completed</option>
                                <option value="In Completed">In Completed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-primary" id="save-project-btn">Save
                            Project</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- view record modal -->
    <div class="modal" tabindex="-1" role="dialog" id="view-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Project Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <b>Name:</b>
                    <p id="name-info"></p>
                    <b>Description:</b>
                    <p id="description-info"></p>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        $(document).ready(function () {
            showAllProjects();
        });

        function showAllProjects() {
            const url = $('meta[name=app-url]').attr("content") + "project/show_all";
            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    const projects = response;
                    const tbody = $("#projects-table-body");
                    tbody.empty();
                    projects.forEach(project => {
                        const projectRow = `
                            <tr>
                                <td>${project.name}</td>
                                <td>${project.description}</td>
                                <td>
                                    <button class="btn btn-outline-info" onclick="showProject(${project.id})">Show</button>
                                    <button class="btn btn-outline-success" onclick="editProject(${project.id})">Edit</button>
                                    <button class="btn btn-outline-danger" onclick="destroyProject(${project.id})">Delete</button>
                                </td>
                                <td>${project.status}</td>
                            </tr>`;
                        tbody.append(projectRow);
                    });
                },
                error: function (response) {
                    console.error(response);
                }
            });
        }

        $("#save-project-btn").click(function (event) {
            event.preventDefault();
            const isUpdate = $("#update_id").val();
            isUpdate ? updateProject() : storeProject();
        });

        function createProject() {
            resetForm();
            $("#form-modal").modal('show');
        }

        function storeProject() {
            toggleSaveButton(true);
            const url = $('meta[name=app-url]').attr("content") + "project/store";
            const data = {
                name: $("#name").val(),
                description: $("#description").val(),
                status: $("#status").val()
            };
            $.ajax({
                url: url,
                type: "POST",
                data: data,
                success: function (response) {
                    toggleSaveButton(false);
                    displayAlert("#alert-div", "success", "Project Created Successfully");
                    resetForm();
                    showAllProjects();
                    $("#form-modal").modal('hide');
                },
                error: function (response) {
                    toggleSaveButton(false);
                    console.log(response)
                    displayValidationErrors(response, "#modal-alert-div");
                }
            });
        }

        function editProject(id) {
            const url = $('meta[name=app-url]').attr("content") + "project/edit/" + id;
            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    const project = response;
                    resetForm();
                    $("#update_id").val(project.id);
                    $("#name").val(project.name);
                    $("#description").val(project.description);
                    $("#form-modal").modal('show');
                },
                error: function (response) {
                    console.error(response);
                }
            });
        }

        function updateProject() {
            toggleSaveButton(true);
            const url = $('meta[name=app-url]').attr("content") + "project/update/" + $("#update_id").val();
            const data = {
                id: $("#update_id").val(),
                name: $("#name").val(),
                description: $("#description").val(),
            };
            $.ajax({
                url: url,
                type: "POST",
                data: data,
                success: function (response) {
                    toggleSaveButton(false);
                    displayAlert("#alert-div", "success", "Project Updated Successfully");
                    resetForm();
                    showAllProjects();
                    $("#form-modal").modal('hide');
                },
                error: function (response) {
                    toggleSaveButton(false);
                    displayValidationErrors(response, "#modal-alert-div");
                }
            });
        }

        function showProject(id) {
            const url = $('meta[name=app-url]').attr("content") + "project/show/" + id;
            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    const project = response;
                    $("#name-info").text(project.name);
                    $("#description-info").text(project.description);
                    $("#view-modal").modal('show');
                },
                error: function (response) {
                    console.error(response);
                }
            });
        }

        function destroyProject(id) {
            const url = $('meta[name=app-url]').attr("content") + "project/delete/" + id;
            $.ajax({
                url: url,
                type: "DELETE",
                success: function (response) {
                    displayAlert("#alert-div", "success", "Project Deleted Successfully");
                    showAllProjects();
                },
                error: function (response) {
                    console.error(response);
                }
            });
        }

        function resetForm() {
            $("#alert-div").empty();
            $("#modal-alert-div").empty();
            $("#update_id").val("");
            $("#name").val("");
            $("#description").val("");
        }

        function toggleSaveButton(disabled) {
            $("#save-project-btn").prop('disabled', disabled);
        }

        function displayAlert(selector, type, message) {
            const alertHtml = `<div class="alert alert-${type}" role="alert"><b>${message}</b></div>`;
            $(selector).html(alertHtml);
        }

        function displayValidationErrors(response, selector) {
            const responseData = JSON.parse(response.responseText);
            if (responseData.errors) {
                const errorHtml = `<div class="alert alert-danger" role="alert"><b>Validation Error!</b> ${responseData.errors}</div>`;
                $(selector).html(errorHtml);
            }
        }

    </script>
</body>

</html>