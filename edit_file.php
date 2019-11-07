<?php
$dsn="mysql:host=localhost;charset=utf8;dbname=upload";
$pdo=new PDO($dsn,'root','1114');
if(!empty($_FILES) && $_FILES['file']['error']==0){
    //傳出的檔案會先在tmp資料夾裡面 再叫出將檔案做管理
    //將資料庫會改變的欄位設為變數 
    $type=$_FILES['file']['type'];
    $filename=$_FILES['file']['name'];
    $path="./upload/";
    $updateTime=date("Y-m-d H:i:s");//資料顧更新時間要自己寫變數更改
    $id=$_POST['id'];// 注意更新資料時id有無$_POST傳出
    move_uploaded_file($_FILES['file']['tmp_name'] , $path . $filename);//將檔案移至指定資料夾目錄

    
    //刪除硬碟裡面的原本的檔案
    $sql="select * from files where id='$id'";
    $origin=$pdo->query($sql)->fetch();
    $origin_file=$origin['path'];
    //刪除語法
    unlink($origin_file);
    //在更新資料庫裡的資料
    $sql="update files set name='$filename',type='$type',update_time='$updateTime',path='" . $path . $filename . "' where id='$id'";
    $result=$pdo->exec($sql);
    if($result==1){
        echo "更新成功";
        header("location:manage.php");
    }else{
        echo "DB有誤";
    }
}
$id=$_GET['id'];
$sql="select * from files where id='$id'";
$data=$pdo->query($sql)->fetch();
?>
<form action="edit_file.php" method="post" enctype="multipart/form-data">
<table>
    <tr>
        <td colspan="2">
            <img src="<?=$data['path'];?>" style="width:200px;height:200px">
        </td>

    </tr>
    <tr>
        <td>name</td>
        <td><?=$data['name'];?></td>
    </tr>
    <tr>
        <td>path</td>
        <td><?=$data['path'];?></td>
    </tr>
    <tr>
        <td>type</td>
        <td><?=$data['type'];?></td>
    </tr>
    <tr>
        <td>create_time</td>
        <td><?=$data['create_time'];?></td>
    </tr>
</table>
更新檔案:<input type="file" name="file"><br>
<input type="hidden" name="id" value="<?=$data['id'];?>">
<input type="submit" value="更新">
</form>