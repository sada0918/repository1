 <?php
    //noticeエラー非表示
    error_reporting(E_ALL & ~E_NOTICE);
    // DB接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
     //データベース内にテーブルを作成（番号、名前、コメント、日付時刻、パスワード）
    $sql = "CREATE TABLE IF NOT EXISTS mission5"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date TEXT,"
    . "password TEXT"
    .");";
    $stmt = $pdo->query($sql);
    
    //入力フォームの送信内容を定義
    $pass=$_POST['pass'];
    $pass2=$_POST['pass2'];
    $pass3=$_POST['pass3'];
    $delete=$_POST['delete'];
    $edit=$_POST['edit'];
    $edit_num=$_POST['edit_num'];
    
   
    
    //条件分岐（新規投稿か編集か？中身のないフォームはないか？）
    //まずは新規投稿手順（変数定義含む）
    //データを入力（データレコードの挿入）
    if(!empty($_POST['name']&&$_POST['str'])&&!empty($pass)){if(empty($edit_num)){
    $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
    $name = $_POST['name'];
    $comment = $_POST['str'];
    $date=date("Y/m/d/ H:i:s");
    $password=$_POST['pass'];
    $sql -> execute();
    }
    //次に編集投稿手順
    elseif(!empty($edit_num)){
    $id = $edit_num; //変更する投稿番号
    $name = $_POST['name'];
    $comment = $_POST['str'];
    $password=$_POST['pass'];
    $sql = 'UPDATE mission5 SET name=:name,comment=:comment,password=:password WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    }
    }
    
    
    
    
    //リセットして条件分岐(削除依頼かどうか、パスワードは正しいか)
    if(!empty($delete)&&!empty($pass2)){
        $id=$delete;
    //IDのレコードを読み込む（selectする）
    $sql = 'SELECT * FROM mission5 WHERE id=:id ';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                             // ←SQLを実行する。
    $result = $stmt->fetch();
    //パスワードが正しければ以下の動作を実行する
    if($result['password']==$pass2){
    $sql = 'delete from mission5 where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    }
    }
    
    
    //リセットして条件分岐（編集依頼かどうか、パスワードはあっているか）
    //入力されているデータレコードの内容を編集
    if(!empty($edit)&&!empty($pass3)){
        $id=$edit;
        //IDのレコードを読み込む（selectする）
    $sql = 'SELECT * FROM mission5 WHERE id=:id ';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                             // ←SQLを実行する。
    $result = $stmt->fetch();
    //パスワードが正しければ以下の動作を実行する（投稿内容表示）
    if($result['password']==$pass3){
          $name_edit=$result['name'];
          $str_edit=$result['comment'];
      }else{
          $name_edit="";
          $str_edit="";
          $edit="";
          $pass3="";
    }
    }
    
    
    
    ?>
    
    
    <html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    趣味<br>
    <form action="" method="post">
 <投稿><br><input type="text" name="name" placeholder="お名前"value="<?php if(!empty($_POST["edit"])&&!empty($_POST['pass3'])){ echo $name_edit ;}?>">
  <input type="text" name="str" placeholder="コメント"value="<?php if(!empty($_POST["edit"])&&!empty($_POST['pass3'])){ echo $str_edit ;} ?>">
 <input type="hidden" name="edit_num" value="<?php if (!empty($_POST["edit"])&&!empty($_POST['pass3'])){echo $edit;} ?>"> 
 <input type="text" name = "pass" placeholder="パスワード"value="<?php if(!empty($_POST["edit"])&&!empty($_POST['pass3'])){ echo $pass3 ;}?>">
 <input type="submit" />
 </form>
 <form action=""method="post">
 <削除><br><input type="number" name = "delete" placeholder="削除対象番号">
 <input type="text" name = "pass2" placeholder="パスワード">
 <input type="submit" name="submit" value = "削除">
</form>
<form action=""method="post">
 <編集><br><input type="number" name = "edit" placeholder="編集対象番号">
       <input type="text" name = "pass3" placeholder="パスワード">
        <button type="submit">編集</button>
 
</form><br>
 
</body> 



<?php

//入力したデータレコードを抽出し、表示する
    $sql = 'SELECT * FROM mission5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id']."\r";
        echo $row['name']."\r";
        echo $row['comment']."\r";
        echo $row['date'];
    echo "<hr>";
    }

?>
