<?php
require "Horus.php";

$app = new Horus;
$app->autoload("classes");

$app->set([
    "Access-Control-Allow-Origin" => "*",
    "Access-Control-Allow-Credentials" => "false",
    "Access-Control-Allow-Methods" => "OPTIONS, GET, POST, PUT, DELETE",
    "Access-Control-Allow-Headers" => "Content-Type"
]);

// $app->on("", function() {});
$app->on("/", function() {

    $this->end("Hello world", 200);

});

/*get all users*/

$app->on("GET /penjual", function() {

    $dbh = db::connect();
    $sql = "SELECT * from Penjual";

    $query = $dbh->prepare($sql);
    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    $this->json($result)->end();

});


$app->on("GET /barang", function() {
    $dbh = db::connect();
    $sql = "SELECT * from Barang";

    $query = $dbh->prepare($sql);
    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    $this->json($result)->end();

});


$app->on("POST /barang", function() {

    // $this->json($this->body->harga); exit;

    $dbh = db::connect();
    $sql = "INSERT INTO barang(namabarang,harga,gambar,kategori) VALUES (:namabarang,:harga,:gambar,:kategori)";
    $param1 = [
        ":namabarang"=> $this->body->namabarang,
        ":harga"=> $this->body->harga,
        ":gambar"=> $this->body->gambar,
        ":kategori"=> $this->body->kategori
    ];

    $query = $dbh->prepare($sql);
    $query->execute($param1);

    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    $this->json($result)->end();

});

$app->on("POST /penjual", function() {

    // $this->json($this->body->harga); exit;

    $dbh = db::connect();
    $sql = "INSERT INTO penjual(idpenjual,katalaluan,email,namakedai,alamat) VALUES (:idpenjual,:katalaluan,:email,:namakedai,:alamat)";
    $param1 = [
        ":idpenjual"=> $this->body->idpenjual,
        ":katalaluan"=> $this->body->katalaluan,
        ":email"=> $this->body->email,
        ":namakedai"=> $this->body->namakedai,
        ":alamat"=> $this->body->alamat
    ];

    $query = $dbh->prepare($sql);
    $query->execute($param1);

    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    $this->json($result)->end();

});

$app->on("POST /login", function() {

    // $this->json($this->body); exit;

    if (!$this->body['idpenjual'] && !$this->body['katalaluan']) {
        $this->json(["message"=> "Gagal untuk log masuk"])->end();
    }

    $dbh = db::connect();
    $sql = "SELECT (idpenjual,katalaluan) from penjual WHERE idpenjual=:idpenjual AND katalaluan=:katalaluan";
    $param = [
        ":idpenjual"=> $this->body['idpenjual'],
        ":katalaluan"=> $this->body['katalaluan'],
    ];

    $query = $dbh->prepare($sql);
    $query->execute($param);

    $result = $query->fetch(PDO::FETCH_OBJ);

    if($result) {
        $this->json(["success"=> true, "data" => $result])->end();
    } else {
         $this->json(["message"=> "Salah id penjual/katalaluan"])->end();
    }
});










/*fetch username and password*/

/*$app->on("POST /login", function() {

    // $this->json($this->body); exit;

    if (!$this->body['username'] && !$this->body['password']) {
        $this->json(["message"=> "Failed to login"])->end();
    }

    $dbh = db::connect();
    $sql = "SELECT * from users WHERE username=:username AND password=:password";
    $param = [
        ":username"=> $this->body['username'],
        ":password"=> $this->body['password'],
    ];

    $query = $dbh->prepare($sql);
    $query->execute($param);

    $result = $query->fetch(PDO::FETCH_OBJ);

    if($result) {
        $this->json(["success"=> true, "data" => $result])->end();
    } else {
         $this->json(["message"=> "Wrong username/password"])->end();
    }
});


/*create devices*/

/*$app->on("POST /devices", function() {

    $dbh = db::connect();
    $sql = "INSERT INTO devices(name,description) VALUES (:name,:description)";
    $param1 = [
        ":name"=> $this->body->name,
        ":description"=> $this->body->descrption,
    ];

    $query = $dbh->prepare($sql);
    $query->execute($param1);

    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    $this->json($result)->end();

});

/*update devices*/

/*$app->on("PUT /devices", function() {

    // TODO: Check exist status

    $dbh = db::connect();
    $sql = "UPDATE devices SET status=:status, in_repair=:in_repair WHERE id=:id";
    $param1 = [
        ":status"=> $this->body['status'],
        ":id"=> $this->body['id'],
        ":in_repair"=> $this->body['in_repair'],
    ];

    $query = $dbh->prepare($sql);
    $query->execute($param1);

    $result = $query->rowCount();

    $this->json($result)->end();

});



/*get devices*/
/*$app->on("GET /devices", function() {

    $dbh = db::connect();
    $sql = "SELECT * from devices";

    $query = $dbh->prepare($sql);
    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    $this->json($result)->end();

});


