<?php
    // DB接続
    $dsn = 'mysql:dbname=データベース名;host=localhost';
    $user = 'ユーザ名';
    $password = "パスワード";
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //CREATE文：データベース'Board'内にテーブル'comment'を作成
    $sql = "CREATE TABLE IF NOT EXISTS comment"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "username varchar(32),"
    . "str TEXT,"
    . "newpass TEXT,"
    . "date TEXT"
    .");";
    $stmt = $pdo->query($sql); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Board</title>
  <link rel="stylesheet" href="m5-1.css">
</head>
<body>
    <h1>掲示板</h1>
    <div class="boardWrapper">
        <div class="factor">
        <div class="area">
            <!-- タブの見出し -->
            <input type="radio" name="tab_name" id="tab1" checked>
            <label class="tab_class" for="tab1">投稿</label>

            <!-- タブの内容 -->
            <div class="content_class">
                <form method="post">
                    <h3>投稿フォーム</h3>
                    <dl>
                        <dt><span class="required">名前</span></dt>
                        <dd><input type="text" name="username" autocomplete="off" required value="<?php 
                        //編集ホームから編集番号とパスワードが送られてくると
            if(!empty($_POST["presubmit"])){
            //変数定義
                $editnum = $_POST["editnum"];
                $prepass = $_POST["prepass"];
                
            //該当番号のデータの取り出し準備
            //SELECT文
                $sql = 'SELECT * FROM comment WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $editnum, PDO::PARAM_INT);
                $stmt->execute();                             
                $results = $stmt->fetchAll();
            
                //ユーザー名をブラウザに表示
                if($results[0]['newpass'] == $prepass){
                    echo $results[0]["username"];
                }
                        }?>"></dd>

                        <dt><span class="required">パスワード</span></dt>
                        <dd><input type="text" name="newpass" autocomplete="off" required><br></dd>

                        <dt><span class="required">コメント</span></dt>
                        <dd><input type="text" name="str" autocomplete="off" required value="<?php
            //編集ホームから編集番号とパスワードが送られてくると
            if(!empty($_POST["presubmit"])){
                //変数定義
                    $editnum = $_POST["editnum"];
                    $prepass = $_POST["prepass"];
                    
                //該当番号のデータの取り出し準備
                //SELECT文
                    $sql = 'SELECT * FROM comment WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $editnum, PDO::PARAM_INT);
                    $stmt->execute();                             
                    $results = $stmt->fetchAll();
                
                    //コメントをブラウザに表示
                    if($results[0]['newpass'] == $prepass){
                        echo $results[0]["str"];
                    }
                        }?>"></textarea></dd>

                        <!-- 編集マーク -->
                        <dd><input type="hidden" name="editmark" value="<?php
            //編集ホームから編集番号とパスワードが送られてくると
            if(!empty($_POST["presubmit"])){
                //変数定義
                    $editnum = $_POST["editnum"];
                    $prepass = $_POST["prepass"];
                    
                //該当番号のデータの取り出し準備
                //SELECT文
                    $sql = 'SELECT * FROM comment WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $editnum, PDO::PARAM_INT);
                    $stmt->execute();                             
                    $results = $stmt->fetchAll();
                
                    //コメントをブラウザに表示
                    if($results[0]['newpass'] == $prepass){
                        echo $results[0]["id"];
                    }
                        }?>"></dd>
                    </dl>
                    <input type="submit" name="submit">
                </form>
            </div>

            <input type="radio" name="tab_name" id="tab2" >
            <label class="tab_class" for="tab2">削除</label>

            <div class="content_class">
                <form method="post">
                <h3>削除要求</h3>
                    <dl>
                        <dt><span class="required">削除番号</span></dt>
                        <dd><input type="number" name="delnum" autocomplete="off" required></dd>
                        <dt><span class="required">パスワード</span></dt>
                        <dd><input type="text" name="delpass" autocomplete="off" required></dd>
                    </dl>
                    <input type="submit" name="delsubmit" value="削除">
                </form>
            </div>

            <input type="radio" name="tab_name" id="tab3" >
            <label class="tab_class" for="tab3">編集</label>
            <div class="content_class">
                <form method="post">
                <h3>編集要求</h3>
                    <dl>
                        <dt><span class="required">編集番号</span></dt>
                        <dd><input type="number" name="editnum" autocomplete="off" required></dd>
                        <dt><span class="required">パスワード</span></dt>
                        <dd><input type="text" name="prepass" autocomplete="off" required></dd>
                    </dl>
                    <input type="submit" name="presubmit" value="編集">  
                </form>
            </div>
    </div>
        
    
