<?php
//セッションスタート
session_start();

require "conn.php";
$sql = "INSERT INTO projects_information_1 VALUES ('', 'projects_name', 'address', 'overview', '' );";
$result  = mysqli_query($conn, $sql);


//if(!isset($_SESSION['count'])){
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
    $result_file  = mysqli_query($link ,"SELECT projects_id FROM projects_information_1 ORDER BY projects_id DESC LIMIT 1;");
    if (!$result_file) {
        die('Failed query'.mysql_error());
    }
    $row = mysqli_fetch_assoc($result_file);
    $row_num = (int) $row['projects_id'];
    $_SESSION['count'] = $row_num;
    print_r($_SESSION['count']);
    // MySQLに対する処理
    $close_flag = mysqli_close($link);

    //}else{
    //    print_r($_SESSION['count']);
    //}

?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>現場登録画面</title>
        <link rel="stylesheet" href = "style.css">
    </head>
    <body>

    <!--<h1>現場管理アプリ</h1>-->
    <h2>現場情報を入力してください。</h2>

        <form action="p_entry.php" method = "post" enctype="multipart/form-data">
            
            現場名<input type = "text" id = "project_name" name = "project_name" value = ""><br />
            所在地<input type ="text" id = "address" name="address" value = ""><br />
            概要<input type = "text" id = "overview" name="overview" value = ""><br />
        </form>

        <h2>図面情報を登録してください。</h2>
            
        <form id="my_form">
			<label for="image_file">ファイルを選択してください</label><br>
            <input type="file" name="image_file[]" id="image_file" multiple="multiple" onchange=checkFile()><br>

            <table id = "pdf_information">
                <tr>
                    <th style="WIDTH: 15px" id="no">No</th>
                    <th style="WIDTH: 150px" id=>ファイル名</th>
                    <th style="WIDTH: 30px"></th>
                </tr>
            </table>
            
            <!--<input type="submit" name ="next" value = "次へ">-->
        </form>
        <button type="button" name="next" value = "次へ" onclick="setPDF()">次へ</button>
        <script>

        function checkFile(){
            //選択ファイルのアップロード
            // フォームデータを取得
            var formdata = new FormData(document.getElementById("my_form"));

            // XMLHttpRequestによるアップロード処理
            var xhttpreq = new XMLHttpRequest();
            xhttpreq.onreadystatechange = function() {
                if (xhttpreq.readyState == 4 && xhttpreq.status == 200) {
                    alert(xhttpreq.responseText);
                }
            };
            xhttpreq.open("POST", "upload_file.php", true);
            xhttpreq.send(formdata);

            var p=[];
            var n=[];
            var f=[];
            var file_name = document.getElementById("image_file");
            var list ="";
            for(var i=0; i<file_name.files.length; i++){
                p.push(file_name.files[i].path);
                n.push(file_name.files[i].name);
                f.push(file_name.files[i]);
            }
            //テーブル取得
            var table = document.getElementById("pdf_information");
            //var num = file.length;
            var num = p.length;
            var cell1 = [];
            var cell2 = [];
            var cell3 = [];
            for(var i = 0; i < num; i++){
                //行を行末に追加
            var row = table.insertRow(-1);
            //セルの挿入
            cell1.push(row.insertCell(-1));
            cell2.push(row.insertCell(-1));
            cell3.push(row.insertCell(-1));
            
            //削除ボタン
            var button = '<input type = "button" value = "削除" onClick= "deleteRow(this)" />';
            //行数取得
            var row_len = table.rows.length;
            //セルの内容入力
            cell1[i].innerHTML = row_len -1;
            cell2[i].innerHTML = n[i];
            cell3[i].innerHTML = button;
        }
    }

    function deleteRow(obj) {
        // 削除ボタンを押下された行を取得
        tr = obj.parentNode.parentNode;
        file_name = tr.cells[1].innerHTML;
        console.log(tr.cells[1].innerHTML);
        //tr.cells[1]
        // フォームデータを取得
        //var formdata = new FormData(document.getElementById("my_form"));
        var formdata = new FormData();
        formdata.append('deleteFile',file_name);
        // XMLHttpRequestによるアップロード処理
        var xhttpreq = new XMLHttpRequest();
        xhttpreq.onreadystatechange = function() {
            if (xhttpreq.readyState == 4 && xhttpreq.status == 200) {
                alert(xhttpreq.responseText);
            }
        };
        xhttpreq.open("POST", "delete.php", true);
        xhttpreq.send(formdata);
            
            // trのインデックスを取得して行を削除する
            tr.parentNode.deleteRow(tr.sectionRowIndex);
            var tableTarget = document.getElementById('pdf_information');
            for(var i = 1; i < tableTarget.rows.length; i++){
                tableTarget.rows[i].cells[0].innerHTML = i;
            }
        }

        function setPDF(){
            //テーブル取得
            var table = document.getElementById("pdf_information");
            var pdf_name;
            var fd;
            var xhttpreq;
            for(var i = 1; i<table.rows.length; i++){
            pdf_name = table.rows[i].cells[1].innerHTML;
            // フォームデータを取得
            fd = new FormData();
            fd.append('pdf',pdf_name);
            // XMLHttpRequestによるアップロード処理
            xhttpreq = new XMLHttpRequest();
            xhttpreq.onreadystatechange = function() {
                if (xhttpreq.readyState == 4 && xhttpreq.status == 200) {
                    alert(xhttpreq.responseText);
                }
            };
            xhttpreq.open("POST", "insert_pdf_information.php", true);
            xhttpreq.send(fd);
            }


            fd = new FormData();
            var project_name = document.getElementById("project_name").value;
            var address = document.getElementById("address").value;
            var overview = document.getElementById("overview").value;
            fd.append('project_name',project_name);
            fd.append('address',address);
            fd.append('overview',overview);
            xhttpreq = new XMLHttpRequest();
            xhttpreq.onreadystatechange = function() {
                if (xhttpreq.readyState == 4 && xhttpreq.status == 200) {
                    alert(xhttpreq.responseText);
                }
            };
            xhttpreq.open("POST", "upload_project_info.php", true);
            xhttpreq.send(fd);
            window.location.href = 'p_entry_report_place.php';
            }

            
        </script>
    </body>
</html>