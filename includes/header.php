<?php
session_start(); // Ensure session is started
include("includes/config.php");
error_reporting(0);
?>

<?php if (!empty($_SESSION['login'])): ?>
<header>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <strong>Welcome: </strong><?php echo htmlentities($_SESSION['sname']); ?>
                &nbsp;&nbsp;

                <strong>Last Login: 
                    <?php 
                    $ret = mysqli_query($con, "SELECT * FROM userlog WHERE studentRegno='" . $_SESSION['login'] . "' ORDER BY id DESC LIMIT 1,1");
                    $row = mysqli_fetch_array($ret);
                    if ($row) {
                        echo htmlentities($row['userip']) . " at " . htmlentities($row['loginTime']);
                    } else {
                        echo "N/A";
                    }
                    ?>
                </strong>
            </div>
        </div>
    </div>
</header>
<?php endif; ?>

<!-- HEADER END -->
<div class="navbar navbar-inverse set-radius-zero">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#" style="color:#fff; font-size:24px; line-height:24px;">
                Online Course Registration
            </a>
        </div>

        <div class="left-div">
            <i class="fa fa-user-plus login-icon"></i>
        </div>
    </div>
</div>
