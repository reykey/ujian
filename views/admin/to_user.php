<section class="title">
    <h4>Tryout User</h4>
</section>

<section class="item">
    <div class="content">
        <fieldset id="filters">
            <legend>Filters</legend>
            <ul>
                <li class="">
                    <label for="f_status">Status: </label>
                    <select name="status" id="status">
                        <option value="sudah">Sudah dikerjakan</option>
                        <option value="belum">Belum dikerjakan</option>
                        <option value="expired">Expired</option>
                    </select> 
                </li>
                <li>
                    <label for="f_status">Paket: </label>
                    <?php echo form_dropdown('paket', $paket, 'all', 'id="paket"'); ?>
                </li>
                <li>
                    <label for="f_status">Nama: </label>
                    <input type="text" name="nama" id="nama">
                </li>
            </ul>
        </fieldset>

    <div id="stream-table" class="streams">
        <?php echo $datax; ?>
    </div>

    <script>
        $('#status, #paket').change(function(){
            var status = $('#status').val();
            var paket = $('#paket').val();
            var nama = $('#nama').val();
            var oldstatus = $('#stream-table').attr('data-status');
            var oldpaket = $('#stream-table').attr('data-paket');
            var oldnama = $('#stream-table').attr('data-nama');
            if(oldstatus != status || oldpaket != paket || oldnama != nama || oldnama != ""){
                $('#stream-table').css('opacity', '.5');
                $.ajax({
                    url: BASE_URL + 'admin/tryout/to_user/table/',
                    type: 'POST',
                    data: {status: status, paket: paket, nama: nama}
                }).done(function(res){
                    $('#stream-table').empty()
                    .html(res)
                    .css('opacity', '1')
                    .removeAttr(oldstatus).attr('data-status', status)
                    .removeAttr(oldpaket).attr('data-paket', paket)
                    .removeAttr(oldpnama).attr('data-nama', nama);
                });
            }
            return false;
        });
        $('#nama').bind('keypress', function(e){
            var code = e.keyCode || e.which;
            if(code == 13) { //Enter keycode
                $('#status, #paket').change();
            }
        })
    </script>
    <style>.button{display:inline-block !important;}
    #filters > ul  > li {display: inline-block;}
    </style>

</div>
</section>