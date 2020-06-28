<?php
include("db.php");
include("get_client_ip.php");
$cid = $_POST["cid"];
$uid = $_POST["uid"];
$date = $_POST["date"];
$TEXT = $_POST["TEXT"];

$stmt = $con->prepare("SELECT TEXT, date from comments where cid=? and uid=?");

$stmt->bind_param("ss", $cid, $uid);
$stmt->execute();

$result = $stmt->get_result();
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        $TEXT = $row["TEXT"];
        $date = $row["date"];
}


$output["TEXT"] = $TEXT;
$output["result"] = true;

}else{

$output["result"] = false;
}


echo json_encode($output)
$con->close();


?>
