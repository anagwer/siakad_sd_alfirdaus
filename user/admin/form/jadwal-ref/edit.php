<head>
     <script type="text/javascript" src="form/ckeditor/ckeditor.js"></script>
</head> 
<?php 
$id = $_GET['id'];
$q=mysqli_query($conn, "SELECT * from jadwal where id_jadwal='$id'")or die("query salah");
$d=mysqli_fetch_array($q);



 ?>
 <form action="form/jadwal/simpanedit_jadwal.php" method="post" enctype="multipart/form-data">
                                           
      
    <div class="form-group">
        <label>Judul</label>
      <input type="hidden" name="id" class="form-control" value="<?php echo $d['id_jadwal'] ?>">
      <input type="text" name="judul" class="form-control" value="<?php echo $d['nama_jadwal'] ?>">
    </div>   
       <div class="form-group">
        <label>Keterangan</label>
       <textarea name="ket" class="ckeditor" id="ckedtor"  required ><?php echo $d['keterangan'] ?></textarea>
    </div>                                    
   
<div class="form-group">
        <label>File</label> <br>
      <input type="file" name="file" >
    </div>   
                            
    
                                   
              

     











    <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
        
        <span id="payment-button-amount">Simpan</span>
      </button>
                                           
                                        </form>