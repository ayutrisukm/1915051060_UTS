<div class="row">
    <div class="pull-left">
        <h4>Daftar Pelanggan</h4>
    </div>
</div>
<div class="row">
    <table class="table">
        <thead>
            <tr>
                <td>
                    No.
                </td>
                <td>Nama</td>
                <td>Jenis Cucian</td>
                <td>Paket</td>
                <th>Berat Cucian</th>
            </tr>
        </thead>
        <tbody>
            <?php if($laundry != NULL){  
                $no=1;
                foreach($laundry as $row){?>
                    <tr>
                        <td><?=$no;?></td>
                        <td><?=$row['nama_pelanggan']?></td>
                        <td><?=$row['jenis_cucian'];?></td>
                        <td><?=$row['nama_paket']?></td>
                        <td><?=$row['berat']?></td>
                    </tr>
               <?php $no++; }
            }?>
        </tbody>
    </table>
</div>