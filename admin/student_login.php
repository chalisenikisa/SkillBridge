<?php
session_start();
$conn = mysqli_connect("localhost", "username", "password", "database");

$reg_no = $_POST['reg_no'];
$password = $_POST['password'];

$query = "SELECT * FROM login WHERE reg_no='$reg_no' AND password='$password'";
$result = mysqli_query($conn, $query);
