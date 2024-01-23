<?php
class Model
{
    public $koneksi;
    public $tabel;
    public $respon;
    public $errors = [];
    public $result = [];

    public function __construct(
        $db_host,
        $db_user,
        $db_password,
        $db_name,
        $tabel
    ) {
        try {
            $this->koneksi = mysqli_connect(
                $db_host,
                $db_user,
                $db_password,
                $db_name
            );
            $this->tabel = $tabel;
        } catch (Exception $e) {
            $error = ['status' => 'gagal', 'message' =>
             $e->getMessage(),"trace"=>$e->getTrace()];
            echo json_encode($error);
        }
    }

    public function findAll()
    {
        try {
            $tabel = $this->tabel;
            $query = "select * from $tabel";
        
            $hasil = mysqli_query($this->koneksi, $query);
        
            $item = [];
            while ($a = mysqli_fetch_assoc($hasil)) {
                $item[] = $a;
            }
            if (!empty($item)) {
                $this->respon = ['status' => 'ok',
                'data' => $item];
            } else {
                
                $this->respon = ['status' => 'gagal',
                                 'data' => 'data kosong'];
            }
        
            echo json_encode($this->respon);
        } catch (Exception $e) {
            $error = ['status' => 'gagal', 'message' =>
             $e->getMessage(),"trace"=>$e->getTrace()];
            echo json_encode($error);
        }
    }
    public function findAllcustom($param)
    {
        try {
            $tabel = $this->tabel;
            $query = "select * from $tabel $param";
        
            $hasil = mysqli_query($this->koneksi, $query);
        
            $item = [];
            while ($a = mysqli_fetch_assoc($hasil)) {
                $item[] = $a;
            }
            if (!empty($item)) {
                $this->respon = ['status' => 'ok',
                'data' => $item];
            } else {
                
                $this->respon = ['status' => 'gagal',
                                 'data' => 'data kosong'];
            }
        
            echo json_encode($this->respon);
        } catch (Exception $e) {
            $error = ['status' => 'gagal', 'message' =>
             $e->getMessage(),"trace"=>$e->getTrace()];
            echo json_encode($error);
        }
    }
    public function find($kolom, $operator, $nilai)
    {
        try {
            if ($operator == 'eq') {
                $op = "='$nilai'";
            }
            if ($operator == 'lk') {
                $op = "like'$nilai'";
            }
            if ($operator == 'lks') {
                $op = "like'%$nilai%'";
            }
            $tabel = $this->tabel;
            $query = "select * from $tabel where $kolom $op";
            $hasil = mysqli_query($this->koneksi, $query);

            $item = [];
            while ($a = mysqli_fetch_assoc($hasil)) {
                $item[] = $a;
            }
            if (!empty($item)) {
                
                $this->respon = ['status' => 'ok',
                                 'data' => $item];
            } else {
                $this->respon = ['status' => 'gagal',
                                 'message' => 'data kosong'];
            }
    
            echo json_encode($this->respon);
        } catch (Exception $e) {
            $error = ['status' => 'gagal', 'message' =>
             $e->getMessage(),"trace"=>$e->getTrace()];
            echo json_encode($error);
        }
    }
    public function input($post)
    {
        try {
            unset($post['perintah']);
            $data = "";
            foreach ($post as $key => $value) {
                $data .= ",'" . $value . "'";
            }
            $data = substr($data, 1);
            $tabel = $this->tabel;
            $sql = "insert into $tabel values (NULL,$data)";
            $hasil =  mysqli_query($this->koneksi, $sql);
            if ($hasil) {
                if (mysqli_affected_rows($this->koneksi)) {
                    $this->respon = ['status' => 'ok',
                    'message' => 'berhasil'];
                } else {
                    $this->respon = ['status' => 'gagal',
                    'message' => "Gagal Meyimpan Data"];
                }
            } else {
                $this->respon = ['status' => 'gagal',
                'message' => mysqli_error($this->koneksi)];
            }
    
            echo json_encode($this->respon);
        } catch (Exception $e) {
            $error = ['status' => 'gagal', 'message' =>
            $e->getMessage(),"trace"=>$e->getTrace()];
            echo json_encode($error);
        }
        
    }
    
