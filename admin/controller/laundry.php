<?php
$con->auth();
$conn=$con->koneksi();
switch (@$_GET['page']){
    case 'add':
        $paket="select * from paket";
        $paket=$conn->query($paket);
        $sql="select * from pegawai";
        $pegawai=$conn->query($sql);
        $content="views/laundry/tambah.php";
        include_once 'views/template.php';
    break;
    case 'save':
        if($_SERVER['REQUEST_METHOD']=="POST"){
            //validasi
            if(empty($_POST['nama_pelanggan'])){
                $err['nama_pelanggan']="Nama Pelanggan Wajib";
            }
            if(empty($_POST['jenis_cucian'])){
                $err['jenis_cucian']="Jenis Cucian Wajib";
            }
            if(!is_numeric($_POST['id_paket'])){
                $err['id_paket']="Paket Wajib Terisi";
            }
            if(!is_numeric($_POST['id_pegawai'])){
                $err['id_pegawai']="Pegawai Wajib Terisi";
            }
            if(!empty($_FILES['photos']['name'])){
            $target_dir = "../media/";
            $photos=basename($_FILES["photos"]["name"]);
            $target_file = $target_dir . $photos;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["photos"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
            }

            // Check if file already exists
            if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["photos"]["size"] > 500000000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
            }

            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
            if (move_uploaded_file($_FILES["photos"]["tmp_name"], $target_file)) {
                //echo "The file ". htmlspecialchars( basename( $_FILES["photos"]["name"])). " has been uploaded.";
                $_POST['photos']=$photos;
                //if(isset($_POST['photos_old']) && $_POST['photos_old']!=''){
                //    unlink($target_dir.$_POST['photos_old']);
                //}else{
                //    echo "Succses";
                //}
            } else {
                //echo "Sorry, there was an error uploading your file.";
                $err["photos"]="Sorry";
            }
            }
            }
            if(!isset($err)){
                $id_pegawai=$_SESSION['login']['id'];
                if(!empty($_POST['id_cucian'])){
                    //update
                    if (isset($_POST['photos'])){
                        $sql="UPDATE cucian SET jenis_cucian='$_POST[jenis_cucian]',nama_pelanggan='$_POST[nama_pelanggan]', id_paket='$_POST[id_paket]',berat='$_POST[berat]',
                    id_pegawai='$_POST[id_pegawai]',photos='$_POST[photos]' WHERE id_cucian='$_POST[id_cucian]'";
                    }else{
                    $sql="UPDATE cucian SET jenis_cucian='$_POST[jenis_cucian]',nama_pelanggan='$_POST[nama_pelanggan]', id_paket='$_POST[id_paket]',berat='$_POST[berat]',
                    id_pegawai='$_POST[id_pegawai]' WHERE id_cucian='$_POST[id_cucian]'";
                }
            }
                else{
                    //save
                    if(isset($_POST['photos'])){
                    $sql = "INSERT INTO cucian (nama_pelanggan,jenis_cucian,id_paket,berat,id_pegawai,photos) 
                    VALUES ('$_POST[nama_pelanggan]','$_POST[jenis_cucian]','$_POST[id_paket]','$_POST[berat]','$_POST[id_pegawai]','$_POST[photos]')";
                    }else{
                    $sql = "INSERT INTO cucian (nama_pelanggan,jenis_cucian,id_paket,berat,id_pegawai) 
                    VALUES ('$_POST[nama_pelanggan]','$_POST[jenis_cucian]','$_POST[id_paket]','$_POST[berat]','$_POST[id_pegawai]')";
                    }
                    
            }
            if ($conn->query($sql) === TRUE) {
                header('Location:http://localhost/laundry/admin/index.php?mod=laundry');
            } else {
                $err['msg']= "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        }else{
            $err['msg']="tidak diijinkan";
        }
        if(isset($err)){
            $paket="select * from paket";
            $paket=$conn->query($paket);
            $sql="select * from pegawai";
            $pegawai=$conn->query($sql);
            $content="views/laundry/tambah.php";
            include_once 'views/template.php';
        }
    break;
    case 'edit':
        $laundry ="select * from cucian where id_cucian='$_GET[id]'";
        $laundry=$conn->query($laundry);
        $_POST=$laundry->fetch_assoc();
        $_POST['id_paket']=$_POST['id_paket'];
        $_POST['id_cucian']=$_POST['id_cucian'];
        //var_dump($laundry);
        $paket="select * from paket";
        $paket=$conn->query($paket);
        
        $sql="select * from pegawai";
        $pegawai=$conn->query($sql);
        $content="views/laundry/tambah.php";
        include_once 'views/template.php';
    break;
    case 'delete';
        $laundry ="delete from cucian where id_cucian='$_GET[id]'";
        $laundry=$conn->query($laundry);
        header('Location: '.$con->site_url().'/admin/index.php?mod=laundry');
    break;
    default:
        $sql="SELECT*FROM cucian
        INNER JOIN pegawai ON cucian.id_pegawai=pegawai.id_pegawai
        INNER JOIN paket ON cucian.id_paket=paket.id_paket";
        $laundry=$conn->query($sql);
        $conn->close();
        $content="views/laundry/tampil.php";
        include_once 'views/template.php';
}
?>
