<?php
session_start();
$project_id = $_SESSION['count'];
//mysqlとの接続
$link = mysqli_connect('localhost', 'root', '');
if (!$link) {
    die('Failed connecting'.mysqli_error());
}
//print('<p>Successed connecting</p>');

//DBの選択
$db_selected = mysqli_select_db($link , 'test_db');
if (!$db_selected){
    die('Failed Selecting table'.mysql_error());
}
//文字列をutf8に設定
mysqli_set_charset($link , 'utf8');

//pdfテーブルの取得
$result_file  = mysqli_query($link ,"SELECT pdf_name FROM pdf_information_1 where project_id = '$project_id';");
if (!$result_file) {
    die('Failed query'.mysql_error());
}
//データ格納用配列の取得
$row_array_file = array();
$i = 0;
while ($row = mysqli_fetch_assoc ($result_file)) {
    $row_array_file[$i] = $row['pdf_name'];
    print_r($row_array_file[$i]);
    $i++;
}
$array_length = count($row_array_file);
$json_array = json_encode($row_array_file);
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>報告箇所登録画面</title>
    </head>
    <body>
    <h2>報告箇所を登録してください。</h2>
    <ul id="pdfName">
    </ul>
    <script type="text/javascript">
        //var names =[];
        var names = <?php echo $json_array; ?>;
        var length = <?php echo $array_length; ?>;
        var li = [];
        for (var i = 0; i < length; i++){
            li[i] = document.createElement('li');
            li[i].textContent = names[i];
            
            document.getElementById('pdfName').appendChild(li[i]);
        }
        document.getElementById("pdfName").children.onclick = function(){};
    </script>
    
    <script>
        function getPic(){

        }
    </script>
    </body>

</html>