    public function edit($id, $post, $kolom)
    {
        try {
            if (!empty($post[$kolom])) {
                unset($post[$kolom]);
            }
            $data = "";
            foreach ($post as $key => $value) {
                $data .= "," .$key . "=" . "'".$value."'";
                $data = ltrim($data, ',');
    
            }
            $tabel = $this->tabel;
            $sql = "update $tabel set $data where $kolom = '$id'";
            mysqli_query($this->koneksi, $sql);
            if (mysqli_affected_rows($this->koneksi) > 0) {
                $this->respon= ['status'=>'ok','messege'=>'berhasil'];
            }
            echo json_encode($this->respon);
        } catch (Exception $e) {
            $error = ['status' => 'gagal', 'message' =>
            $e->getMessage(),"trace"=>$e->getTrace()];
            echo json_encode($error);
        }
    }
    public function hapus($id, $kolom)
    {
        try {
            $tabel = $this->tabel;
            $sql = "delete from $tabel where $kolom = '$id'";
    
            mysqli_query($this->koneksi, $sql);
            if (mysqli_affected_rows($this->koneksi)) {
                $this->respon= ['status'=>'ok',
                               'messege'=>'berhasil'];
            }
            echo json_encode($this->respon);
        } catch (Exception $e) {
            $error = ['status' => 'gagal', 'message' =>
            $e->getMessage(),"trace"=>$e->getTrace()];
            echo json_encode($error);
        }
    }
    public function upload($file, $post, $path)
    {
        try {
            // unset($post['perintah']);
            $data = "";
            foreach ($post as $key => $value) {
                $data .= ",'" . $value . "'";
            }
            $data = substr($data, 1);
            $tabel = $this->tabel;
            $nama = explode('.', $file['file']['name']);
            $extension = strtolower(end($nama));
            $namaAcak = md5(uniqid(mt_rand()));
            $namaFile = $namaAcak.".".$extension;
            $sql = "insert into $tabel values (NULL,$data,'$namaFile')";
            move_uploaded_file(
                $file['file']['tmp_name'],
                $path.$namaFile
            );
            $hasil =  mysqli_query($this->koneksi, $sql);
            if ($hasil) {
                if (mysqli_affected_rows($this->koneksi)) {
                    $this->respon = ['status' => 'ok',
                    'message' => 'berhasil'];
                } else {
                    $this->respon = ['status' => 'gagal',
                     'message' => mysqli_error($this->koneksi)];
                }
            } else {
                $this->respon = ['status' => 'gagal',
                'message' => mysqli_error($this->koneksi)];
            }
    
            echo json_encode($this->respon);
        } catch (Exception $e) {
            $error = ['status' => 'gagal', 'message' =>
             $e->getMessage(),"trace"=>$e->getTrace()];
            echo json_encode($error);
        }
    }
 public function join($sql,$where)
    {
        try {
            $tabel = $this->tabel;
            if (is_null($where)) {
                
                $query = $sql;
            }            
            else {
                $query = $sql .$where ;
                
                
            }
            
            
            $hasil = mysqli_query($this->koneksi, $query);
        
            $item = [];
            while ($a = mysqli_fetch_assoc($hasil)) {
                $item[] = $a;
            }
            if (!empty($item)) {
                $this->respon = ['status' => 'ok',
                'data' => $item];
            } else {
                
                $this->respon = ['status' => 'gagal',
                                 'data' => 'data kosong'];
            }
        
            echo json_encode($this->respon);
        } catch (Exception $e) {
            $error = ['status' => 'gagal', 'message' =>
             $e->getMessage(),"trace"=>$e->getTrace()];
            echo json_encode($error);
        }
    }   
    
      public function dashboard($kolom,$group)
    {
        try {
            $tabel = $this->tabel;
            $query = "SELECT COUNT($kolom) as nama ,tglCi as tanggal FROM $tabel GROUP BY MONTH($group);";
        
            $hasil = mysqli_query($this->koneksi, $query);
        
            $item = [];
            while ($a = mysqli_fetch_assoc($hasil)) {
                $item[] = $a;
            }
            if (!empty($item)) {
                $this->respon = ['status' => 'ok',
                'data' => $item];
            } else {
                
                $this->respon = ['status' => 'gagal',
                                 'data' => 'data kosong'];
            }
        
            echo json_encode($this->respon);
        } catch (Exception $e) {
            $error = ['status' => 'gagal', 'message' =>
             $e->getMessage(),"trace"=>$e->getTrace()];
            echo json_encode($error);
        }
    }
   
}
