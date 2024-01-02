<?php
$conn  = new mysqli('localhost', 'root', '', 'sct');
$query = "select distinct name, gender, age, email, phone, organization from user_data order by id";
$result = $conn->query($query) or die($conn->error . __LINE__);
$fetch_data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fetch_data[] = $row;
    }
}
$jResponse = json_encode($fetch_data);
echo $jResponse;
?>