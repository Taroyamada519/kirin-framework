# Kirin Framework (キリン)

エレガントなPHPフレームワークを使用してREST APIを実装するためのフレームワークです。MVCアーキテクチャに基づいており、ルーティング、データベース操作、バリデーションなどの基本的な機能を提供します。

## 必要要件

- PHP 7.4以上
- Composer
- SQLite3

## インストール方法

1. プロジェクトをクローンまたはダウンロード:
```bash
git clone [repository-url]
cd kirin-framework
```

2. Composerの依存関係をインストール:
```bash
composer install
```

## 基本的な使い方

### 1. サーバーの起動

開発サーバーを起動するには以下のコマンドを実行します：

```bash
php -S localhost:8000 -t public
```

### 2. ルーティングの定義

ルートは `public/index.php` で定義します：

```php
$router->get('/api/users', 'UserController@index');
$router->post('/api/users', 'UserController@store');
$router->get('/api/users/{id}', 'UserController@show');
$router->put('/api/users/{id}', 'UserController@update');
$router->delete('/api/users/{id}', 'UserController@delete');
```

### 3. コントローラーの作成

新しいコントローラーを作成する場合は、`src/Controllers`ディレクトリに配置し、`BaseController`を継承します：

```php
namespace App\Controllers;

class ExampleController extends BaseController {
    public function index() {
        // 一覧取得処理
        $this->response->success($data);
    }

    public function show() {
        $id = $this->request->getParam('id');
        // 詳細取得処理
        $this->response->success($data);
    }
}
```

### 4. モデルの作成

新しいモデルを作成する場合は、`src/Models`ディレクトリに配置し、`BaseModel`を継承します：

```php
namespace App\Models;

class Example extends BaseModel {
    protected $table = 'examples';
    protected $fillable = ['name', 'description'];
}
```

### 5. バリデーション

コントローラーでバリデーションを使用する例：

```php
$errors = $this->validate($data, [
    'name' => 'required|min:2|max:50',
    'email' => 'required|email'
]);

if (!empty($errors)) {
    $this->response->error(['validation' => $errors], 422);
}
```

利用可能なバリデーションルール：
- required: 必須項目
- email: メールアドレス形式
- min:n: 最小文字数
- max:n: 最大文字数

### 6. データベース操作

モデルを通じてデータベースを操作する例：

```php
// 全件取得
$items = $model->all();

// IDで検索
$item = $model->find($id);

// 作成
$id = $model->create([
    'name' => 'Example',
    'email' => 'example@example.com'
]);

// 更新
$model->update($id, [
    'name' => 'Updated Example'
]);

// 削除
$model->delete($id);
```

## APIエンドポイントの動作確認

以下は実装済みのユーザーAPIエンドポイントの動作確認例です：

### 1. ユーザーの作成
```bash
$ curl -X POST -H "Content-Type: application/json" -d '{"name":"John Doe","email":"john@example.com"}' http://localhost:8000/api/users

# レスポンス
{
    "error": false,
    "message": "User created successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-02-20 12:56:32",
        "updated_at": "2025-02-20 12:56:32"
    }
}
```

### 2. ユーザー一覧の取得
```bash
$ curl http://localhost:8000/api/users

# レスポンス
{
    "error": false,
    "message": "Success",
    "data": [
        {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2025-02-20 12:56:32",
            "updated_at": "2025-02-20 12:56:32"
        }
    ]
}
```

### 3. 特定のユーザーの取得
```bash
$ curl http://localhost:8000/api/users/1

# レスポンス
{
    "error": false,
    "message": "Success",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-02-20 12:56:32",
        "updated_at": "2025-02-20 12:56:32"
    }
}
```

### 4. ユーザーの更新
```bash
$ curl -X PUT -H "Content-Type: application/json" -d '{"name":"John Smith","email":"john@example.com"}' http://localhost:8000/api/users/1

# レスポンス
{
    "error": false,
    "message": "User updated successfully",
    "data": {
        "id": 1,
        "name": "John Smith",
        "email": "john@example.com",
        "created_at": "2025-02-20 12:56:32",
        "updated_at": "2025-02-20 12:58:23"
    }
}
```

### 5. ユーザーの削除
```bash
$ curl -X DELETE http://localhost:8000/api/users/1

# レスポンス
{
    "error": false,
    "message": "User deleted successfully"
}
```

## レスポンス形式

### 成功時のレスポンス
```json
{
    "error": false,
    "message": "Success",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-02-20 12:00:00",
        "updated_at": "2025-02-20 12:00:00"
    }
}
```

### エラー時のレスポンス
```json
{
    "error": true,
    "message": "Validation error",
    "validation": {
        "email": "This email is already registered"
    }
}
```

## ディレクトリ構造

```
kirin-framework/
├── public/
│   ├── index.php      # エントリーポイント
│   └── .htaccess      # Apache用リライトルール
├── src/
│   ├── Core/          # フレームワークのコア機能
│   │   ├── Router.php
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── Database.php
│   ├── Controllers/   # コントローラー
│   │   ├── BaseController.php
│   │   └── UserController.php
│   └── Models/        # モデル
│       ├── BaseModel.php
│       └── User.php
├── composer.json      # Composer設定
└── README.md         # ドキュメント
