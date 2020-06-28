<?php
include("db.php");
include("get_client_ip.php");
$mobile = $_POST["mobile"];
$password = $_POST["password"];

$mobile = filter_var($mobile, FILTER_SANITIZE_NUMBER_INT);

$stmt = $con->prepare("SELECT ID, Name from login where Mobile=? and Password=?");

$stmt->bind_param("ss", $mobile, $password);
$stmt->execute();

$result = $stmt->get_result();
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        $name = $row["Name"];
        $id = $row["ID"];
        //Generate and update token
        $access_token = rand(1111111111, 99999999999);
        $sqli = "UPDATE login SET Access_Token=? where ID = ?";
        $stmt1 = $con->prepare($sqli);
        $stmt1->bind_param("si", $access_token, $id);
        $stmt1->execute();
    
}
$output["Name"] = $name;
$output["result"] = true;
$output["message"] = "Login success";
$output["token"] = $access_token;
}else{
$output["result"] = false;
$output["message"] = "Login failed";
}

echo json_encode($output);
$con->close();

?>

