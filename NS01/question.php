<!-- view2
⓪ページ番号を元に問題を表示
空チェック
$_GET['pages']と$_GET['time']はある
$_GET['lap']と$_GET['answer']はないので空ならINSERTしないというコード

送信ボタンを押したら
①ページ番号+1を自分のページに送信
②ボタンを押した時間2をデータベースにインサート
③答えをデータベースにインサート
④答えと正答の比較結果をデータベースにインサート
⑤ボタンを押した時間2-ボタンを押した時間1をデータベースにインサート

最後のページにかかった時間を出す？

データベースには回答、結果（正誤）、ボタンを押した時間、時間差を入れる

ページの更新をして、⓪に戻る -->

<!-- データベースへの接続 -->
<?php
  $dsn = 'mysql:dbname=NS01;host=localhost';
  $user = 'root';
  $password='';
  $dbh = new PDO($dsn, $user, $password);
  $dbh->query('SET NAMES utf8');
?>


<?php
// 答えが3桁の割られる数
$num1 = [6137,6708,4340,9724,5355,8220,5159,1704,4060,6562,6110,6580,3074,9708,8512,2331,7350,6474,5175,7194,2352,6760,3375,7047,1491,7280,1008,3540,6025,2760,7880,4032,9823,4950,4180,1276,7392,9000,6215,8892,7392,7059,1800,4209,4884,6688,5248,2702,6345,4972,5892,2294,4860,8648];
// 答えが3桁の割る数
$num2 = [17,12,28,17,45,15,77,24,58,17,10,20,29,12,14,37,75,83,23,33,21,26,27,27,21,80,63,15,25,23,40,36,19,90,44,29,16,72,55,12,21,13,72,69,66,38,16,14,45,11,12,62,81,94];
// 答えが3桁の答え
$answer = [361,559,155,572,119,548,67,71,70,386,611,329,106,809,608,63,98,78,225,218,112,260,125,261,71,91,16,236,241,120,197,112,517,55,95,44,462,125,113,741,352,543,25,61,74,176,328,193,141,452,491,37,60,92];

$pagenum = $_POST['pagenum'];
$qnum = $pagenum + 1;
// $resultを出すときは$pagenumが次の数字になっているため$answerの中身は$pagenum-1にする必要がある
$resultcheck = $pagenum - 1;
// 正解不正解の確認と$resultへの格納
if (!empty($_POST['answer'])) {
    $correctanswer = $answer[$resultcheck];
    if ($_POST['answer'] == $correctanswer) {
        $result = "正解";
    } else {
        $result = "不正解";
    }
}
?>

<?php
// 問題開始時間をINSERTする
if (!empty($_POST['time'])) {
    $time_sql = "INSERT INTO `pushtime` SET `pushtime`=?";
    $time_data = array($_POST['time']);
    $time_stmt = $dbh->prepare($time_sql);
    $time_stmt->execute($time_data);
}
?>

<?php
// resultsテーブルへのINSERT
$difftime = $_POST['time'];
    if (!empty($_POST['answer'])) {
        $result_sql = "INSERT INTO `results` SET `answer`=?, `result`=?, `starttime`=?";
        $result_data = array($_POST['answer'], $result, $_POST['time']);
        $result_stmt = $dbh->prepare($result_sql);
        $result_stmt->execute($result_data);
    }
?>



<body>
  <?php if ($pagenum <= 53) { ?>
  <p><?php echo "対象配列は".$_POST['pagenum']."で"."現在の時刻は".$_POST['time']; ?>
  </p>
  <form method="POST"
    action="<?php $_SERVER['PHP_SELF'] ?>">
    <p><?php echo "第".$qnum."問<br>".$num1[$pagenum]."÷".$num2[$pagenum]."=";?>
      <input type="number" name="answer" maxlength="3" />
      <input type="hidden" name="pagenum"
        value="<?php echo $pagenum+1; ?>">
      <input type="hidden" name="time"
        value="<?php echo date('Y/m/d H:i:s'); ?>">
      <input type="submit" value="次の問題へ"></p>
  </form>
  <?php } elseif ($pagenum == 54) { ?>
  <p>これで問題は終了です<br>
    手を挙げて試験官に終了を知らせてください。</p>
  <?php } ?>

</body>