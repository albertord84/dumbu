    <br><br>
<div id="admin_form2">    
    <div class="row">
        <div class="col-xs-1"></div>
        <div class="col-xs-2">
            <div class="left">
                <div class="row">
                    <input type="radio" name="pendence_option" value="1" checked="checked" /><b> Listar pendências</b>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <select id="pendences_date" class="form-control" style="width: 100px">
                            <option value="-50" <?php if (isset($form_filter) && $form_filter[pendences_date] == "-50") { echo 'selected'; } ?> >Anteriores</option>
                            <option value="-30" <?php if (isset($form_filter) && $form_filter[pendences_date] == "-30") { echo 'selected'; } ?> >-30 dias</option>
                            <option value="-7" <?php if (isset($form_filter) && $form_filter[pendences_date] == "-7") { echo 'selected'; } ?> >-7 dias</option>
                            <option value="-1" <?php if (isset($form_filter) && $form_filter[pendences_date] == "-1") { echo 'selected'; } ?> >Ontem</option>
                            <option value="0" <?php if (isset($form_filter)) { if ($form_filter[pendences_date] == "0") { echo 'selected'; } } else { echo 'selected'; } ?> >Hoje</option>
                            <option value="1" <?php if (isset($form_filter) && $form_filter[pendences_date] == "1") { echo 'selected'; } ?> >Amanhã</option>
                            <option value="7" <?php if (isset($form_filter) && $form_filter[pendences_date] == "7") { echo 'selected'; } ?> >+7 dias</option>
                            <option value="30" <?php if (isset($form_filter) && $form_filter[pendences_date] == "30") { echo 'selected'; } ?> >+30 dias</option>
                            <option value="50" <?php if (isset($form_filter) && $form_filter[pendences_date] == "50") { echo 'selected'; } ?> >Posteriores</option>
                        </select>
                    </div>
                    <div class="col-xs-6">
                        <button  style="min-width:100px" id = "execute_query2" type="button" class="btn btn-success ladda-button"  data-style="expand-left" data-spinner-color="#ffffff">
                            <span class="ladda-label">Listar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-8">
            
        </div>
        <div class="col-xs-1"></div>            
    </div>
</div>
<div class="row">
    <div class="col-xs-1"></div>
    <div class="col-xs-10">
        <?php
            if (isset($result)) {
                $num_rows = count($result);
                echo '<br><p><b style="color:red">Total de registros: </b><b>'.$num_rows.'</b></p><br>';
            }
        ?>
    </div>
    <div class="col-xs-1"></div>
</div>