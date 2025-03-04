<?php
require_once "config.php";
$request_method=$_SERVER["REQUEST_METHOD"];
switch ($request_method) {
   case 'GET':
         if(!empty($_GET["id_masyarakat"]))
         {
            $id=intval($_GET["id_masyarakat"]);
            get_masyarakat($id);
         }
         else
         {
            get_masyarakatt();
         }
         break;
   case 'POST':
         if(!empty($_GET["id_masyarakat"]))
         {
            $id=intval($_GET["id_masyarakat"]);
            update_masyarakat($id);
         }
         else
         {
            insert_masyarakat();
         }     
         break; 
   case 'DELETE':
          $id=intval($_GET["id_masyarakat"]);
            delete_masyarakat($id);
            break;
   default:
      // Invalid Request Method
         header("HTTP/1.0 405 Method Not Allowed");
         break;
      break;
 }



   function get_masyarakatt()
   {
      global $mysqli;
      $query="SELECT * FROM masyarakat";
      $data=array();
      $result=$mysqli->query($query);
      while($row=mysqli_fetch_object($result))
      {
         $data[]=$row;
      }
      $response=array(
                     'status' => 1,
                     'message' =>'Get List Masyarakat Successfully.',
                     'data' => $data
                  );
      header('Content-Type: application/json');
      echo json_encode($response);
   }
 
   function get_masyarakat($id=0)
   {
      global $mysqli;
      $query="SELECT * FROM masyarakat";
      if($id != 0)
      {
         $query.=" WHERE id_masyarakat=".$id." LIMIT 1";
      }
      $data=array();
      $result=$mysqli->query($query);
      while($row=mysqli_fetch_object($result))
      {
         $data[]=$row;
      }
      $response=array(
                     'status' => 1,
                     'message' =>'Get Masyarakat Successfully.',
                     'data' => $data
                  );
      header('Content-Type: application/json');
      echo json_encode($response);
        
   }
 
   function insert_masyarakat()
      {
         global $mysqli;
         if(!empty($_POST["nik"]) && !empty($_POST["nama"]) && !empty($_POST["alamat"])){
            $data=$_POST;
         }else{
            $data = json_decode(file_get_contents('php://input'), true);
         }

         $arrcheckpost = array('nik' => '','nama' => '','alamat' => '');
         $hitung = count(array_intersect_key($data, $arrcheckpost));
         if($hitung == count($arrcheckpost)){
          
               $result = mysqli_query($mysqli, "INSERT INTO masyarakat SET
               nik = '$data[nik]',
               nama = '$data[nama]',
               alamat = '$data[alamat]'");                
               if($result)
               {
                  $response=array(
                     'status' => 1,
                     'message' =>'Masyarakat Added Successfully.'
                  );
               }
               else
               {
                  $response=array(
                     'status' => 0,
                     'message' =>'Masyarakat Addition Failed.'
                  );
               }
         }else{
            $response=array(
                     'status' => 0,
                     'message' =>'Parameter Do Not Match'
                  );
         }
         header('Content-Type: application/json');
         echo json_encode($response);
      }
 
   function update_masyarakat($id)
      {
         global $mysqli;
         if(!empty($_POST["nik"]) && !empty($_POST["nama"]) && !empty($_POST["alamat"])){
            $data=$_POST;
         }else{
            $data = json_decode(file_get_contents('php://input'), true);
         }

         $arrcheckpost = array('nik' => '','nama' => '','alamat' => '');
         $hitung = count(array_intersect_key($data, $arrcheckpost));
         if($hitung == count($arrcheckpost)){
          
              $result = mysqli_query($mysqli, "UPDATE masyarakat SET
	      nik = '$data[nik]',
              nama = '$data[nama]',
              alamat = '$data[alamat]'
              WHERE id_masyarakat='$id'");
          
            if($result)
            {
               $response=array(
                  'status' => 1,
                  'message' =>'Masyarakat Updated Successfully.'
               );
            }
            else
            {
               $response=array(
                  'status' => 0,
                  'message' =>'Masyarakat Updation Failed.'
               );
            }
         }else{
            $response=array(
                     'status' => 0,
                     'message' =>'Parameter Do Not Match'
                  );
         }
         header('Content-Type: application/json');
         echo json_encode($response);
      }
 
   function delete_masyarakat($id)
   {
      global $mysqli;
      $query="DELETE FROM masyarakat WHERE id_masyarakat=".$id;
      if(mysqli_query($mysqli, $query))
      {
         $response=array(
            'status' => 1,
            'message' =>'Masyarakat Deleted Successfully.'
         );
      }
      else
      {
         $response=array(
            'status' => 0,
            'message' =>'Masyarakat Deletion Failed.'
         );
      }
      header('Content-Type: application/json');
      echo json_encode($response);
   }

 
?> 
