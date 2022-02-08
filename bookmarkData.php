<?php
if (isset($_POST['url']) && isset($_POST['linkName'])) {
  $url = filter_var($_POST['url'], FILTER_VALIDATE_URL);
  $urlName = mb_strlen(trim($_POST['linkName']));

  if (isset($url) && isset($urlName) && !is_null($url) && !is_null($urlName)) {
    $mark = $mysqli->prepare('INSERT INTO bookmark(link,user_id,link_name) VALUES(?,?,?)');
    $mark->bind_param('sis', $_POST['url'], $_SESSION['id'], $_POST['linkName']);
    $mark->execute();
    $mark->close();
    $url = htmlentities($_POST['url']);
    $linkName = htmlentities($_POST['linkName']);
    $id = $mysqli->insert_id;
    $data = array('url' => $url, 'linkName' => $linkName, 'id' => $id);
    header("Content-type: application/json; charset=UTF-8");
    echo json_encode($data);
    exit;
  }
}

if (isset($_POST['delId'])) {
  $delete = $mysqli->prepare('DELETE bookmark,book_memo FROM bookmark LEFT JOIN book_memo ON bookmark.id = book_memo.book_id WHERE bookmark.id = ?');
  $delete->bind_param('i', $_POST['delId']);
  $delete->execute();
  $delete->close();
  $delId = $_POST['delId'];
  $data = array('delId' => $delId);
  header("Content-type:application/json;charset=UTF-8");
  echo json_encode($data);
  exit;
}

if (isset($_POST['memoId']) && isset($_POST['dragId'])) {
  $memoId = $_POST['memoId'];
  $dragId = $_POST['dragId'];
  $mbDrag = $mysqli->prepare('INSERT INTO book_memo(memo_id,book_id) VALUES(?,?)');
  $mbDrag->bind_param('ii', $memoId, $dragId);
  $mbDrag->execute();
  $mbDrag->close();
  $data = array('memoId' => $memoId, 'dragId' => $dragId);
  header("Content-type:application/json;charset=UTF-8");
  echo json_encode($data);
  exit;
}

if (isset($_POST['bookName']) && isset($_POST['bookLink'])) {
  $bookName = mb_strlen(trim($_POST['bookName']));
  $bookLink = filter_var($_POST['bookLink'], FILTER_VALIDATE_URL);
  if (isset($bookName) && !is_null($bookName) && isset($bookLink) && !is_null($bookLink)) {
    $bookId = $_POST['bookId'];
    $bookUpdate = $mysqli->prepare('UPDATE bookmark SET link = ?,link_name = ? WHERE id = ?');
    $bookUpdate->bind_param('ssi', $bookLink, $bookName, $bookId);
    $bookUpdate->execute();
    $bookUpdate->close();
    $data = array('bookName' => $bookName, 'bookLink' => $bookLink, 'bookId' => $bookId);
    header('Content-type:application/json;charset=UTF-8');
    echo json_encode($data);
    exit();
  }
} else if (isset($_POST['bookName'])) {
  $bookName = mb_strlen(trim($_POST['bookName']));
  if (isset($bookName) && !is_null($bookName)) {
    $bookId = $_POST['bookId'];
    $bookUpdate = $mysqli->prepare('UPDATE bookmark SET link_name = ? WHERE id = ?');
    $bookUpdate->bind_param('si', $bookName, $bookId);
    $bookUpdate->execute();
    $bookUpdate->close();
    $data = array('bookName' => $bookName, 'bookId' => $bookId);
    header('Content-type:application/json;charset=UTF-8');
    echo json_encode($data);
    exit();
  }
} else if (isset($_POST['bookLink'])) {
  $bookLink = filter_var($_POST['bookLink'], FILTER_VALIDATE_URL);
  if (isset($bookLink) && !is_null($bookLink)) {
    $bookId = $_POST['bookId'];
    $bookUpdate = $mysqli->prepare('UPDATE bookmark SET link = ? WHERE id = ?');
    $bookUpdate->bind_param('si', $bookLink, $bookId);
    $bookUpdate->execute();
    $bookUpdate->close();
    $data = array('bookLink' => $bookLink, 'bookId' => $bookId);
    header('Content-type:application/json;charset=UTF-8');
    echo json_encode($data);
    exit();
  }
}


if (isset($_POST['removeDrag'])) {
  $removeDragId = $_POST['removeDrag'];
  $removeDrag = $mysqli->prepare('DELETE FROM book_memo WHERE book_id = ?');
  $removeDrag->bind_param('i', $removeDragId);
  $removeDrag->execute();
  $removeDrag->close();
  $data = array('removeDrag' => $removeDragId);
  header('Content-type:application/json;charset=UTF-8');
  echo json_encode($data);
  exit();
}
