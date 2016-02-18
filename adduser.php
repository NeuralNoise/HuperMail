<!DOCTYPE html>

<html>
<head>

    <!-- Website Title & Description for Search Engine purposes -->
    <title>SignUp - HuperMail</title>
    <meta name="description" content="">

    <!-- Mobile viewport optimized -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="includes/css/bootstrap-glyphicons.css" rel="stylesheet">


    <!-- Custom CSS -->
    <link href="style.css" rel="stylesheet">

    <!-- Include Modernizr in the head, before any other Javascript -->
    <script src="includes/js/modernizr-2.6.2.min.js"></script>

</head>
<body>

<div class="container">

    <div class="row" id="row">
        <div class="col-sm-6" id="loginForm">
            <div class="card" id="form-card">

                <div class="card-header bg-primary">Sign up with HuperMail</div>

                <form class="container" id="container-login">

                    <fieldset class="form-group">
                        <div class="labeldiv">
                            <small class="text-muted sr-only" id="labelid">Your ID</small>
                        </div>
                        <input type="text" class="form-control" id="inputid" placeholder="Enter your id number" onkeyup="showLabel('labelid',this.value);">
                        <small class="text-muted">Your data is in the safe hands.</small>
                    </fieldset>

                    <fieldset class="form-group">
                        <div class="labeldiv">
                            <small class="text-muted sr-only" id="labelemail">Username</small>
                        </div>
                        <input type="text" class="form-control" id="inputEmail" placeholder="Enter a user name" onkeyup="showLabel('labelemail',this.value);">
                    </fieldset>

                    <div class="row">
                        <div class="col-sm-4">
                            <fieldset class="form-group">
                                <div class="labeldiv">
                                    <small class="text-muted sr-only" id="labelFirstName">First Name</small>
                                </div>
                                <input type="text" class="form-control" id="inputFirstName" placeholder="First name" onkeyup="showLabel('labelFirstName',this.value);">
                            </fieldset>
                        </div>
                        <div class="col-sm-4">
                            <fieldset class="form-group">
                                <div class="labeldiv">
                                    <small class="text-muted sr-only" id="labelMiddleName">Middle Name</small>
                                </div>
                                <input type="text" class="form-control" id="inputMiddleName" placeholder="Middle name" onkeyup="showLabel('labelMiddleName',this.value);">
                            </fieldset>
                        </div>
                        <div class="col-sm-4">
                            <fieldset class="form-group">
                                <div class="labeldiv">
                                    <small class="text-muted sr-only" id="labelLastName">Last Name</small>
                                </div>
                                <input type="text" class="form-control" id="inputLastName" placeholder="Last name" onkeyup="showLabel('labelLastName',this.value);">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            <fieldset class="form-group">
                                <div class="labeldiv">
                                    <small class="text-muted sr-only" id="labelPassword">Password</small>
                                </div>
                                <input type="password" class="form-control" id="inputPassword" placeholder="Choose a password" onkeyup="showLabel('labelPassword',this.value);">
                            </fieldset>
                        </div>
                        <div class="col-sm-4">

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            <fieldset class="form-group">
                                <div class="labeldiv">
                                    <small class="text-muted sr-only" id="labelConfirmPassword">Confirm Password</small>
                                </div>
                                <input type="password" class="form-control" id="inputConfirmPassword" placeholder="Confirm password" onkeyup="showLabel('labelConfirmPassword',this.value);">
                            </fieldset>
                        </div>
                        <div class="col-sm-4">

                        </div>
                    </div>

                    <fieldset class="form-group">
                        <div class="labeldiv">
                            <small class="text-muted sr-only" id="labelMobile">Mobile Number</small>
                        </div>
                        <input type="text" class="form-control" id="inputMobile" placeholder="Enter your Mobile number" onkeyup="showLabel('labelMobile',this.value);">
                    </fieldset>


                    <button class="btn btn-primary" type="submit" id="submitButton">Submit</button>


                </form>
            </div>
        </div>


        <div class="col-sm-6">

            <div id="info">
                <svg height="200" id="svg">
                    <circle r="30" cx="40" cy="150" style="stroke: black; fill: none"/>
                    <g id="arrow">
                        <line x1="12" y1="150" x2="60" y2="130" style="stroke: black;"/>
                        <line x1="12" y1="150" x2="60" y2="170" style="stroke: black;"/>
                    </g>
                </svg>
            </div>

        </div>

    </div>

</div>

<!-- All Javascript at the bottom of the page for faster page loading -->

<!-- First try for the online version of jQuery-->
<!--<link rel="stylesheet" href="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/css/bootstrap.css">-->

<!-- If no online access, fallback to our hardcoded version of jQuery -->
<script>window.jQuery || document.write('<script src="includes/js/jquery.min.js"><\/script>')</script>

<!-- Bootstrap JS -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/js/bootstrap.js"></script>-->
<script src="bootstrap/js/bootstrap.js"></script>
<!-- Custom JS -->
<script src="jsc.js"></script>
</body>
</html>


<?php 
    if (isset($_POST['login'])) { 
        $login=$_POST['login']; 
        $name=$_POST['name']; 
        $passwd=$_POST['pwd'];
        echo "<br><br>";
        $result=shell_exec("bash /srv/www/hupermail/src/newuser.sh $login $passwd");
    header('Location: success.php');    
    }
?>