<?php
//新規投稿

    //送信すると
    if(!empty($_POST["submit"])){
   
        //マークなしですべて受信したとき、    
        if(empty($_POST["editmark"]) && !empty($_POST["username"]) && !empty($_POST["str"]) && !empty($_POST["newpass"])){
            
            //INSERT文：データを入力
            $username = $_POST["username"];
            $str = $_POST["str"];
            $newpass = $_POST["newpass"];
            $date = date("Y/m/d H:i:s");
        
            $sql = "INSERT INTO comment (username, str, newpass, date) VALUES (:username, :str, :newpass, :date)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':str', $str, PDO::PARAM_STR);
            $stmt->bindParam(':newpass', $newpass, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->execute();
            
            echo "<span style=color:green;>投稿を受け付けました</span>"."<br>";
        }
    }
    
//削除処理

    //投稿番号とパスワードを送信する
    if(!empty($_POST["delsubmit"])){
        
        //番号とパスの両方を受信したとき、
        if(!empty($_POST["delnum"]) && !empty($_POST["delpass"])){
            //変数定義
            $delpass = $_POST["delpass"];
            $delnum = $_POST["delnum"];
        
            //保存データの取り出し準備
            //SELECT文
            $sql = 'SELECT * FROM comment where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $delnum, PDO::PARAM_INT);                  
            $stmt->execute();                             
            $results = $stmt->fetchAll();
            $count=count($results);

            //一意な数字があるか
            if($count==0){
                echo "<span style=color:red;>該当するコメントが見つかりません</span>"."<br>";

            }else{
                if($results[0]["newpass"] == $delpass){
                    $sql = 'delete from comment where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $delnum, PDO::PARAM_INT);
                    $stmt->execute();

                    echo "<span style=color:green;>削除しました</span>"."<br>";
                }else{
                    echo "<span style=color:red;>パスワードが一致しません</span>"."<br>";
                }
            }               
        }
    }
    
    
//編集
    //投稿番号とパスワードを送信すると
    if(!empty($_POST["presubmit"])){

        //番号とパスの両方を受信したとき、
        if(!empty($_POST["editnum"]) && !empty($_POST["prepass"])){
            //変数定義
            $editnum = $_POST["editnum"];
            $prepass = $_POST["prepass"];
        
            //保存データの取り出し準備
            //SELECT文
            $sql = 'SELECT * FROM comment where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $editnum, PDO::PARAM_INT);                  
            $stmt->execute();                             
            $results = $stmt->fetchAll();
            //件数を調べる
            $count=count($results);

            //idは一意であるから、0or1で条件分岐
            if($count == 0){
                echo "<span style=color:red;>該当するコメントが見つかりません</span>"."<br>";
            }else{
                //パスワードが一致すれば
                if($results[0]["newpass"] == $prepass){
                    echo "<span style=color:green;>投稿フォームから編集を行ってください</span>"."<br>";
                }else{
                    echo "<span style=color:red;>パスワードが一致しません</span>"."<br>";
                }
            }               
        }
    }

//編集
    //編集マーク付きで、名前、コメント、パスワードを受信したとき、
                    
    if(!empty($_POST["submit"]) && !empty($_POST["editmark"]) && !empty($_POST["username"]) && !empty($_POST["str"]) && !empty($_POST["newpass"])){
    //変数定義
        $id = $_POST["editmark"]; 
        $username = $_POST["username"];
        $str = $_POST["str"];
        $newpass = $_POST["newpass"];
        $date = date("Y/m/d H:i:s");

    // 保存データの取り出し準備                   
        $sql = 'SELECT * FROM comment where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();                             
        $results = $stmt->fetchAll();
         
    //パスワードが一致すれば編集(update文)実行
        if($results[0]["newpass"] == $newpass){
            //UPDATE文：入力されているデータレコードの内容を編集
            $sql = 'UPDATE comment SET username=:username,str=:str,newpass=:newpass,date=:date WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':str', $str, PDO::PARAM_STR);
            $stmt->bindParam(':newpass', $newpass, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo "<span style=color:green;>編集に成功しました</span>"."<br>";
        }else{
            echo "<span style=color:red;>パスワードが無効です。編集要求からやり直してください。</span>"."<br>";
        }
    //パスワードが空欄のまま送信すると        
    }
?>
</div>
<hr>
    <div class="factor">
        <div class="box_scroll">
        
<?php
    //SELECT文：入力したデータレコードを抽出し、表示する
        $sql = 'SELECT * FROM comment';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].' ';
            echo $row['username'].' ';
            echo $row['str'].' ';
            echo $row['date'].'<br>';
        }
?>
        </div>  
    </div>
        

</body>
</html>