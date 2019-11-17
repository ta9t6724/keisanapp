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
  $dsn = 'mysql:dbname=FM01;host=localhost';
  $user = 'root';
  $password='';
  $dbh = new PDO($dsn, $user, $password);
  $dbh->query('SET NAMES utf8');
?>


<?php
// 答えが3桁の割られる数
$num1 = [6060,2144,3682,5488,6492,6920,6888,5984,1900,4340,4224,5724,6090,4640,6501,4819,2915,2310,3550,7315,6321,5907,5576,2040,3626,1936,1116,4935,7506,8190,8064,4617,8342,2280,2310,4484,5920,3575,8074,4303,7518,5850,6860,1470,1755,2750,9928,4120,8700,3640,6356,4284,1653,4914];
// 答えが3桁の割る数
$num2 = [15,16,14,28,12,20,84,88,76,35,16,27,15,10,33,61,55,30,10,35,43,33,41,12,37,88,18,35,27,35,21,19,43,60,66,59,16,11,22,13,42,50,70,70,45,22,68,20,50,35,14,68,19,91];
// 答えが3桁の答え
$answer = [404,134,263,196,541,346,82,68,25,124,264,212,406,464,197,79,53,77,355,209,147,179,136,170,98,22,62,141,278,234,384,243,194,38,35,76,370,325,367,331,179,117,98,21,39,125,146,206,174,104,454,63,87,54];

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
  <p>これで問題は終了です。<br>
    手を挙げて試験官に終了を知らせてください。</p>
  <?php } ?>

</body>