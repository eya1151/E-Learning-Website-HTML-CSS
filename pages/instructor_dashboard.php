<?php
require_once("../connexion/conx.php");
//session_start();

// Retrieve user data from the database
$_SESSION["id"] = 1; // User's session
$sessionId = $_SESSION["id"];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user_form WHERE id = $sessionId"));

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update profile picture
    if (isset($_FILES["image"]["name"])) {
        $id = $_POST["id"];
        $name = $_POST["first_name"];

        $imageName = $_FILES["image"]["name"];
        $imageSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];

        // Image validation
        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $imageName);
        $imageExtension = strtolower(end($imageExtension));
        if (!in_array($imageExtension, $validImageExtension)) {
            echo "
            <script>
                alert('Invalid Image Extension');
                window.location.href = 'user_profile.php';
            </script>
            ";
            exit;
        } elseif ($imageSize > 1200000) {
            echo "
            <script>
                alert('Image Size Is Too Large');
                window.location.href = 'user_profile.php';
            </script>
            ";
            exit;
        } else {
            $newImageName = $name . " - " . date("Y.m.d") . " - " . date("h.i.sa"); // Generate new image name
            $newImageName .= '.' . $imageExtension;
            $query = "UPDATE user_form SET pp = '$newImageName' WHERE id = $id"; // Update profile picture in the database
            mysqli_query($conn, $query);
            move_uploaded_file($tmpName, 'img/' . $newImageName);
        }
    }

    // Update other user information
    $id = $_POST["id"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $uname = $_POST["uname"];
    $uprenom = $_POST["uprenom"];
    $adresse = $_POST["adresse"];
    $level = $_POST["level"];

    $query = "UPDATE user_form SET uname = '$uname', email = '$email', phone = '$phone', uname = '$uname', uprenom = '$uprenom', adresse = '$adresse', level = '$level' WHERE id = $id";
    mysqli_query($conn, $query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Secret Coder: Login</title>
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta content="" name="keywords">
<meta content="" name="description">
<link href="img/icon.png" rel="icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="../lib/animate/animate.min.css" rel="stylesheet">
<link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/style.css" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<style>
    body{
    background:#f3f3f3;
    margin-top:20px;
    color: #616f80;
}
.card {
    border: none;
    margin-bottom: 24px;
    -webkit-box-shadow: 0 0 13px 0 rgba(236,236,241,.44);
    box-shadow: 0 0 13px 0 rgba(236,236,241,.44);
}

.avatar-xs {
    height: 2.3rem;
    width: 2.3rem;
}
</style>
</head>
<body>
<?php include '../repite/user_header.php'; ?>
<div></div>
<div class="container">
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-pattern">
                <div class="card-body">
                    <div class="float-right">
                        <i class="fa fa-archive text-primary h4 ml-3"></i>
                    </div>
                    <h5 class="font-size-20 mt-0 pt-1">24</h5>
                    <p class="text-muted mb-0">Total Projects</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-pattern">
                <div class="card-body">
                    <div class="float-right">
                        <i class="fa fa-th text-primary h4 ml-3"></i>
                    </div>
                    <h5 class="font-size-20 mt-0 pt-1">18</h5>
                    <p class="text-muted mb-0">Completed Projects</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-pattern">
                <div class="card-body">
                    <div class="float-right">
                        <i class="fa fa-file text-primary h4 ml-3"></i>
                    </div>
                    <h5 class="font-size-20 mt-0 pt-1">06</h5>
                    <p class="text-muted mb-0">Pending Projects</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <form>
                        <div class="form-group mb-0">
                            <label>Search</label>
                            <div class="input-group mb-0">
                                <input type="text" class="form-control" placeholder="Search..." aria-describedby="project-search-addon" />
                                <div class="input-group-append">
                                    <button class="btn btn-danger" type="button" id="project-search-addon"><i class="fa fa-search search-icon font-12"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive project-list">
                        <table class="table project-table table-centered table-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Projects</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Team</th>
                                    <th scope="col">Progress</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>New admin Design</td>
                                    <td>02/5/2019</td>
                                    <td>
                                        <span class="text-success font-12"><i class="mdi mdi-checkbox-blank-circle mr-1"></i> Completed</span>
                                    </td>
                                    <td>
                                        <div class="team">
                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Roger Drake">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar6.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>

                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Reggie James">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>

                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Gerald Mayberry">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar8.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0">Progress<span class="float-right">100%</span></p>

                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="action">
                                            <a href="#" class="text-success mr-4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"> <i class="fa fa-pencil h5 m-0"></i></a>
                                            <a href="#" class="text-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Close"> <i class="fa fa-remove h5 m-0"></i></a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">2</th>
                                    <td>Landing page Design</td>
                                    <td>04/6/2019</td>
                                    <td>
                                        <span class="text-primary font-12"><i class="mdi mdi-checkbox-blank-circle mr-1"></i> Pending</span>
                                    </td>
                                    <td>
                                        <div class="team">
                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Deborah Mixon">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>

                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Scott Jessie">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0">Progress<span class="float-right">78%</span></p>

                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 78%;" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="action">
                                            <a href="#" class="text-success mr-4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"> <i class="fa fa-pencil h5 m-0"></i></a>
                                            <a href="#" class="text-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Close"> <i class="fa fa fa-remove h5 m-0"></i></a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">3</th>
                                    <td>Multipurpose Landing Template</td>
                                    <td>06/6/2019</td>
                                    <td>
                                        <span class="text-success font-12"><i class="mdi mdi-checkbox-blank-circle mr-1"></i> Completed</span>
                                    </td>
                                    <td>
                                        <div class="team">
                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Neil Wing">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>

                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Stanley Barber">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar4.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>
                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Roger Drake">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar5.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>

                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Jack Krier">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar6.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0">Progress<span class="float-right">100%</span></p>

                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="action">
                                            <a href="#" class="text-success mr-4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"> <i class="fa fa-pencil h5 m-0"></i></a>
                                            <a href="#" class="text-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Close"> <i class="fa fa fa-remove h5 m-0"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">4</th>
                                    <td>Blog Template Design</td>
                                    <td>07/5/2019</td>
                                    <td>
                                        <span class="text-success font-12"><i class="mdi mdi-checkbox-blank-circle mr-1"></i> Completed</span>
                                    </td>
                                    <td>
                                        <div class="team">
                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Roger Drake">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>

                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Reggie James">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar8.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>

                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Gerald Mayberry">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0">Progress<span class="float-right">100%</span></p>

                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="action">
                                            <a href="#" class="text-success mr-4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"> <i class="fa fa-pencil h5 m-0"></i></a>
                                            <a href="#" class="text-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Close"> <i class="fa fa fa-remove h5 m-0"></i></a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">5</th>
                                    <td>Brand logo design</td>
                                    <td>08/6/2019</td>
                                    <td>
                                        <span class="text-primary font-12"><i class="mdi mdi-checkbox-blank-circle mr-1"></i> Pending</span>
                                    </td>
                                    <td>
                                        <div class="team">
                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Deborah Mixon">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>

                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Scott Jessie">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0">Progress<span class="float-right">54%</span></p>

                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 54%;" aria-valuenow="54" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="action">
                                            <a href="#" class="text-success mr-4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"> <i class="fa fa-pencil h5 m-0"></i></a>
                                            <a href="#" class="text-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Close"> <i class="fa fa fa-remove h5 m-0"></i></a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">6</th>
                                    <td>Redesign - Landing page</td>
                                    <td>10/6/2019</td>
                                    <td>
                                        <span class="text-primary font-12"><i class="mdi mdi-checkbox-blank-circle mr-1"></i> Pending</span>
                                    </td>
                                    <td>
                                        <div class="team">
                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Neil Wing">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar6.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>

                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Stanley Barber">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar5.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>
                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Roger Drake">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar4.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>

                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Jack Krier">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0">Progress<span class="float-right">41%</span></p>

                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 41%;" aria-valuenow="41" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="action">
                                            <a href="#" class="text-success mr-4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"> <i class="fa fa-pencil h5 m-0"></i></a>
                                            <a href="#" class="text-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Close"> <i class="fa fa fa-remove h5 m-0"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">7</th>
                                    <td>Redesign - Dashboard</td>
                                    <td>12/5/2019</td>
                                    <td>
                                        <span class="text-success font-12"><i class="mdi mdi-checkbox-blank-circle mr-1"></i> Completed</span>
                                    </td>
                                    <td>
                                        <div class="team">
                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Roger Drake">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar6.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>

                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Reggie James">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0">Progress<span class="float-right">100%</span></p>
                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="action">
                                            <a href="#" class="text-success mr-4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"> <i class="fa fa-pencil h5 m-0"></i></a>
                                            <a href="#" class="text-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Close"> <i class="fa fa fa-remove h5 m-0"></i></a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">8</th>
                                    <td>Landing page Design</td>
                                    <td>13/6/2019</td>
                                    <td>
                                        <span class="text-primary font-12"><i class="mdi mdi-checkbox-blank-circle mr-1"></i> Pending</span>
                                    </td>
                                    <td>
                                        <div class="team">
                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Deborah Mixon">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>

                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Scott Jessie">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0">Progress<span class="float-right">84%</span></p>

                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 84%;" aria-valuenow="84" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="action">
                                            <a href="#" class="text-success mr-4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"> <i class="fa fa-pencil h5 m-0"></i></a>
                                            <a href="#" class="text-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Close"> <i class="fa fa fa-remove h5 m-0"></i></a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">9</th>
                                    <td>Multipurpose Landing Template</td>
                                    <td>15/6/2019</td>
                                    <td>
                                        <span class="text-success font-12"><i class="mdi mdi-checkbox-blank-circle mr-1"></i> Completed</span>
                                    </td>
                                    <td>
                                        <div class="team">
                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Neil Wing">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar4.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>

                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Stanley Barber">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>
                                            <a href="javascript: void(0);" class="team-member" data-toggle="tooltip" data-placement="top" title="" data-original-title="Roger Drake">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar5.png" class="rounded-circle avatar-xs" alt="" />
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0">Progress<span class="float-right">100%</span></p>

                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="action">
                                            <a href="#" class="text-success mr-4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"> <i class="fa fa-pencil h5 m-0"></i></a>
                                            <a href="#" class="text-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Close"> <i class="fa fa fa-remove h5 m-0"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- end project-list -->

                    <div class="pt-3">
                        <ul class="pagination justify-content-end mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
<?php include("../repite/footer.php"); ?>
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
