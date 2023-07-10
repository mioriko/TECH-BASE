<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    
    <?php
   
    //新規投稿
    //データベース接続（詳細は4-1を参照）
    $dsn="mysql:dbname=******;host:localhost";
    $user="******";
    $pass="*******";
    $pdo=new PDO($dsn,$user,$pass,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
    
    //テーブル作成（詳細は4-2を参照）
    $sql="CREATE TABLE IF NOT EXISTS tbtest"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."comment TEXT,"
    ."password TEXT,"
    ."date TEXT"
    .");";
    $stmt=$pdo->query($sql);
    
   
    
    
    //投稿機能
    if(isset($_POST["submit"])){
     if(!empty($_POST["postname"] && $_POST["postcomment"] && $_POST["postpassword"])){
    $name=$_POST["postname"];
    $comment=$_POST["postcomment"];
    $password=$_POST["postpassword"];
    $date=date("Y-m-d H:i:s");
    
    //新規登録(詳細は4-5)
    if(empty($_POST["hiddennum"])){
    $sql=$pdo->prepare("INSERT INTO tbtest(name,comment,password,date) VALUES(:name,:comment,:password,:date)");
    $sql->bindParam(":name",$name,PDO::PARAM_STR);
    $sql->bindParam(":comment",$comment,PDO::PARAM_STR);
    $sql->bindParam(":password",$password,PDO::PARAM_STR);
    $sql->bindParam(":date",$date,PDO::PARAM_STR);
    $name=$_POST["postname"];
    $comment=$_POST["postcomment"];
    $password=$_POST["postpassword"];
    $date=date("Y-m-d H:i:s");
    $sql->execute();

     
    }else{
           
        $hiddennum=$_POST["hiddennum"];
        $sql="UPDATE tbtest SET name=:name, comment=:comment, password=:password,date=:date WHERE id=:id";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":name",$name,PDO::PARAM_STR);
        $stmt->bindParam(":comment",$comment,PDO::PARAM_STR);
        $stmt->bindParam(":password",$password,PDO::PARAM_STR);
        $stmt->bindParam(":date",$date,PDO::PARAM_STR);
        $stmt->bindParam(":id",$hiddennum,PDO::PARAM_INT);
        $stmt->execute();
    }
    }
    }
    
    
    
    
    //削除機能
    if(isset($_POST["delsubmit"])){
    if(!empty($_POST["delete"] && $_POST["delpass"])){
    $delete=$_POST["delete"];
    $delpass=$_POST["delpass"];
    
    //IDを取得
    $sql="SELECT * FROM tbtest where id=:id";
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(":id",$delete,PDO::PARAM_INT);
    $stmt->execute();
    $delnum=$stmt->fetch();
    
    //一致で削除
    if($delnum["password"]==$delpass){
        $sql="DELETE FROM tbtest WHERE id=:id ";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":id",$delete,PDO::PARAM_INT);
        $stmt->execute();
      
    }
    
    }
    }
    
  
   
    // 編集機能
if (isset($_POST["editsubmit"]) && !empty($_POST["edit"])&& !empty($_POST["editpass"])) {
    
        $edit = $_POST["edit"];
        $editpass = $_POST["editpass"];
        
        // IDを取得
        $sql = "SELECT * FROM tbtest WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $edit, PDO::PARAM_INT);
        $stmt->execute();
        $editnum = $stmt->fetch();


        if ($editnum["password"] === $editpass) {
            $editname = $editnum["name"];
            $editcomment = $editnum["comment"];
        }
        
       
        
    
}
?>
 <!--投稿欄-->
    <form action="" method="post">
        <input type="text" name="postname" placeholder="名前" value="<?php if(isset($editname)) {echo$editname;}?>">
        <input type="text" name="postcomment" placeholder="コメント" value="<?php if(isset($editcomment)) {echo$editcomment;}?>">
        <input type="password" name="postpassword" placeholder="パスワード">
        <input type="hidden" name="hiddennum" value="<?php if(isset($editname)) {echo$edit;}?>">
        <input type="submit" name="submit"><br>
     <!--削除欄-->
        <input type="text" name="delete" placeholder="削除番号">
        <input type="password" name="delpass" placeholder="パスワード">
        <input type="submit" name="delsubmit" value="削除"><br>
     <!--編集欄-->
        <input type="text" name="edit" placeholder="編集番号" value="<?php if(isset($editname)) {echo $edit;}?>">
        <input type="password" name="editpass" placeholder="パスワード">
        <input type="submit" name="editsubmit" value="編集"><br>
    </form>
   
   <?php
   
    //レコード表示（詳細は4-6）
    $sql="SELECT * FROM tbtest";
    $stmt=$pdo->query($sql);
    $results=$stmt->fetchAll();
    foreach($results as $row){
        echo $row["id"]." / ";
        echo $row["name"]." / ";
        echo $row["comment"]." / ";
        echo $row["date"]."<br>";
        echo "<hr>";
    }
    ?>
</body>
</html>