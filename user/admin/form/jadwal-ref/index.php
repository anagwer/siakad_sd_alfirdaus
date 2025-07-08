<?php   if ($_SESSION['level']=='Administrator') { ?>
  

<?php } ?>
<?php if (isset($_SESSION['pesan'])) {
  echo $_SESSION['pesan'];
  unset($_SESSION['pesan']);
} ?>

<?php

    $q=mysqli_query($conn, "SELECT * from jadwal");
    $no=1;
    while ($d = mysqli_fetch_array($q)) { 
      $pecah = explode('-', $d['tgl_input']);
      $tgli  = $pecah[2].'-'.$pecah[1].'-'.$pecah[0];?>
     



     <div class="box-header">
      <h3 class="box-title">
        <?php echo $d['nama_jadwal'] ?>
      </h3>

     
      
     
      <?php echo $d['keterangan'];
      if ($d['file']!='') {
        echo '<br><br><a href="form/jadwal/file/'.$d['file'].'" target="_blank">Lihat File</a>';
      }



      if ($_SESSION['level']=='Administrator') { ?>
        <br> <br>
      <?php }else{}
       ?>
      
     
      
    </div>



     <?php } ?>
  