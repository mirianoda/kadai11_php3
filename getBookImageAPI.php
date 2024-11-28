<?php
try {
    // データベース接続
    include("funcs.php");
    $pdo = db_conn();

    // データベースからbook_idを取得
    $stmt = $pdo->prepare("SELECT id,book_id FROM gs_bm_table ORDER BY id ASC");
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Google Books APIキー
    $apiKey = "AIzaSyD_ASTpi0mKIWoZKka2UBlrjCLEX0r3apo";

    // 各book_idでGoogle Books APIを呼び出して表紙URLを取得
    $results = [];
    foreach ($books as $book) {
        $id = $book['id'];
        $bookId = $book['book_id'];
        $apiUrl = "https://www.googleapis.com/books/v1/volumes/{$bookId}?key={$apiKey}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        // $response = file_get_contents($apiUrl);

        if ($response === false) {
            continue; // エラーが発生した場合はスキップ
        }

        $data = json_decode($response, true);
        $coverUrl = $data['volumeInfo']['imageLinks']['thumbnail'] ?? null; // 表紙URL
        $title = $data['volumeInfo']['title'] ?? "Unknown Title";

        if ($coverUrl) {
            $results[] = [
                'id' => $id,
                'book_id' => $bookId,
                'title' => $title,
                'cover_url' => $coverUrl,
            ];
        }
    }

    // // cURLマルチハンドルの初期化
    // $multiHandle = curl_multi_init();
    // $curlHandles = [];
    // $results = [];

    // // 各リクエストを追加
    // foreach ($books as $book) {
    //     $id = $book['id'];
    //     $bookId = $book['book_id'];
    //     $apiUrl = "https://www.googleapis.com/books/v1/volumes/{$bookId}?key={$apiKey}";

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $apiUrl);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_multi_add_handle($multiHandle, $ch);

    //     $curlHandles[$id] = $ch;
    // }

    // // リクエストの実行
    // do {
    //     $status = curl_multi_exec($multiHandle, $active);
    //     curl_multi_select($multiHandle);
    // } while ($active > 0 && $status == CURLM_OK);

    // // 結果を取得
    // foreach ($curlHandles as $id => $ch) {
    //     $response = curl_multi_getcontent($ch);
    //     $data = json_decode($response, true);
    //     $coverUrl = $data['volumeInfo']['imageLinks']['thumbnail'] ?? null;

    //     if ($coverUrl) {
    //         $results[] = [
    //             'id' => $id,
    //             'book_id' => $books[$id]['book_id'],
    //             'cover_url' => $coverUrl,
    //         ];
    //     }

    //     curl_multi_remove_handle($multiHandle, $ch);
    //     curl_close($ch);
    // }

    // // マルチハンドルを閉じる
    // curl_multi_close($multiHandle);


    // JSON形式で結果を返す
    header('Content-Type: application/json');
    echo json_encode($results);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
    exit;
}
