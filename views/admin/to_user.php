<section class="title">
    <h4><?php echo lang('simple_order:order'); ?></h4>
</section>

<section class="item">
    <div class="content">
        <fieldset id="filters">
            <legend>Filters</legend>
            <ul>
                <li class="">
                    <label for="f_status">Filter By Status Pengerjaan: </label>
                    <select name="status" id="status">
                        <option value="belum">Belum dikerjakan</option>
                        <option value="sudah">Sudah dikerjakan</option>
                        <option value="expired">Expired</option>
                    </select> 
                </li>
                <li>
                    <label for="f_status">Filter By Paket: </label>
                    <?php echo form_dropdown('paket', $paket, 'all', 'id="paket"'); ?>
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
            var oldstatus = $('#stream-table').attr('data-status');
            var oldpaket = $('#stream-table').attr('data-paket');
            if(oldstatus != status || oldpaket != paket){
                $('#stream-table').css('opacity', '.5');
                $.ajax({
                    url: BASE_URL + 'admin/tryout/to_user/table/',
                    type: 'POST',
                    data: {status: status, paket: paket}
                }).done(function(res){
                    $('#stream-table').empty()
                    .html(res)
                    .css('opacity', '1')
                    .removeAttr(oldstatus).attr('data-status', status)
                    .removeAttr(oldpaket).attr('data-paket', paket);
                });
            }
            return false;
        });
    </script>
    <style>.button{display:inline-block !important;}
    #filters > ul  > li {display: inline-block;}
    </style>

</div>
</section>